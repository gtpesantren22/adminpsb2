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
                </div>
            </div>

            <div class="overflow-x-auto">
                <div wire:loading class="text-sm text-gray-500">
                    Memuat data...
                </div>
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
                                JKL</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Gel</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                No. HP</th>
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
                        @forelse ($datas as $row)
                            <tr>
                                <td class="px-6 py-3 whitespace-nowrap text-md font-medium text-gray-900">
                                    {{ $loop->iteration }}</td>
                                {{-- <td class="px-6 py-3 whitespace-nowrap text-md text-gray-500">{{ $row['nis'] }}</td> --}}
                                <td class="px-6 py-3 whitespace-nowrap text-md text-gray-500">{{ $row->nama }}</td>
                                <td class="px-6 py-3 whitespace-nowrap text-md text-gray-500">
                                    {{ $row->desa }} - {{ $row->kec }} - {{ $row->kab }}</td>
                                <td class="px-6 py-3 whitespace-nowrap text-md text-gray-500">
                                    {{ $row->jkl }}</td>
                                <td class="px-6 py-3 whitespace-nowrap text-md text-gray-500">{{ $row->gel }}
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap text-md text-gray-500">{{ $row->hp }}</td>
                                <td class="px-6 py-3 whitespace-nowrap text-md text-gray-500">{{ $row->lembaga }}
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap text-md font-medium">
                                    <button class="text-secondary-blue hover:text-primary-blue mr-3">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="text-red-500 hover:text-red-700">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="p-4 text-center text-gray-500">
                                    Data santri tidak ada
                                </td>
                            </tr>
                        @endforelse


                    </tbody>
                </table>
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
</div>
