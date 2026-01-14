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
            ->paginate($this->paginate)->onEachSide(0);

        return view('livewire.santri-baru', compact('datas'));
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
}
