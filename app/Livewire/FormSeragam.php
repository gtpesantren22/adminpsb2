<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Santri;
use App\Models\Atasan;
use App\Models\Bawahan;
use App\Models\Seragam;

#[Layout('components.layouts.public')]
class FormSeragam extends Component
{
    public $santri;

    // Form Inputs
    public $atasan_id = '';
    public $bawahan_id = '';
    public $songkok = '0';

    // Collections
    public $listAtasan = [];
    public $listBawahan = [];

    // Details (for display when selected)
    public $detailAtasan = null;
    public $detailBawahan = null;

    // State
    public $isSubmitted = false;
    public $submittedData = null;

    public function mount($id)
    {
        $this->santri = Santri::findOrFail($id);

        // Cek apakah sudah pernah isi
        $existing = Seragam::where('id_santri', $this->santri->id_santri)->first();
        if ($existing) {
            $this->isSubmitted = true;
            $this->submittedData = $existing;
            return;
        }

        // Tentukan Jenjang (Asumsi MTs/SMP = SLTP, MA/SMA/SMK = SLTA)
        $lembaga = strtoupper($this->santri->lembaga);
        $jenjang = str_contains($lembaga, 'MTs') || str_contains($lembaga, 'SMP') ? 'SLTP' : 'SLTA';

        // Jenis Kelamin ('L' atau 'P'. Jika 'Laki-laki' kita sesuaikan)
        // Kita gunakan str_starts_with untuk jaga-jaga kalau 'L' / 'Laki-laki'
        $jklPrefix = strtoupper(substr($this->santri->jkl, 0, 1)); // 'L' atau 'P'

        // Load pilihan ukuran
        // Asumsi data di atasans jkl diisi 'L' atau 'P' (karena di santris mungkin 'L' / 'P' atau 'Laki-laki' / 'Perempuan')
        // Akan lebih aman pakai like
        $this->listAtasan = Atasan::where('jenjang', $jenjang)
            ->where('jkl', 'like', $jklPrefix . '%')
            ->get();

        $this->listBawahan = Bawahan::where('jenjang', $jenjang)
            ->where('jkl', 'like', $jklPrefix . '%')
            ->get();
    }

    public function updatedAtasanId($value)
    {
        if ($value) {
            $this->detailAtasan = Atasan::find($value);
        } else {
            $this->detailAtasan = null;
        }
    }

    public function updatedAtawanId($value) // just in case for typo
    {
        $this->updatedAtasanId($value);
    }

    public function updatedBawahanId($value)
    {
        if ($value) {
            $this->detailBawahan = Bawahan::find($value);
        } else {
            $this->detailBawahan = null;
        }
    }

    public function save()
    {
        $this->validate([
            'atasan_id' => 'required',
            'bawahan_id' => 'required',
            'songkok' => strtoupper(substr($this->santri->jkl, 0, 1)) === 'L' ? 'required' : 'nullable'
        ], [
            'atasan_id.required' => 'Mhn pilih ukuran baju atas (baju).',
            'bawahan_id.required' => 'Mhn pilih ukuran baju bawah (celana/rok).',
            'songkok.required' => 'Mhn isi ukuran songkok untuk santri laki-laki.'
        ]);

        $jklPrefix = strtoupper(substr($this->santri->jkl, 0, 1));

        $atasanModel = Atasan::find($this->atasan_id);
        $bawahanModel = Bawahan::find($this->bawahan_id);

        Seragam::create([
            'id_santri' => $this->santri->id_santri,
            'atasan' => $atasanModel ? $atasanModel->ukuran : '-',
            'bawahan' => $bawahanModel ? $bawahanModel->ukuran : '-',
            'songkok' => $jklPrefix === 'L' ? $this->songkok : '0',
        ]);

        $this->isSubmitted = true;

        // Reload saved data
        $this->submittedData = Seragam::where('id_santri', $this->santri->id_santri)->first();

        // Tampilkan notifikasi
        $this->dispatch('swal', [
            'title' => 'Berhasil!',
            'text' => 'Ukuran seragam berhasil disimpan.',
            'icon' => 'success'
        ]);
    }

    public function render()
    {
        return view('livewire.form-seragam');
    }
}
