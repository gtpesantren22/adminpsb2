<div>
    <!-- Master Data Page -->
    <div class="page-content  relative min-h-[300px]">
        <!-- LOADING OVERLAY -->
        {{-- <div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4 md:mb-0">Master Data Tanggungan</h2>
            <button
                class="bg-gradient-to-r from-primary-blue to-secondary-blue text-white font-medium py-2.5 px-5 rounded-lg hover:from-primary-blue hover:to-primary-blue focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-blue shadow-md transition duration-150 flex items-center"
                data-bs-toggle="modal" data-bs-target="#addDataModal">
                <i class="fas fa-plus mr-2"></i>Tambah Data
            </button>
        </div> --}}

        <div class="bg-white rounded-xl shadow-md overflow-hidden shadow-hover">
            <div class="px-6 py-4 border-b border-gray-200 flex flex-col md:flex-row md:items-center justify-between">
                {{-- <div class="mb-4 md:mb-0">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" wire:model.debounce.500ms="search"
                            class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-secondary-blue focus:border-transparent"
                            placeholder="Cari data...">
                    </div>
                </div>
                <div class="flex space-x-3">
                    <select wire:model="perPage"
                        class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-secondary-blue">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <button
                        class="border border-gray-300 rounded-lg px-4 py-2 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-secondary-blue transition duration-150">
                        <i class="fas fa-filter mr-2"></i>Filter
                    </button>
                </div> --}}
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#
                            </th>
                            {{-- <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                NIS</th> --}}
                            <th scope="col" wire:click="sort('nama')"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nama</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Alamat</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Gel</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Lembaga Tujuan</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($rows as $row)
                            @if ($row['dok_transfer'] != null)
                                <tr class="hover:bg-gray-100">
                                    <td class="px-6 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ ($page - 1) * $perPage + $loop->iteration }}</td>
                                    <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-500">{{ $row['nama'] }}
                                    </td>
                                    <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-500">
                                        @if ($row['wilayah'] != null)
                                            {{ $row['wilayah']['nama'] }} -
                                            {{ $row['wilayah']['parrent_recursive']['nama'] }} -
                                            {{ $row['wilayah']['parrent_recursive']['parrent_recursive']['nama'] }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-500">
                                        {{ $row['gelombang'] }}
                                    </td>
                                    <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-500">
                                        {{ $row['lembaga']['nama'] }}</td>
                                    <td>
                                        @if ($row['is_santri'])
                                            <span class="text-green-600 font-semibold">Terverifikasi</span>
                                        @else
                                            <span class="text-red-600 font-semibold">Belum</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-3 whitespace-nowrap text-sm font-medium">
                                        <button wire:click.prevent="detail('{{ $row['nik'] }}')"
                                            wire:loading.attr="disabled"
                                            class="inline-flex items-center gap-1.5
                                                bg-blue-500 text-white
                                                px-2 py-1
                                                rounded-md text-sm
                                                hover:bg-blue-600
                                                disabled:opacity-70">

                                            <!-- SPINNER -->
                                            <svg wire:loading wire:target="detail('{{ $row['nik'] }}')"
                                                class="w-4 h-4 animate-spin text-white" viewBox="0 0 24 24"
                                                fill="none">

                                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                                    stroke="currentColor" stroke-width="4" />

                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z" />
                                            </svg>

                                            <!-- ICON -->
                                            <i wire:loading.remove wire:target="detail('{{ $row['nik'] }}')"
                                                class="fas fa-edit text-xs">
                                            </i>

                                            <!-- TEXT -->
                                            <span class="leading-none">Verifikasi</span>
                                        </button>
                                    </td>

                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="10" class="p-4 text-center text-gray-500">
                                    Data tidak ditemukan
                                </td>
                            </tr>
                        @endforelse


                    </tbody>
                </table>
                <div wire:loading wire:target="search,paginate"
                    class="absolute inset-0 bg-white/80 backdrop-blur-sm flex items-center justify-center z-10">

                    <div class="flex flex-col items-center space-y-4 mt-10">
                        <svg class="animate-spin h-12 w-12 text-secondary-blue mt-1/2" viewBox="0 0 50 50">
                            <circle cx="25" cy="25" r="20" fill="none" stroke="currentColor"
                                stroke-width="4" stroke-linecap="round" stroke-dasharray="31.4 31.4" opacity="0.25" />
                            <circle cx="25" cy="25" r="20" fill="none" stroke="currentColor"
                                stroke-width="4" stroke-linecap="round" stroke-dasharray="31.4 94.2" />
                        </svg>

                        <span class="text-sm text-gray-600">
                            Mencari data...
                        </span>
                    </div>

                </div>
            </div>


            {{-- <div class="px-6 py-4 border-t border-gray-200 flex flex-col md:flex-row md:items-center justify-between">
                <div class="text-sm text-gray-500 mb-4 md:mb-0">
                    Halaman {{ $page }} / {{ $this->lastPage() }}
                </div>
                <nav class="flex space-x-2">
                    <button wire:click="prevPage" @disabled($page <= 1)
                        class="px-3 py-1 border border-gray-300 rounded-lg text-sm hover:bg-gray-50">Previous</button>

                    <button wire:click="nextPage"
                        class="px-3 py-1 border border-gray-300 rounded-lg text-sm hover:bg-gray-50"
                        @disabled($page >= $this->lastPage())>Next</button>
                </nav>
            </div> --}}
            <!-- Pagination -->
            {{-- <div class="flex justify-between items-center text-sm">
                <button wire:click="prevPage" class="px-3 py-1 border rounded" @disabled($page <= 1)>
                    Prev
                </button>

                <span>
                    Halaman {{ $page }} / {{ $this->lastPage() }}
                </span>

                <button wire:click="nextPage" class="px-3 py-1 border rounded" @disabled($page >= $this->lastPage())>
                    Next
                </button>
            </div> --}}
        </div>
    </div>
    @if ($modalVerval)
        @php
            $dok = $userById['dok_transfer'] ?? null;
            $path = $dok['path'] ?? null;
            $extension = strtolower($dok['extension'] ?? '');
        @endphp
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">

            <!-- MODAL CONTAINER -->
            <div class="bg-white rounded-xl shadow-xl w-full max-w-6xl mx-4">

                <!-- HEADER -->
                <div class="flex items-center justify-between px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-circle-check text-green-600"></i>
                        Verifikasi Pendaftaran
                    </h3>

                    <button wire:click="$set('modalVerval', false)" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>

                <!-- BODY -->
                <div class="px-6 py-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- KIRI: STRUK -->
                        <div class="border rounded-lg p-4 bg-gray-50">
                            {{-- <p class="text-sm font-semibold text-gray-700 mb-3">
                                Bukti Transfer
                            </p> --}}

                            <div class="flex items-center justify-center">
                                @if ($path)
                                    @if ($extension === 'pdf')
                                        <iframe src="https://data.ppdwk.com/storage/berkas-psb/{{ $path }}"
                                            class="w-full h-[460px] rounded-md shadow border"
                                            style="border:none;"></iframe>
                                    @else
                                        <img src="https://data.ppdwk.com/storage/berkas-psb/{{ $path }}"
                                            alt="Struk Transfer"
                                            class="max-h-[450px] w-full rounded-md shadow object-contain">
                                    @endif
                                @else
                                    <p class="text-sm text-gray-500 italic">
                                        Dokumen transfer belum tersedia
                                    </p>
                                @endif
                            </div>
                        </div>

                        <!-- KANAN: DETAIL -->
                        <div class="border rounded-lg border-gray-200 p-4">
                            <p class="text-sm font-semibold text-gray-700 mb-4">
                                Detail Pendaftar
                            </p>

                            @if ($userById)
                                <div class="space-y-3 text-sm">

                                    @php
                                        $items = [
                                            'Nama' => $userById['nama'] ?? '-',
                                            'Tempat, Tgl Lahir' =>
                                                ($userById['tempat_lahir'] ?? '-') .
                                                ', ' .
                                                ($userById['tanggal_lahir_indo'] ?? '-'),
                                            'Jenis Kelamin' =>
                                                $userById['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan',
                                            'Alamat' =>
                                                $row['wilayah']['nama'] .
                                                    ' - ' .
                                                    $row['wilayah']['parrent_recursive']['nama'] .
                                                    ' - ' .
                                                    $row['wilayah']['parrent_recursive']['parrent_recursive']['nama'] ??
                                                '-',
                                            'Nama Bapak' => $userById['nama_ayah'] ?? '-',
                                            'Nama Ibu' => $userById['nama_ibu'] ?? '-',
                                            'Lembaga' => $userById['lembaga']['nama'] ?? '-',
                                            'No HP' => $userById['whatsapp'] ?? '-',
                                            'Gelombang' => $userById['gelombang'] ?? '-',
                                        ];
                                    @endphp

                                    @foreach ($items as $label => $value)
                                        <div class="flex justify-between gap-4">
                                            <span class="text-gray-500">{{ $label }}</span>
                                            <span class="font-medium text-gray-900 text-right">
                                                {{ $value }}
                                            </span>
                                        </div>
                                    @endforeach

                                    <div class="pt-3 border-t">
                                        <span class="text-gray-500">Status</span>
                                        @if ($cekSantri)
                                            <span
                                                class="ml-2 inline-flex items-center rounded-full bg-green-200 px-2 py-1 text-xs font-medium text-green-700">
                                                Terverifikasi
                                            </span>
                                        @else
                                            <span
                                                class="ml-2 inline-flex items-center rounded-full bg-yellow-200 px-2 py-1 text-xs font-medium text-yellow-700">
                                                Menunggu Verifikasi
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <p class="text-sm text-gray-500">Data tidak ditemukan</p>
                            @endif
                        </div>

                    </div>
                </div>

                <!-- FOOTER -->
                <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 border-t">

                    <button wire:click="$set('modalVerval', false)"
                        class="rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-700 border hover:bg-gray-100">
                        Tutup
                    </button>

                    @if (!$cekSantri)
                        <button wire:click.prevent="approve('{{ $userById['nik'] }}')" wire:loading.attr="disabled"
                            wire:confirm="Are you sure you want to delete this post?"
                            class="rounded-md bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:bg-green-500">
                            <!-- SPINNER -->
                            <svg wire:loading wire:target="approve('{{ $userById['nik'] }}')"
                                class="w-5 h-5 animate-spin text-white" viewBox="0 0 24 24" fill="none">

                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4" />

                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z" />
                            </svg>

                            Verifikasi
                        </button>
                    @endif
                </div>

            </div>
        </div>
    @endif


</div>
