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
                                <td class="px-6 py-3 font-semibold text-gray-900">
                                    Rp {{ number_format($row->nominal, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-3 text-gray-600 text-sm">
                                    {{ $row->tgl_bayar ? $row->tgl_bayar->format('d-m-Y') : '-' }}
                                </td>
                                <td class="px-6 py-3">
                                    @if($row->via === 'Cash')
                                        <span class="bg-green-50 text-green-700 border border-green-100 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                            {{ $row->via }}
                                        </span>
                                    @else
                                        <span class="bg-blue-50 text-blue-700 border border-blue-100 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                            {{ $row->via }}
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <!-- Edit -->
                                        <button wire:click="edit('{{ $row->id_bayar }}')"
                                            class="p-1.5 bg-blue-50 text-blue-600 rounded-md hover:bg-blue-100 hover:text-blue-700 transition shadow-sm"
                                            title="Ubah Data">
                                            <i class="fas fa-edit text-xs"></i>
                                        </button>

                                        <!-- Hapus -->
                                        <button wire:confirm="Apakah Anda yakin ingin menghapus data pembayaran pendaftaran ini?"
                                            wire:click="delete('{{ $row->id_bayar }}')"
                                            class="p-1.5 bg-red-50 text-red-600 rounded-md hover:bg-red-100 hover:text-red-700 transition shadow-sm"
                                            title="Hapus Data">
                                            <i class="fas fa-trash text-xs"></i>
                                        </button>
                                    </div>
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

    <!-- EDIT MODAL -->
    @if($modalEdit)
        <div class="fixed inset-0 bg-gray-500/75 backdrop-blur-sm flex items-center justify-center p-4 z-50 animate-fade-in">
            <div class="bg-white rounded-xl shadow-2xl max-w-lg w-full overflow-hidden transform transition-all duration-300 scale-100">
                <div class="px-6 py-4 border-b flex justify-between items-center bg-gray-50">
                    <h3 class="text-md font-bold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-edit text-blue-600"></i>
                        Ubah Pembayaran Pendaftaran
                    </h3>
                    <button wire:click="$set('modalEdit', false)" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>

                <form wire:submit.prevent="update" class="p-6 space-y-4">
                    <!-- Santri Info (Read Only) -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Santri</label>
                        @if($editingSantri)
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
                                <h4 class="text-sm font-bold text-gray-900">{{ $editingSantri['nama'] }}</h4>
                                <p class="text-xs text-gray-500">NIS: {{ $editingSantri['nis'] ?? '-' }} | {{ $editingSantri['jkl'] }}</p>
                                <p class="text-xs text-blue-700 font-medium">{{ $editingSantri['lembaga'] }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- Nominal Bayar -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Nominal Bayar (Rp)</label>
                        <div x-data="{
                            formatted: '',
                            init() {
                                this.formatted = $wire.edit_nominal ? new Intl.NumberFormat('id-ID').format($wire.edit_nominal) : '';
                                this.$watch('$wire.edit_nominal', value => {
                                    if (!value) {
                                        this.formatted = '';
                                    } else {
                                        this.formatted = new Intl.NumberFormat('id-ID').format(value);
                                    }
                                });
                            }
                        }">
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 text-sm">Rp</span>
                                </div>
                                <input type="text" x-model="formatted"
                                    @input="
                                        let raw = formatted.replace(/\D/g, '');
                                        formatted = raw ? new Intl.NumberFormat('id-ID').format(raw) : '';
                                        $wire.set('edit_nominal', raw);
                                    "
                                    class="pl-9 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-secondary-blue text-sm"
                                    placeholder="Masukkan nominal">
                            </div>
                        </div>
                        @error('edit_nominal') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Tanggal Bayar -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Tanggal Bayar</label>
                        <input type="date" wire:model="edit_tgl_bayar"
                            class="px-4 py-2 w-full border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-secondary-blue text-sm">
                        @error('edit_tgl_bayar') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Metode Pembayaran (Via) -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Metode Pembayaran</label>
                        <select wire:model="edit_via"
                            class="px-4 py-2 w-full border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-secondary-blue text-sm">
                            <option value="">-- Pilih Metode --</option>
                            <option value="Cash">Cash</option>
                            <option value="Transfer">Transfer</option>
                        </select>
                        @error('edit_via') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="bg-gray-50 -mx-6 -mb-6 px-6 py-4 flex justify-end gap-3 border-t mt-6">
                        <button type="button" wire:click="$set('modalEdit', false)"
                            class="rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-700 border hover:bg-gray-50 transition shadow-sm">
                            Batal
                        </button>
                        <button type="submit"
                            class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-500 transition shadow-md">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
