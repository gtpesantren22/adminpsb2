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
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nominal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tgl Bayar</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Via</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($datas as $row)
                            <tr>
                                <td class="px-6 py-3">
                                    {{ $datas->firstItem() + $loop->index }}
                                </td>
                                <td class="px-6 py-3 text-gray-700">{{ $row->santri?->nama ?? '-' }}</td>
                                <td class="px-6 py-3">{{ $row->santri?->lembaga ?? '-' }}</td>
                                <td class="px-6 py-3">{{ number_format($row->nominal) }}</td>
                                <td class="px-6 py-3">{{ $row->tgl_bayar }}</td>
                                <td class="px-6 py-3">
                                    <span
                                        class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-sm ">{{ $row->via }}</span>
                                </td>

                                <td class="px-6 py-3">
                                    <button class="text-secondary-blue mr-3">
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


            {{ $datas->links() }}
        </div>
    </div>
</div>
