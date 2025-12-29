<?php

namespace App\Livewire;

use App\Models\Santri;
use Livewire\Component;

class SantriLama extends Component
{
    public function render()
    {
        return view('livewire.santri-lama', [
            'datas' => Santri::where('ket', 'lama')->paginate(10)
        ]);
    }
}
