<?php

namespace App\Livewire;

use App\Models\Pendaftaran;
use App\Models\Santri;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.dashboard', [
            'jml_santri' => Santri::all()->count(),
            'jml_pendaftaran' => Pendaftaran::all()->count(),
            'nominal_pendaftaran' => Pendaftaran::sum('nominal'),
        ]);
    }
}
