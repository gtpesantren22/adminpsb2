<?php

namespace App\Livewire;

use App\Models\Pendaftaran;
use Livewire\Component;
use Livewire\WithPagination;

class PendaftaranBaru extends Component
{
    use WithPagination;

    public $search = '';
    public $paginate = 10;
    protected $paginationTheme = 'tailwind';

    // Edit Modal State
    public $modalEdit = false;
    public $editingId = null;
    public $edit_nominal;
    public $edit_tgl_bayar;
    public $edit_via;
    public $editingSantri = null;

    public function render()
    {
        return view('livewire.pendaftaran-baru', [
            'datas' => Pendaftaran::with('santri')
                ->whereHas('santri', function ($q) {
                    $q->where('ket', 'baru');
                    $q->where(function ($sub) {
                        $sub->where('nama', 'ILIKE', '%' . $this->search . '%')
                            ->orWhere('lembaga', 'ILIKE', '%' . $this->search . '%');
                    });
                })
                ->orderBy('tgl_bayar', 'DESC')
                ->orderBy('id_bayar', 'DESC')
                ->paginate($this->paginate)->onEachSide(0)
        ]);
    }

    public function edit($id)
    {
        $pendaftaran = Pendaftaran::with('santri')->findOrFail($id);
        $this->editingId = $pendaftaran->id_bayar;
        $this->edit_nominal = (int) $pendaftaran->nominal;
        $this->edit_tgl_bayar = $pendaftaran->tgl_bayar->format('Y-m-d');
        $this->edit_via = $pendaftaran->via;
        $this->editingSantri = $pendaftaran->santri ? $pendaftaran->santri->toArray() : null;
        $this->modalEdit = true;
    }

    public function update()
    {
        $this->validate([
            'edit_nominal' => 'required|numeric|min:0',
            'edit_tgl_bayar' => 'required|date',
            'edit_via' => 'required|in:Transfer,Cash',
        ], [
            'edit_nominal.required' => 'Nominal wajib diisi',
            'edit_nominal.numeric' => 'Nominal harus berupa angka',
            'edit_nominal.min' => 'Nominal tidak boleh negatif',
            'edit_tgl_bayar.required' => 'Tanggal bayar wajib diisi',
            'edit_via.required' => 'Metode pembayaran wajib diisi',
            'edit_via.in' => 'Metode pembayaran tidak valid',
        ]);

        $pendaftaran = Pendaftaran::findOrFail($this->editingId);
        $pendaftaran->update([
            'nominal' => $this->edit_nominal,
            'tgl_bayar' => $this->edit_tgl_bayar,
            'via' => $this->edit_via,
        ]);

        $this->dispatch('swal', title: 'Berhasil', text: 'Data pembayaran pendaftaran berhasil diperbarui', icon: 'success');
        $this->modalEdit = false;
    }

    public function delete($id)
    {
        Pendaftaran::findOrFail($id)->delete();
        $this->dispatch('swal', title: 'Berhasil', text: 'Data pembayaran pendaftaran berhasil dihapus', icon: 'success');
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
