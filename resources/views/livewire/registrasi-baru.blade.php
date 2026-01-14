<div>
    <!-- Master Data Page -->
    <div class="page-content">
        {{-- <div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4 md:mb-0">Master Data Tanggungan</h2>
            <button
                class="bg-gradient-to-r from-primary-blue to-secondary-blue text-white font-medium py-2.5 px-5 rounded-lg hover:from-primary-blue hover:to-primary-blue focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-blue shadow-md transition duration-150 flex items-center"
                data-bs-toggle="modal" data-bs-target="#addDataModal">
                <i class="fas fa-plus mr-2"></i>Tambah Data
            </button>
        </div> --}}

        <div class="bg-white rounded-xl shadow-md shadow-hover">
            <div class="px-6 py-4 border-b border-gray-200 flex flex-col md:flex-row md:items-center justify-between">
                <div class="mb-4 md:mb-0">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" wire:model.live="search"
                            class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-secondary-blue focus:border-transparent"
                            placeholder="Cari data...">
                    </div>
                </div>
                <div class="flex space-x-3">
                    <select wire:model.live="paginate"
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
                </div>
            </div>

            <div class="relative overflow-x-auto ">
                <!-- TABLE -->
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                            <th wire:click="sort('nama')"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer">
                                Nama
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lembaga</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggungan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lunas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($datas as $row)
                            @php
                                if ($row->total_bayar < $row->total_tanggungan) {
                                    $status = 'Belum';
                                    $bgColor = 'red';
                                } elseif ($row->total_bayar == $row->total_tanggungan) {
                                    $status = 'Lunas';
                                    $bgColor = 'green';
                                } else {
                                    $status = 'Lebih';
                                    $bgColor = 'yellow';
                                }
                            @endphp
                            <tr>
                                <td class="px-6 py-3">
                                    {{ $datas->firstItem() + $loop->index }}
                                </td>
                                <td class="px-6 py-3 text-gray-700">{{ $row->nama ?? '-' }}</td>
                                <td class="px-6 py-3">{{ $row->lembaga ?? '-' }}</td>
                                <td class="px-6 py-3">{{ number_format($row->total_tanggungan) }}</td>
                                <td class="px-6 py-3">{{ number_format($row->total_bayar) }}</td>
                                <td class="px-6 py-3">
                                    <span
                                        class="bg-{{ $bgColor }}-100 text-{{ $bgColor }}-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-sm ">{{ $status }}</span>
                                    <span>
                                </td>

                                <td class="px-6 py-3">
                                    <button wire:click.prevent="detail('{{ $row->id_santri }}')"
                                        class="text-secondary-blue mr-3">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="text-red-500">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="p-6 text-center text-gray-500">
                                    Data pembayaran pendaftaran kosong
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- FULL CENTER SPINNER -->
                <div wire:loading wire:target="search,paginate"
                    class="absolute inset-0 bg-white/80 backdrop-blur-sm flex items-center justify-center z-10">

                    <div class="flex flex-col items-center space-y-4 mt-10">
                        <svg class="animate-spin h-12 w-12 text-secondary-blue mt-1/2" viewBox="0 0 50 50">
                            <circle cx="25" cy="25" r="20" fill="none" stroke="currentColor"
                                stroke-width="4" stroke-linecap="round" stroke-dasharray="31.4 31.4" opacity="0.25" />
                            <circle cx="25" cy="25" r="20" fill="none" stroke="currentColor"
                                stroke-width="4" stroke-linecap="round" stroke-dasharray="31.4 94.2" />
                        </svg>
                    </div>

                </div>

            </div>
            @if ($datas->links())
                <div class="border-t border-gray-200 bg-white px-4 py-3">
                    {{ $datas->links() }}
                </div>
            @endif
        </div>
    </div>
    @if ($modalVerval)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">

            <!-- MODAL CONTAINER -->
            <div class="bg-white rounded-xl shadow-xl w-full max-w-6xl mx-4">

                <!-- HEADER -->
                <div class="flex items-center justify-between px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-circle-check text-green-600"></i>
                        Registrasi Ulang Santri
                    </h3>

                    <button wire:click="$set('modalVerval', false)" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>

                <!-- BODY -->
                <div class="px-6 py-6">
                    {{-- Row Atas --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- KIRI: STRUK -->
                        <div class="border rounded-lg p-4 border-gray-500">
                            <p class="text-sm font-semibold text-gray-700 mb-3">
                                Detail Santri
                            </p>

                            @if ($userById)
                                <div class="space-y-3 text-sm">

                                    @php
                                        $items = [
                                            'Nama' => $userById->nama ?? '-',
                                            'Tempat, Tgl Lahir' =>
                                                ($userById->tempat ?? '-') . ', ' . ($userById->tanggal ?? '-'),
                                            'Jenis Kelamin' => $userById->jkl == 'L' ? 'Laki-laki' : 'Perempuan',
                                            'Alamat' =>
                                                $userById->desa . ' - ' . $userById->kec . ' - ' . $userById->kab ??
                                                '-',
                                            'Nama Bapak' => $userById->bapak ?? '-',
                                            'Nama Ibu' => $userById->ibu ?? '-',
                                            'Lembaga' => $userById->lembaga ?? '-',
                                            'No HP' => $userById->hp ?? '-',
                                            'Gelombang' => $userById->gel ?? '-',
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

                                </div>
                            @else
                                <p class="text-sm text-gray-500">Data tidak ditemukan</p>
                            @endif
                        </div>

                        <!-- KANAN: DETAIL -->
                        <div class="border rounded-lg border-gray-200 p-4">
                            <p class="text-sm font-semibold text-gray-700 mb-4">
                                Nominal Tanggungan
                            </p>
                            @if ($tanggungan && $tanggungan->count() > 0)
                                <div class="space-y-3 text-sm">

                                    @foreach ($tanggungan as $label)
                                        <div class="flex justify-between gap-4">
                                            <span class="text-gray-500">{{ $label->nama }}</span>
                                            <span class="font-medium text-gray-900 text-right">
                                                {{ $label->nominal ? number_format($label->nominal) : '-' }}
                                            </span>
                                        </div>
                                    @endforeach

                                </div>
                            @else
                                <p class="text-sm text-gray-500">Data Tanggungan tidak ada</p>
                            @endif

                        </div>

                    </div>

                    {{-- Row Bawah --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                        <!-- TOTAL TANGGUNGAN -->
                        <div class="border rounded-lg p-4 border-gray-200 mt-6 md:col-span-1">
                            <p class="text-sm font-semibold text-gray-700 mb-3">
                                Input Pembayaran
                            </p>
                            <form wire:submit.prevent="simpanPembayaran">
                                <label for="nominal" class="block text-sm/6 font-medium text-gray-900">Nominal</label>
                                <div class="mt-1">
                                    <input id="nominal" type="text" wire:model="nominal" autocomplete="nominal"
                                        required
                                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
                                </div>
                                <label for="tgl_bayar" class="block text-sm/6 font-medium text-gray-900">Tanggal
                                    Bayar</label>
                                <div class="mt-2">
                                    <input id="tgl_bayar" type="date" wire:model="tgl_bayar"
                                        autocomplete="tgl_bayar" required
                                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
                                </div>
                                <label for="tgl_bayar" class="block text-sm/6 font-medium text-gray-900">Via</label>
                                <div class="mt-2">
                                    <div class="space-y-2">
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input id="default-radio-1" type="radio" value="Transfer"
                                                wire:model="via"
                                                class="
                                                    w-4 h-4
                                                    appearance-none
                                                    rounded-full
                                                    border border-default-medium
                                                    bg-neutral-secondary-medium
                                                    text-brand

                                                    checked:bg-brand
                                                    checked:border-brand

                                                    focus:outline-none
                                                    focus:ring-2
                                                    focus:ring-brand
                                                    focus:ring-offset-2
                                                    focus:ring-offset-white
                                                ">
                                            <span class="select-none text-sm font-medium text-heading">
                                                Transfer
                                            </span>
                                        </label>

                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input id="default-radio-2" type="radio" value="Cash"
                                                wire:model="via"
                                                class="
                                                    w-4 h-4
                                                    appearance-none
                                                    rounded-full
                                                    border border-default-medium
                                                    bg-neutral-secondary-medium
                                                    text-brand

                                                    checked:bg-brand
                                                    checked:border-brand

                                                    focus:outline-none
                                                    focus:ring-2
                                                    focus:ring-brand
                                                    focus:ring-offset-2
                                                    focus:ring-offset-white
                                                ">
                                            <span class="select-none text-sm font-medium text-heading">
                                                Cash
                                            </span>
                                        </label>
                                    </div>


                                </div>
                                <div class="mt-6 flex items-center gap-x-3">
                                    <button type="submit" wire:loading.attr="disabled"
                                        class="relative inline-flex items-center justify-center
                                                min-w-[120px]
                                                rounded-md bg-indigo-600 px-4 py-2
                                                text-sm font-semibold text-white
                                                shadow-xs hover:bg-indigo-500
                                                focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600
                                                disabled:opacity-70">

                                        <!-- Loader -->
                                        <svg wire:loading class="absolute h-5 w-5 animate-spin text-white"
                                            viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                            <circle cx="12" cy="12" r="10" class="opacity-25"
                                                stroke="currentColor" stroke-width="4" />
                                            <path
                                                d="M12 22C14.6522 22 17.1957 20.9464 19.0711 19.0711C20.9464 17.1957 22 14.6522 22 12C22 9.34784 20.9464 6.8043 19.0711 4.92893C17.1957 3.05357 14.6522 2 12 2"
                                                class="opacity-75" stroke="currentColor" stroke-width="4" />
                                        </svg>

                                        <!-- Text (TIDAK DIHAPUS) -->
                                        <span wire:loading.class="opacity-0" class="transition-opacity">
                                            Simpan
                                        </span>
                                    </button>


                                </div>
                            </form>

                        </div>

                        <!-- TOTAL PEMBAYARAN -->
                        <div class="border rounded-lg p-4 border-gray-200 mt-6 md:col-span-2">
                            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-2 mb-4">
                                <div
                                    class="bg-white rounded-xl shadow-md p-2 border-l-4 border-secondary-blue shadow-hover">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="text-sm font-medium text-gray-500">Tanggungan</p>
                                            <p class="text-xl font-bold text-gray-800">
                                                Rp {{ number_format($totalTanggungan) }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="bg-white rounded-xl shadow-md p-2 border-l-4 border-green-500 shadow-hover">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="text-sm font-medium text-gray-500">Lunas</p>
                                            <p class="text-xl font-bold text-gray-800">
                                                Rp {{ number_format($totalBayar) }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-white rounded-xl shadow-md p-2 border-l-4 border-red-500 shadow-hover">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="text-sm font-medium text-gray-500">Belum Lunas</p>
                                            <p class="text-xl font-bold text-gray-800">Rp
                                                {{ number_format($totalTanggungan - $totalBayar) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        {{-- <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th> --}}
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Nominal</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Tanggal Bayar</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Kasir</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Aksi</th>
                                    </tr>
                                </thead>

                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse ($dataBayar as $row)
                                        <tr>
                                            {{-- <td class="px-6 py-3">
                                                {{ $dataBayar->firstItem() + $loop->index }}
                                            </td> --}}
                                            <td class="px-4 py-3">{{ number_format($row->nominal) }}</td>
                                            <td class="px-4 py-3 text-gray-700">{{ $row->tgl_bayar ?? '-' }}</td>
                                            <td class="px-4 py-3">{{ $row->kasir }}</td>

                                            <td class="px-4 py-3">
                                                <button wire:click="delBayar('{{ $row->id }}')"
                                                    wire:confirm="yakin akan menghapus data pembayaran ini?"
                                                    class="text-red-500">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="p-6 text-center text-gray-500">
                                                Data pembayaran kosong
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                        </div>

                    </div>
                </div>

                <!-- FOOTER -->
                <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 border-t">

                    <button wire:click="$set('modalVerval', false)"
                        class="rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-700 border hover:bg-gray-100">
                        Tutup
                    </button>
                </div>

            </div>
        </div>
    @endif
</div>
