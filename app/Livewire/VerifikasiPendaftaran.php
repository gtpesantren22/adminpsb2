<?php

namespace App\Livewire;

use App\Models\Pendaftaran;
use App\Models\Santri;
use App\Services\ApiService;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Livewire\Component;
use SweetAlert2\Laravel\Traits\WithSweetAlert;

class VerifikasiPendaftaran extends Component
{
    use WithSweetAlert;

    public string $search = '';
    public string $sortby = 'created_at';
    public string $sortbydesc = 'DESC';
    public int $perPage = 500;
    public int $page = 1;

    public int $total = 0;
    public array $rows = [];
    public bool $loading = false;

    public $userById = null;
    public $modalVerval = false;
    public $cekSantri = null;

    protected $queryString = [
        'page' => ['except' => 1],
        'search' => ['except' => ''],
    ];

    public function mount()
    {
        $this->loadData();
        // dd($this->loadData());
    }

    public function updatedSearch()
    {
        $this->page = 1;
        $this->loadData();
    }

    // public function updatedPerPage()
    // {
    //     $this->page = 1;
    //     $this->loadData();
    // }

    public function loadData()
    {
        $this->loading = true;
        $nikSantri = Santri::pluck('id_santri')->flip();
        $response = ApiService::datatables([
            'data' => 'pendaftar',
            'page' => $this->page,
            'per_page' => $this->perPage,
            'q' => $this->search,
            'sortby' => $this->sortby,
            'sortbydesc' => $this->sortbydesc,
            'status' => 1,
        ]);

        $this->rows = collect($response['data']['data'] ?? [])
            ->whereNotNull('dok_transfer')   // ⬅️ FILTER DI SINI
            ->where('lembaga.nama', '!=', 'RA DARUL LUGHAH WAL KAROMAH')
            ->where('lembaga.nama', '!=', 'MI DARUL LUGHAH WAL KAROMAH')
            ->map(function ($row) use ($nikSantri) {
                $row['is_santri'] = isset($nikSantri[$row['peserta_didik_id'] ?? null]);
                return $row;
            })
            ->values()                       // ⬅️ RESET INDEX
            ->toArray();

        $this->total = count($this->rows);

        $this->loading = false;
    }

    public function detail($nik)
    {
        $this->modalVerval = true;
        $response = ApiService::datatables([
            'data' => 'pendaftar',
            'page' => 1,
            'per_page' => 1,
            'q' => $nik,
            'sortby' => $this->sortby,
            'sortbydesc' => $this->sortbydesc,
            'status' => 1,
        ]);

        // dd($response);
        $this->userById = collect($response['data']['data'] ?? [])->first();
        $this->cekSantri = Santri::where('nik', $nik)->exists();
    }

    public function approve($nik)
    {
        $response = ApiService::datatables([
            'data' => 'pendaftar',
            'page' => 1,
            'per_page' => 1,
            'q' => $nik,
            'sortby' => $this->sortby,
            'sortbydesc' => $this->sortbydesc,
            'status' => 1,
        ]);

        // dd($response);
        $santri = (object) collect($response['data']['data'] ?? [])->first();
        $dtSave = [
            'id_santri' => $santri->peserta_didik_id,

            // IDENTITAS
            'nis' => $santri->nis,
            'nisn' => $santri->nisn,
            'nik' => $santri->nik,
            'no_kk' => $santri->no_kk,
            'nama' => $santri->nama,
            'tempat' => $santri->tempat_lahir,
            'tanggal' => $santri->tanggal_lahir,
            'jkl' => $santri->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan',
            'agama' => '',
            'lembaga' => $santri->lembaga['nama'],

            // ALAMAT
            'jln' => $santri->alamat,
            'rt' => '00',
            'rw' => '00',
            'desa' => $santri->wilayah['nama'],
            'kec' => $santri->wilayah['parrent_recursive']['nama'],
            'kab' => $santri->wilayah['parrent_recursive']['parrent_recursive']['nama'],
            'prov' => $santri->wilayah['parrent_recursive']['parrent_recursive']['parrent_recursive']['nama'],
            'kd_pos' => 0,

            // AYAH
            'bapak' => $santri->nama_ayah,
            'a_nik' => $santri->nik_ayah,
            'a_tempat' => '',
            'a_tanggal' => '',
            'a_pkj' => '',
            'a_pend' => '',
            'a_hasil' => '',
            'a_stts' => '',

            // IBU
            'ibu' => $santri->nama_ibu,
            'i_nik' => $santri->nik_ibu,
            'i_tempat' => '',
            'i_tanggal' => '',
            'i_pkj' => '',
            'i_pend' => '',
            'i_hasil' => '',
            'i_stts' => '',

            // KONTAK & AKUN
            'hp' => $santri->whatsapp,
            'username' => '',
            'password' => '', // otomatis di-hash oleh mutator

            // STATUS
            'stts' => 'Terverifikasi',
            'gel' => $santri->gelombang,
            'jalur' => $santri->jalur_str,
            'waktu_daftar' => Carbon::createFromFormat(
                'd/m/Y H:i:s',
                $santri->created_at
            )->format('Y-m-d H:i:s'),

            // DATA TAMBAHAN
            'anak_ke' => $santri->anak_ke,
            'jml_sdr' => $santri->jml_sdr,
            'jenis' => '',
            'asal' => $santri->sekolah_asal,
            'npsn' => 0,
            'a_asal' => $santri->alamat_sekolah_asal,
            'ket' => 'baru',
            'tinggal' => '',
        ];
        Santri::create($dtSave);

        Pendaftaran::create([
            'id_bayar' => (string) Str::uuid(),
            'id_santri' => $santri->peserta_didik_id,
            'nis'      => $santri->nis,
            'nominal'  => $this->getNominalByGelombang($santri->gelombang),
            'tgl_bayar' => now(),
            'via'      => 'Transfer',
            'kasir'    => auth()->user()->name ?? 'Admin',
        ]);


        // same as `Swal.fire()` in JS, same options: https://sweetalert2.github.io/#configuration
        $this->dispatch(
            'swal',
            title: 'Success!!',
            text: 'Verifikasi pendaftaran berhasil',
            icon: 'success',
            confirmButtonText: 'Ok'
        );
        $this->modalVerval = false;
        $this->loadData();
    }

    private function getNominalByGelombang($gelombang): int
    {
        return match ((int) $gelombang) {
            1 => 80000,
            2 => 130000,
            3 => 180000,
            default => 0,
        };
    }

    // public function sort(string $field)
    // {
    //     if ($this->sortby === $field) {
    //         $this->sortbydesc = $this->sortbydesc === 'DESC' ? 'ASC' : 'DESC';
    //     } else {
    //         $this->sortby = $field;
    //         $this->sortbydesc = 'DESC';
    //     }

    //     $this->loadData();
    // }

    // public function nextPage()
    // {
    //     if ($this->page < $this->lastPage()) {
    //         $this->page++;
    //         $this->loadData();
    //     }
    // }

    // public function prevPage()
    // {
    //     if ($this->page > 1) {
    //         $this->page--;
    //         $this->loadData();
    //     }
    // }

    // public function lastPage(): int
    // {
    //     return (int) ceil($this->total / $this->perPage);
    // }

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

    public function render()
    {
        return view('livewire.verifikasi-pendaftaran');
    }
}
