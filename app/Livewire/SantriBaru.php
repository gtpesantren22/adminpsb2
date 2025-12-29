<?php

namespace App\Livewire;

use App\Models\Santri;
use Livewire\Component;
use Livewire\WithPagination;

class SantriBaru extends Component
{
    use WithPagination;
    public $paginate = 10;
    public $search = '';
    protected $paginationTheme = 'tailwind';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $datas = Santri::where('ket', 'baru')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nama', 'ILIKE', "%{$this->search}%")
                        ->orWhere('desa', 'ILIKE', "%{$this->search}%")
                        ->orWhere('kec', 'ILIKE', "%{$this->search}%")
                        ->orWhere('kab', 'ILIKE', "%{$this->search}%")
                        ->orWhere('lembaga', 'ILIKE', "%{$this->search}%");
                });
            })
            ->paginate($this->paginate);

        return view('livewire.santri-baru', compact('datas'));
    }
}
