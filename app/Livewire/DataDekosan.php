<?php

namespace App\Livewire;

use App\Models\Dekosan;
use App\Models\Santri;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class DataDekosan extends Component
{
    use WithPagination;

    public $search = '';
    public $paginate = 10;
    protected $paginationTheme = 'tailwind';

    // Modals
    public $modalCreate = false;
    public $modalEdit = false;

    // Create Form Fields
    public $id_santri;
    public $nominal;
    public $tgl_bayar;
    public $searchSantri = '';
    public $selectedSantri = null;
    public array $santriList = [];

    // Edit Form Fields
    public $editingId = null;
    public $edit_id_santri;
    public $edit_nominal;
    public $edit_tgl_bayar;
    public $edit_tempat_kos;
    public $editingSantri = null;

    // Places list
    public array $tempatKosPutra = [
        'Ny. Jamilatul Lailiyah',
        'K. M. Zaini Bin Ali Wafa',
        'Ny. Farihah',
        'K. Abdul Mukti',
    ];

    public array $tempatKosPutri = [
        'Ny. Zahrotul Muawanah',
        'Ny. Saadah Abadiyah',
        'Ny. Mamjudah',
        'Ny. Naili Zulfa',
        'Ny. Lathifah Rois',
        'Ny. Ummi Kulsum',
    ];

    public function mount()
    {
        $this->resetForm();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedSearchSantri()
    {
        if (strlen($this->searchSantri) >= 2) {
            $this->santriList = Santri::where('nama', 'ILIKE', "%{$this->searchSantri}%")
                ->orWhere('id_santri', 'ILIKE', "%{$this->searchSantri}%")
                ->orWhere('nis', 'ILIKE', "%{$this->searchSantri}%")
                ->limit(5)
                ->get()
                ->toArray();
        } else {
            $this->santriList = [];
        }
    }

    public function selectSantri($id)
    {
        $santri = Santri::findOrFail($id);
        $this->selectedSantri = $santri->toArray();
        $this->id_santri = $santri->id_santri;
        $this->searchSantri = '';
        $this->santriList = [];
    }

    public function resetForm()
    {
        $this->id_santri = null;
        $this->nominal = null;
        $this->tgl_bayar = date('Y-m-d');
        $this->searchSantri = '';
        $this->selectedSantri = null;
        $this->santriList = [];
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->modalCreate = true;
    }

    public function store()
    {
        $this->validate([
            'id_santri' => 'required|exists:santris,id_santri',
            'nominal' => 'required|numeric|min:0',
            'tgl_bayar' => 'required|date',
        ], [
            'id_santri.required' => 'Silakan pilih santri terlebih dahulu',
            'nominal.required' => 'Nominal wajib diisi',
            'nominal.numeric' => 'Nominal harus berupa angka',
            'nominal.min' => 'Nominal tidak boleh negatif',
            'tgl_bayar.required' => 'Tanggal bayar wajib diisi',
        ]);

        $santri = Santri::findOrFail($this->id_santri);
        $gender = $santri->jkl; // 'Laki-laki' or 'Perempuan'
        $candidates = ($gender === 'Laki-laki') ? $this->tempatKosPutra : $this->tempatKosPutri;

        // Balance logic: count occurrences in dekosans table
        $counts = Dekosan::select('tempat_kos', DB::raw('count(*) as total'))
            ->whereIn('tempat_kos', $candidates)
            ->groupBy('tempat_kos')
            ->pluck('total', 'tempat_kos')
            ->toArray();

        $candidatesWithCount = [];
        foreach ($candidates as $name) {
            $candidatesWithCount[$name] = $counts[$name] ?? 0;
        }

        $minCount = min($candidatesWithCount);
        $eligibleCandidates = array_keys(array_filter($candidatesWithCount, function ($count) use ($minCount) {
            return $count === $minCount;
        }));

        // Pick one at random from the tied minimums
        $selectedPlace = $eligibleCandidates[array_rand($eligibleCandidates)];

        $dekosan = Dekosan::create([
            'id_santri' => $this->id_santri,
            'nominal' => $this->nominal,
            'tgl_bayar' => $this->tgl_bayar,
            'tempat_kos' => $selectedPlace,
            'kasir' => auth()->user()->name ?? 'Kasir',
        ]);

        $this->dispatch('swal', title: 'Berhasil', text: 'Data pembayaran dekosan berhasil disimpan', icon: 'success');
        $this->modalCreate = false;
        
        // Auto print receipt after saving
        $this->printReceipt($dekosan->id);
    }

    public function edit($id)
    {
        $dekosan = Dekosan::with('santri')->findOrFail($id);
        $this->editingId = $dekosan->id;
        $this->edit_id_santri = $dekosan->id_santri;
        $this->edit_nominal = $dekosan->nominal;
        $this->edit_tgl_bayar = $dekosan->tgl_bayar->format('Y-m-d');
        $this->edit_tempat_kos = $dekosan->tempat_kos;
        $this->editingSantri = $dekosan->santri->toArray();
        $this->modalEdit = true;
    }

    public function update()
    {
        $this->validate([
            'edit_nominal' => 'required|numeric|min:0',
            'edit_tgl_bayar' => 'required|date',
            'edit_tempat_kos' => 'required|string',
        ], [
            'edit_nominal.required' => 'Nominal wajib diisi',
            'edit_nominal.numeric' => 'Nominal harus berupa angka',
            'edit_nominal.min' => 'Nominal tidak boleh negatif',
            'edit_tgl_bayar.required' => 'Tanggal bayar wajib diisi',
            'edit_tempat_kos.required' => 'Tempat kos wajib diisi',
        ]);

        $dekosan = Dekosan::findOrFail($this->editingId);
        $dekosan->update([
            'nominal' => $this->edit_nominal,
            'tgl_bayar' => $this->edit_tgl_bayar,
            'tempat_kos' => $this->edit_tempat_kos,
        ]);

        $this->dispatch('swal', title: 'Berhasil', text: 'Data pembayaran dekosan berhasil diperbarui', icon: 'success');
        $this->modalEdit = false;
    }

    public function delete($id)
    {
        Dekosan::findOrFail($id)->delete();
        $this->dispatch('swal', title: 'Berhasil', text: 'Data pembayaran dekosan berhasil dihapus', icon: 'success');
    }

    public function printReceipt($id)
    {
        $dekosan = Dekosan::with('santri')->findOrFail($id);
        $this->dispatch('print-dekosan', [
            'id' => $dekosan->id,
            'nama' => $dekosan->santri->nama,
            'lembaga' => $dekosan->santri->lembaga ?? '-',
            'jkl' => $dekosan->santri->jkl,
            'tempat_kos' => $dekosan->tempat_kos,
            'tanggal' => $dekosan->tgl_bayar->format('d/m/Y'),
            'nominal' => (int) $dekosan->nominal,
            'kasir' => $dekosan->kasir,
        ]);
    }

    public function render()
    {
        $query = Dekosan::with('santri');

        if ($this->search) {
            $query->where(function ($q) {
                $q->whereHas('santri', function ($sq) {
                    $sq->where('nama', 'ILIKE', "%{$this->search}%")
                       ->orWhere('id_santri', 'ILIKE', "%{$this->search}%");
                })->orWhere('tempat_kos', 'ILIKE', "%{$this->search}%");
            });
        }

        $dekosans = $query->latest()->paginate($this->paginate);

        // Fetch counts for all places
        $counts = Dekosan::select('tempat_kos', DB::raw('count(*) as total'))
            ->groupBy('tempat_kos')
            ->pluck('total', 'tempat_kos')
            ->toArray();

        $rekapPutra = [];
        foreach ($this->tempatKosPutra as $kos) {
            $rekapPutra[$kos] = $counts[$kos] ?? 0;
        }

        $rekapPutri = [];
        foreach ($this->tempatKosPutri as $kos) {
            $rekapPutri[$kos] = $counts[$kos] ?? 0;
        }

        $totalDana = Dekosan::sum('nominal');

        return view('livewire.data-dekosan', [
            'dekosans' => $dekosans,
            'rekapPutra' => $rekapPutra,
            'rekapPutri' => $rekapPutri,
            'totalDana' => $totalDana,
        ]);
    }
}
