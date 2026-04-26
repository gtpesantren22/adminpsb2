<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Seragam;
use Illuminate\Support\Facades\DB;

#[Layout('components.layouts.public-wide')]
class RekapSeragam extends Component
{
    use WithPagination;

    public $search = '';
    public $lembaga = '';

    // List of Lembaga for filter dropdown (can be dynamic or static)
    public $listLembaga = ['MTs', 'SMP', 'MA', 'SMA', 'SMK']; // Adjust to actual values used

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingLembaga()
    {
        $this->resetPage();
    }

    public function render()
    {
        $datas = Seragam::query()
            ->join('santris', 'seragams.id_santri', '=', 'santris.id_santri')
            ->select(
                'seragams.id',
                'santris.nama',
                'santris.jkl',
                'santris.lembaga',
                'seragams.atasan',
                'seragams.bawahan',
                'seragams.songkok',
                'seragams.created_at',
                DB::raw('COALESCE(t.total_tanggungan, 0) as total_tanggungan'),
                DB::raw('COALESCE(r.total_bayar, 0) as total_bayar')
            )
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
            ->leftJoin(DB::raw("
                (
                    SELECT
                        id_santri,
                        SUM(nominal) AS total_bayar
                    FROM registrasis
                    GROUP BY id_santri
                ) r
            "), 'r.id_santri', '=', 'santris.id_santri')
            ->when($this->search, function ($query) {
                $query->where('santris.nama', 'ilike', '%' . $this->search . '%');
            })
            ->when($this->lembaga, function ($query) {
                $query->where('santris.lembaga', 'ilike', $this->lembaga . '%');
            })
            ->orderBy('seragams.created_at', 'asc')
            ->paginate(50); // Using 50 to see more data

        return view('livewire.rekap-seragam', [
            'datas' => $datas
        ]);
    }
}
