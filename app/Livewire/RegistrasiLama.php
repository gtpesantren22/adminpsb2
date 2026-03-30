<?php

namespace App\Livewire;

use App\Models\Registrasi;
use App\Models\Santri;
use App\Models\Tanggungan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Number;
use Livewire\Component;
use Livewire\WithPagination;

class RegistrasiLama extends Component
{
    use WithPagination;

    public $search = '';
    public $paginate = 10;
    protected $paginationTheme = 'tailwind';
    public $modalVerval = false;
    public $userById;
    public $tanggungan;
    public $totalTanggungan;
    public $totalBayar;
    public $dataBayar;
    public $nominal;
    public $santri_id;
    public $tgl_bayar;
    public $via;

    public function render()
    {
        return view('livewire.registrasi-lama', [
            'datas' =>
            Santri::query()
                ->select([
                    'santris.id_santri',
                    'santris.nama',
                    'santris.lembaga',
                    DB::raw('COALESCE(t.total_tanggungan, 0) as total_tanggungan'),
                    DB::raw('COALESCE(r.total_bayar, 0) as total_bayar'),
                    DB::raw('COALESCE(t.total_tanggungan, 0) - COALESCE(r.total_bayar, 0) as sisa'),
                ])
                // SUBQUERY TOTAL TANGGUNGAN
                ->leftJoin(DB::raw("
                    (
                        SELECT
                            gelombang,
                            jkl,
                            ket,
                            SUM(nominal) AS total_tanggungan
                        FROM tanggungans
                        GROUP BY gelombang, jkl, ket
                    ) t
                "), function ($join) {
                    $join->on('t.gelombang', '=', 'santris.gel')
                        ->on('t.jkl', '=', 'santris.jkl')
                        ->on('t.ket', '=', 'santris.ket');
                })

                // SUBQUERY TOTAL BAYAR
                ->leftJoin(DB::raw("
                    (
                        SELECT
                            id_santri,
                            SUM(nominal) AS total_bayar
                        FROM registrasis
                        GROUP BY id_santri
                    ) r
                "), 'r.id_santri', '=', 'santris.id_santri')

                ->where('santris.ket', 'lama')
                ->when($this->search, function ($query) {
                    $query->where(function ($q) {
                        $q->where('santris.nama', 'ILIKE', "%{$this->search}%")
                            ->orWhere('santris.desa', 'ILIKE', "%{$this->search}%")
                            ->orWhere('santris.kec', 'ILIKE', "%{$this->search}%")
                            ->orWhere('santris.kab', 'ILIKE', "%{$this->search}%")
                            ->orWhere('santris.lembaga', 'ILIKE', "%{$this->search}%");
                    });
                })
                ->paginate($this->paginate)->onEachSide(0)
        ]);
    }

    public function detail($id)
    {
        $this->modalVerval = true;
        $userById = Santri::find($id);
        $this->userById = $userById;
        $this->tanggungan = Tanggungan::where('gelombang', $userById->gel)
            ->where('jkl', $userById->jkl)
            ->where('ket', $userById->ket)
            ->get();
        $this->totalTanggungan = Tanggungan::where('gelombang', $userById->gel)
            ->where('jkl', $userById->jkl)
            ->where('ket', $userById->ket)
            ->sum('nominal');
        $this->totalBayar = Registrasi::where('id_santri', $userById->id_santri)->sum('nominal');
        $this->dataBayar = Registrasi::where('id_santri', $userById->id_santri)->get();
    }

    public function delBayar($id)
    {
        Registrasi::find($id)->delete();
        $this->dispatch(
            'swal',
            title: 'Berhasil',
            text: 'Data pembayaran berhasil dihapus',
            icon: 'success'
        );
        $this->detail($this->userById->id_santri);
    }

    public function simpanPembayaran()
    {
        $this->validate([
            'nominal' => 'required|numeric|min:1000',
            'tgl_bayar' => 'required',
            'via' => 'required',
        ], [
            'nominal.required' => 'Nominal pembayaran wajib diisi',
            'nominal.numeric' => 'Nominal pembayaran harus berupa angka',
            'nominal.min' => 'Nominal pembayaran minimal 1000',
        ]);

        Registrasi::create([
            'id_santri' => $this->userById->id_santri,
            'nominal' => $this->nominal,
            'tgl_bayar' => $this->tgl_bayar,
            'via' => $this->via,
            'kasir' => auth()->user()->name,
        ]);
        $this->dispatch(
            'swal',
            title: 'Berhasil',
            text: 'Data pembayaran berhasil disimpan',
            icon: 'success'
        );
        $this->detail($this->userById->id_santri);
        $this->reset(['nominal', 'tgl_bayar', 'via']);
    }

    public function placeholder()
    {
        return <<<'HTML'
        <div>
            <!-- Loading spinner... -->
           <div class="flex flex-col items-center space-y-4 mt-10">
                        <svg class="animate-spin h-12 w-12 text-secondary-blue mt-1/2" viewBox="0 0 50 50">
                            <circle cx="25" cy="25" r="20" fill="none" stroke="currentColor"
                                stroke-width="4" stroke-linecap="round" stroke-dasharray="31.4 31.4" opacity="0.25" />
                            <circle cx="25" cy="25" r="20" fill="none" stroke="currentColor"
                                stroke-width="4" stroke-linecap="round" stroke-dasharray="31.4 94.2" />
                        </svg>
                    </div>
        </div>
        HTML;
    }
    public function updatedSearch()
    {
        $this->resetPage();
    }
}
