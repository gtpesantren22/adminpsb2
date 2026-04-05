<div x-data="{
    confirmWaSend(action, id, title, nama) {
        Swal.fire({
            title: title + '?',
            html: 'Kirim pesan ke <strong>' + nama + '</strong>',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3b82f6',
            cancelButtonColor: '#ef4444',
            confirmButtonText: '<i class=\'fas fa-paper-plane mr-1\'></i> Ya, Kirim!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Mengirim Pesan...',
                    html: 'Harap tunggu, pesan sedang dikirim.',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                $wire[action](id);
            }
        });
    }
}">
    <!-- Master Data Page -->
    <div class="page-content">

        <div class="bg-white rounded-xl shadow-md overflow-hidden shadow-hover">
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

            <div class="relative overflow-x-auto min-h-[300px]">
                <!-- TABLE -->
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                Nama
                            </th>
                            {{-- <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Alamat</th> --}}
                            {{-- <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No. HP</th> --}}
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lembaga</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Last Message
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Registrasi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase w-48">Aksi
                                Pengiriman</th>
                        </tr>
                    </thead>

                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($datas as $row)
                            <tr>
                                <td class="px-6 py-3 align-middle">
                                    {{ $datas->firstItem() + $loop->index }}
                                </td>
                                <td class="px-6 py-3 text-gray-700 font-medium align-middle">{{ $row->nama }}</td>
                                {{-- <td class="px-6 py-3 text-gray-500 text-sm align-middle">
                                    {{ $row->desa }} - {{ $row->kec }} - {{ $row->kab }}
                                </td> --}}
                                {{-- <td class="px-6 py-3 text-gray-700 align-middle">{{ $row->hp }}</td> --}}
                                <td class="px-6 py-3 text-gray-700 align-middle">{{ $row->lembaga }}</td>
                                <td class="px-6 py-3 text-gray-700 align-middle">
                                    @if ($row->direction == 'outbound')
                                        <span class="bg-green-100 text-green-700 text-xs font-semibold px-2 py-1 rounded-md">
                                            <i class="fas fa-paper-plane mr-2"></i> {{ $row->last_message }}
                                        </span>
                                    @else
                                        <span class="bg-red-100 text-red-700 text-xs font-semibold px-2 py-1 rounded-md">
                                            <i class="fas fa-reply mr-2"></i> {{ $row->last_message }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-3 text-gray-700 align-middle">
                                    @php
                                        $prosentase =
                                            $row->total_bayar > 0
                                                ? ($row->total_bayar / $row->total_tanggungan) * 100
                                                : 0;
                                    @endphp
                                    @if ($row->total_bayar >= $row->total_tanggungan)
                                        <span
                                            class="bg-green-100 text-green-700 text-xs font-semibold px-2 py-1 rounded-md">
                                            Lunas {{ round($prosentase, 1) }}%
                                        </span>
                                    @else
                                        <span
                                            class="bg-red-100 text-red-700 text-xs font-semibold px-2 py-1 rounded-md">
                                            Belum {{ round($prosentase, 1) }}%
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-3 align-middle">
                                    <span
                                        class="bg-green-100 text-green-700 text-xs font-semibold px-2 py-1 rounded-md">
                                        Terverifikasi
                                    </span>
                                </td>

                                <td class="px-6 py-3 align-middle text-center">
                                    <!-- Dropdown Alpine.js -->
                                    <div x-data="{ open: false }" class="relative inline-block text-left">

                                        <button @click="open = !open" @click.away="open = false"
                                            class="inline-flex justify-center items-center w-full px-4 py-2 text-sm font-semibold text-white bg-secondary-blue hover:bg-blue-700 rounded-lg shadow-sm focus:outline-none transition-colors">
                                            <i class="fab fa-whatsapp text-lg mr-2"></i> Pesan
                                            <i class="fas fa-chevron-down ml-2 text-xs"></i>
                                        </button>

                                        <!-- Dropdown Menu -->
                                        <div x-show="open" x-transition:enter="transition ease-out duration-100"
                                            x-transition:enter-start="transform opacity-0 scale-95"
                                            x-transition:enter-end="transform opacity-100 scale-100"
                                            x-transition:leave="transition ease-in duration-75"
                                            x-transition:leave-start="transform opacity-100 scale-100"
                                            x-transition:leave-end="transform opacity-0 scale-95"
                                            class="absolute right-0 z-20 w-56 mt-2 origin-top-right bg-white rounded-lg shadow-xl ring-1 ring-black ring-opacity-5 focus:outline-none overflow-hidden"
                                            style="display: none;">

                                            <div class="py-1">
                                                <!-- Item 1 -->
                                                <button
                                                    @click="open = false; confirmWaSend('sendKonfirmasi', '{{ $row->id_santri }}', 'Kirim Konfirmasi Pendaftaran', '{{ $row->nama }}')"
                                                    class="w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 border-b border-gray-100 flex items-center gap-3 transition-colors">
                                                    <i class="fas fa-check-circle w-4 text-center"></i> Konfirmasi
                                                </button>

                                                <!-- Item 2 -->
                                                <button
                                                    @click="open = false; confirmWaSend('sendPembayaran', '{{ $row->id_santri }}', 'Kirim Tagihan Pembayaran', '{{ $row->nama }}')"
                                                    class="w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 border-b border-gray-100 flex items-center gap-3 transition-colors">
                                                    <i class="fas fa-money-bill-wave w-4 text-center"></i> Pembayaran
                                                </button>

                                                <!-- Item 3 -->
                                                <button
                                                    @click="open = false; confirmWaSend('sendRegistrasi', '{{ $row->id_santri }}', 'Kirim Info Registrasi/Daftar Ulang', '{{ $row->nama }}')"
                                                    class="w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-700 border-b border-gray-100 flex items-center gap-3 transition-colors">
                                                    <i class="fas fa-id-card w-4 text-center"></i> Registrasi
                                                </button>

                                                <!-- Item 4 -->
                                                <button
                                                    @click="open = false; confirmWaSend('sendGroup', '{{ $row->id_santri }}', 'Kirim Undangan WA Group', '{{ $row->nama }}')"
                                                    class="w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-700 border-b border-gray-100 flex items-center gap-3 transition-colors">
                                                    <i class="fab fa-whatsapp w-4 text-center"></i> Join Group
                                                </button>

                                                <!-- Item 5 -->
                                                <button
                                                    @click="open = false; confirmWaSend('sendSeragam', '{{ $row->id_santri }}', 'Kirim Jadwal Seragam', '{{ $row->nama }}')"
                                                    class="w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-teal-50 hover:text-teal-700 flex items-center gap-3 transition-colors">
                                                    <i class="fas fa-tshirt w-4 text-center"></i> Info Seragam
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-6 text-center text-gray-500">
                                    Data santri tidak ada
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- FULL HEADER LOADING INDICATOR -->
                <div wire:loading wire:target="search,paginate"
                    class="absolute inset-0 bg-white/80 backdrop-blur-sm flex items-center justify-center z-10">

                    <div class="flex flex-col items-center space-y-4 mt-10">
                        <svg class="animate-spin h-12 w-12 text-secondary-blue mt-1/2" viewBox="0 0 50 50">
                            <circle cx="25" cy="25" r="20" fill="none" stroke="currentColor"
                                stroke-width="4" stroke-linecap="round" stroke-dasharray="31.4 31.4" opacity="0.25" />
                            <circle cx="25" cy="25" r="20" fill="none" stroke="currentColor"
                                stroke-width="4" stroke-linecap="round" stroke-dasharray="31.4 94.2" />
                        </svg>

                        <span class="text-sm text-gray-600 font-medium">
                            Memuat data...
                        </span>
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
</div>
