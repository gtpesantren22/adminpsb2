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

    public function render()
    {
        return view('livewire.pendaftaran-baru', [
            'datas' => Pendaftaran::with('santri')
                ->whereHas('santri', function ($q) {
                    $q->where('nama', 'ILIKE', '%' . $this->search . '%');
                    $q->where('lembaga', 'ILIKE', '%' . $this->search . '%');
                })
                ->paginate($this->paginate)->onEachSide(0)
        ]);
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
