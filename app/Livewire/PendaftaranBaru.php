<?php

namespace App\Livewire;

use App\Models\Pendaftaran;
use Livewire\Component;

class PendaftaranBaru extends Component
{
    public $search = '';
    public $paginate = 10;
    public function render()
    {
        return view('livewire.pendaftaran-baru', [
            'datas' => Pendaftaran::with('santri')
                ->whereHas('santri', function ($q) {
                    $q->where('nama', 'ILIKE', '%' . $this->search . '%');
                    $q->where('lembaga', 'ILIKE', '%' . $this->search . '%');
                })
                ->paginate($this->paginate)
        ]);
    }
}
