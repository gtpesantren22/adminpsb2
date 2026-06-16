<?php

namespace App\Livewire;

use App\Models\Santri;
use App\Services\ApiService;
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

    public function sync($id_santri)
    {
        $santri = Santri::find($id_santri);
        if (!$santri) {
            $this->dispatch(
                'swal',
                title: 'Gagal',
                text: 'Data santri tidak ditemukan di database lokal.',
                icon: 'error'
            );
            return;
        }

        // Cari di data sumber menggunakan NIK, id_santri, atau nama
        $query = $santri->nik ?: ($santri->id_santri ?: $santri->nama);
        $response = ApiService::datatables([
            'data' => 'pendaftar',
            'page' => 1,
            'per_page' => 10,
            'q' => $query,
            'sortby' => 'created_at',
            'sortbydesc' => 'DESC',
            'status' => 1,
        ]);

        if (isset($response['error'])) {
            $this->dispatch(
                'swal',
                title: 'Error API',
                text: 'Gagal menghubungi data sumber: ' . $response['error'],
                icon: 'error'
            );
            return;
        }

        $apiData = collect($response['data']['data'] ?? [])
            ->first(function ($item) use ($santri) {
                return ($item['peserta_didik_id'] ?? null) === $santri->id_santri;
            });

        // Backup search jika pencarian awal tidak menghasilkan record yang cocok
        if (!$apiData && $query !== $santri->id_santri) {
            $response = ApiService::datatables([
                'data' => 'pendaftar',
                'page' => 1,
                'per_page' => 10,
                'q' => $santri->id_santri,
                'sortby' => 'created_at',
                'sortbydesc' => 'DESC',
                'status' => 1,
            ]);
            $apiData = collect($response['data']['data'] ?? [])
                ->first(function ($item) use ($santri) {
                    return ($item['peserta_didik_id'] ?? null) === $santri->id_santri;
                });
        }

        if (!$apiData) {
            $this->dispatch(
                'swal',
                title: 'Tidak Ditemukan',
                text: 'Data pendaftar tidak ditemukan di data sumber.',
                icon: 'warning'
            );
            return;
        }

        $santriData = (object) $apiData;

        // Tentukan ket
        $ket = $santriData->pd_lama == null ? 'baru' : 'lama';

        $santri->update([
            // IDENTITAS
            'nis' => $santriData->nis ?? $santri->nis,
            'nisn' => $santriData->nisn ?? $santri->nisn,
            'nik' => $santriData->nik ?? $santri->nik,
            'no_kk' => $santriData->no_kk ?? $santri->no_kk,
            'nama' => $santriData->nama ?? $santri->nama,
            'tempat' => $santriData->tempat_lahir ?? $santri->tempat,
            'tanggal' => $santriData->tanggal_lahir ?? $santri->tanggal,
            'jkl' => isset($santriData->jenis_kelamin) ? ($santriData->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan') : $santri->jkl,
            'lembaga' => $santriData->lembaga['nama'] ?? $santri->lembaga,

            // ALAMAT
            'jln' => $santriData->alamat ?? $santri->jln,
            'desa' => $santriData->wilayah['nama'] ?? $santri->desa,
            'kec' => $santriData->wilayah['parrent_recursive']['nama'] ?? $santri->kec,
            'kab' => $santriData->wilayah['parrent_recursive']['parrent_recursive']['nama'] ?? $santri->kab,
            'prov' => $santriData->wilayah['parrent_recursive']['parrent_recursive']['parrent_recursive']['nama'] ?? $santri->prov,

            // AYAH & IBU
            'bapak' => $santriData->nama_ayah ?? $santri->bapak,
            'a_nik' => $santriData->nik_ayah ?? $santri->a_nik,
            'ibu' => $santriData->nama_ibu ?? $santri->ibu,
            'i_nik' => $santriData->nik_ibu ?? $santri->i_nik,

            // KONTAK
            'hp' => $santriData->whatsapp ?? $santri->hp,

            // STATUS & LAIN-LAIN
            'gel' => $santriData->gelombang ?? $santri->gel,
            'jalur' => $santriData->jalur_str ?? $santri->jalur,
            'anak_ke' => $santriData->anak_ke ?? $santri->anak_ke,
            'jml_sdr' => $santriData->jml_sdr ?? $santri->jml_sdr,
            'asal' => $santriData->sekolah_asal ?? $santri->asal,
            'a_asal' => $santriData->alamat_sekolah_asal ?? $santri->a_asal,
            'ket' => $ket,
        ]);

        $this->dispatch(
            'swal',
            title: 'Berhasil!!',
            text: 'Sinkronisasi data santri berhasil dilakukan.',
            icon: 'success',
            confirmButtonText: 'Ok'
        );
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
