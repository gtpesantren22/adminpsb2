<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Seragam;

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
                'seragams.created_at'
            )
            ->when($this->search, function ($query) {
                $query->where('santris.nama', 'ilike', '%' . $this->search . '%');
            })
            ->when($this->lembaga, function ($query) {
                $query->where('santris.lembaga', 'ilike', $this->lembaga);
            })
            ->orderBy('seragams.created_at', 'asc')
            ->paginate(50); // Using 50 to see more data

        return view('livewire.rekap-seragam', [
            'datas' => $datas
        ]);
    }
}
