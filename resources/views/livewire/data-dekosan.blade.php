<div class="page-content relative min-h-[300px]" x-data="{ qzConnected: false, printerName: localStorage.getItem('qz_printer_name') || '', showRekap: false }" x-init="$watch('printerName', val => localStorage.setItem('qz_printer_name', val))">
    <!-- Toggle Rekap Button -->
    <div class="mb-4">
        <button @click="showRekap = !showRekap" type="button"
            class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-primary-blue hover:text-blue-700 transition focus:outline-none bg-white px-4 py-2.5 rounded-lg shadow-sm border border-gray-100 hover:bg-gray-50">
            <i class="fas" :class="showRekap ? 'fa-eye-slash text-red-500' : 'fa-chart-pie text-blue-500'"></i>
            <span x-text="showRekap ? 'Sembunyikan Rekap Tempat Kos' : 'Tampilkan Rekap Tempat Kos'"></span>
        </button>
    </div>

    <!-- REKAP PANEL -->
    <div x-show="showRekap" x-transition class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 animate-fade-in" style="display: none;">
        <!-- Rekap Putra -->
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center gap-2 mb-4">
                <div class="bg-blue-100 p-2 rounded-lg text-blue-700">
                    <i class="fas fa-mars text-lg"></i>
                </div>
                <h3 class="text-md font-bold text-gray-800">Rekap Tempat Kos Putra</h3>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @foreach($rekapPutra as $kos => $count)
                    <div class="bg-gray-50 rounded-lg p-2.5 flex items-center justify-between border border-gray-100 hover:shadow-sm transition">
                        <span class="text-xs font-semibold text-gray-700 leading-tight">{{ $kos }}</span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold bg-blue-100 text-blue-800">
                            {{ $count }} Anak
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Rekap Putri -->
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-pink-500">
            <div class="flex items-center gap-2 mb-4">
                <div class="bg-pink-100 p-2 rounded-lg text-pink-700">
                    <i class="fas fa-venus text-lg"></i>
                </div>
                <h3 class="text-md font-bold text-gray-800">Rekap Tempat Kos Putri</h3>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @foreach($rekapPutri as $kos => $count)
                    <div class="bg-gray-50 rounded-lg p-2.5 flex items-center justify-between border border-gray-100 hover:shadow-sm transition">
                        <span class="text-xs font-semibold text-gray-700 leading-tight">{{ $kos }}</span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold bg-pink-100 text-pink-800">
                            {{ $count }} Anak
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Header/Filter Bar -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
        <div class="px-6 py-5 border-b border-gray-200 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="bg-blue-100 p-2.5 rounded-lg text-primary-blue flex-shrink-0">
                    <i class="fas fa-hotel text-xl"></i>
                </div>
                <div>
                    <div class="flex flex-wrap items-center gap-2.5">
                        <h2 class="text-xl font-bold text-gray-800">Transaksi Dekosan</h2>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-50 text-green-700 border border-green-200">
                            Total Masuk: Rp {{ number_format($totalDana, 0, ',', '.') }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-500 mt-0.5">Kelola administrasi dan penempatan tempat kos santri</p>
                </div>
            </div>
            
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" wire:model.live="search"
                        class="pl-10 pr-4 py-2 w-full sm:w-64 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-secondary-blue focus:border-transparent text-sm"
                        placeholder="Cari nama santri / kos...">
                </div>

                <!-- Printer Settings Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" type="button"
                        class="bg-gray-100 text-gray-700 font-medium py-2.5 px-3.5 rounded-lg hover:bg-gray-200 focus:outline-none transition flex items-center justify-center gap-1.5 text-sm"
                        title="Pengaturan Printer">
                        <i class="fas fa-cog text-gray-500"></i>
                        <span class="hidden md:inline">Printer USB</span>
                        <i class="fas fa-chevron-down text-[10px]"></i>
                    </button>
                    <div x-show="open" @click.away="open = false" 
                        class="absolute right-0 mt-2 w-64 bg-white border border-gray-200 rounded-lg shadow-xl p-4 z-20 animate-fade-in"
                        style="display: none;">
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Nama Printer USB (QZ Tray)</label>
                        <input type="text" x-model="printerName"
                            class="w-full px-3 py-1.5 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-secondary-blue text-sm mb-2"
                            placeholder="Contoh: Thermal Printer, XP-80">
                        <p class="text-[10px] text-gray-500 leading-tight">
                            Masukkan nama printer USB Anda sesuai yang terdaftar di Windows. Jika dikosongkan, QZ Tray akan menggunakan printer default sistem.
                        </p>
                    </div>
                </div>

                <button wire:click="openCreateModal"
                    class="bg-gradient-to-r from-primary-blue to-secondary-blue text-white font-medium py-2.5 px-4 rounded-lg hover:from-primary-blue hover:to-primary-blue focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-blue shadow-md transition duration-150 flex items-center justify-center gap-2">
                    <i class="fas fa-plus"></i>
                    <span>Tambah Transaksi</span>
                </button>
            </div>
        </div>

        <!-- Table List -->
        <div class="relative overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">#</th>
                        <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Santri</th>
                        <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Lembaga</th>
                        <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Gender</th>
                        <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nominal</th>
                        <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tgl Bayar</th>
                        <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tempat Kos</th>
                        <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kasir</th>
                        <th scope="col" class="px-6 py-3.5 class text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($dekosans as $item)
                        <tr class="hover:bg-gray-50/80 transition duration-100">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ ($dekosans->currentPage() - 1) * $dekosans->perPage() + $loop->iteration }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="h-8 w-8 rounded-full bg-blue-50 flex items-center justify-center text-primary-blue font-semibold text-sm">
                                        {{ substr($item->santri->nama ?? '-', 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold text-gray-900">{{ $item->santri->nama ?? '-' }}</div>
                                        <div class="text-xs text-gray-500">NIS: {{ $item->santri->nis ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $item->santri->lembaga ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if(($item->santri->jkl ?? '') === 'Laki-laki')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
                                        <i class="fas fa-mars text-[10px]"></i> Putra
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-pink-50 text-pink-700">
                                        <i class="fas fa-venus text-[10px]"></i> Putri
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                Rp {{ number_format($item->nominal, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $item->tgl_bayar->format('d-m-Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2.5 py-1 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-100">
                                    {{ $item->tempat_kos }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $item->kasir }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="inline-flex items-center gap-1.5">
                                    <!-- Print -->
                                    <button wire:click="printReceipt({{ $item->id }})" wire:loading.attr="disabled"
                                        class="p-1.5 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 hover:text-gray-900 transition shadow-sm"
                                        title="Cetak Struk Thermal">
                                        <i class="fas fa-print text-xs"></i>
                                    </button>
                                    
                                    <!-- Edit -->
                                    <button wire:click="edit({{ $item->id }})"
                                        class="p-1.5 bg-blue-50 text-blue-600 rounded-md hover:bg-blue-100 hover:text-blue-700 transition shadow-sm"
                                        title="Ubah Data">
                                        <i class="fas fa-edit text-xs"></i>
                                    </button>

                                    <!-- Hapus -->
                                    <button wire:confirm="Apakah Anda yakin ingin menghapus data transaksi ini?"
                                        wire:click="delete({{ $item->id }})"
                                        class="p-1.5 bg-red-50 text-red-600 rounded-md hover:bg-red-100 hover:text-red-700 transition shadow-sm"
                                        title="Hapus Data">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="p-8 text-center text-gray-500 italic">
                                Data transaksi tidak ditemukan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $dekosans->links() }}
        </div>
    </div>

    <!-- CREATE MODAL -->
    @if($modalCreate)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden animate-fade-in">
                <!-- Header -->
                <div class="flex items-center justify-between px-6 py-4 border-b">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-plus-circle text-primary-blue"></i>
                        Tambah Transaksi Dekosan
                    </h3>
                    <button wire:click="$set('modalCreate', false)" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>

                <form wire:submit.prevent="store" class="p-6 space-y-4">
                    <!-- Santri Selection -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Pilih Santri</label>
                        @if($selectedSantri)
                            <!-- Profile Card of Selected Santri -->
                            <div class="bg-blue-50/50 border border-blue-200 rounded-lg p-3 flex justify-between items-center">
                                <div>
                                    <h4 class="text-sm font-bold text-gray-900">{{ $selectedSantri['nama'] }}</h4>
                                    <p class="text-xs text-gray-500">NIS: {{ $selectedSantri['nis'] ?? '-' }} | {{ $selectedSantri['jkl'] }}</p>
                                    <p class="text-xs text-blue-700 font-medium">{{ $selectedSantri['lembaga'] }}</p>
                                </div>
                                <button type="button" wire:click="resetForm" class="text-xs bg-red-100 text-red-700 px-2 py-1.5 rounded-md hover:bg-red-200 font-semibold transition">
                                    Ganti
                                </button>
                            </div>
                        @else
                            <!-- Search box for Santri -->
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                                <input type="text" wire:model.live="searchSantri"
                                    class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-secondary-blue text-sm"
                                    placeholder="Ketik minimal 2 karakter nama/NIS...">
                                
                                @if(count($santriList) > 0)
                                    <div class="absolute z-10 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg max-h-48 overflow-y-auto divide-y divide-gray-100">
                                        @foreach($santriList as $s)
                                            <button type="button" wire:click="selectSantri('{{ $s['id_santri'] }}')"
                                                class="w-full text-left px-4 py-2.5 hover:bg-blue-50/50 flex justify-between items-center transition">
                                                <div>
                                                    <span class="block text-sm font-semibold text-gray-900">{{ $s['nama'] }}</span>
                                                    <span class="block text-xs text-gray-500">NIS: {{ $s['nis'] ?? '-' }} | {{ $s['lembaga'] }}</span>
                                                </div>
                                                <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full">
                                                    {{ $s['jkl'] == 'Laki-laki' ? 'Putra' : 'Putri' }}
                                                </span>
                                            </button>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            @error('id_santri') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                        @endif
                    </div>

                    <!-- Nominal Bayar -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Nominal Bayar (Rp)</label>
                        <div x-data="{
                            formatted: '',
                            init() {
                                this.formatted = $wire.nominal ? new Intl.NumberFormat('id-ID').format($wire.nominal) : '';
                                this.$watch('$wire.nominal', value => {
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
                                        $wire.set('nominal', raw);
                                    "
                                    class="pl-9 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-secondary-blue text-sm"
                                    placeholder="Masukkan nominal">
                            </div>
                        </div>
                        @error('nominal') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Tanggal Bayar -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Tanggal Bayar</label>
                        <input type="date" wire:model="tgl_bayar"
                            class="px-4 py-2 w-full border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-secondary-blue text-sm">
                        @error('tgl_bayar') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="bg-gray-50 -mx-6 -mb-6 px-6 py-4 flex justify-end gap-3 border-t mt-6">
                        <button type="button" wire:click="$set('modalCreate', false)"
                            class="rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-700 border hover:bg-gray-50 transition shadow-sm">
                            Batal
                        </button>
                        <button type="submit"
                            class="rounded-lg bg-gradient-to-r from-primary-blue to-secondary-blue px-4 py-2 text-sm font-semibold text-white hover:opacity-90 transition shadow-md">
                            Simpan & Cetak
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- EDIT MODAL -->
    @if($modalEdit)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden animate-fade-in">
                <!-- Header -->
                <div class="flex items-center justify-between px-6 py-4 border-b">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-edit text-blue-600"></i>
                        Edit Transaksi Dekosan
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

                    <!-- Tempat Kos Manual Override -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Tempat Kos (Manual)</label>
                        <select wire:model="edit_tempat_kos"
                            class="px-4 py-2 w-full border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-secondary-blue text-sm">
                            <option value="">-- Pilih Tempat Kos --</option>
                            @if($editingSantri && $editingSantri['jkl'] === 'Laki-laki')
                                @foreach($tempatKosPutra as $kos)
                                    <option value="{{ $kos }}">{{ $kos }} (Putra)</option>
                                @endforeach
                            @else
                                @foreach($tempatKosPutri as $kos)
                                    <option value="{{ $kos }}">{{ $kos }} (Putri)</option>
                                @endforeach
                            @endif
                        </select>
                        @error('edit_tempat_kos') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
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

    <!-- QZ TRAY PRINTER JS INTEGRATION -->
    <script src="https://cdn.jsdelivr.net/npm/qz-tray@2.2.4/qz-tray.min.js"></script>
    <script>
        // Check websocket active on load
        function checkQzConnection() {
            if (typeof qz !== 'undefined') {
                if (qz.websocket.isActive()) {
                    window.dispatchEvent(new CustomEvent('qz-status', { detail: true }));
                } else {
                    qz.websocket.connect().then(() => {
                        window.dispatchEvent(new CustomEvent('qz-status', { detail: true }));
                    }).catch(() => {
                        window.dispatchEvent(new CustomEvent('qz-status', { detail: false }));
                    });
                }
            }
        }
        
        document.addEventListener('DOMContentLoaded', checkQzConnection);
        document.addEventListener('livewire:navigated', checkQzConnection);

        document.addEventListener('livewire:init', () => {
            Livewire.on('print-dekosan', (event) => {
                const data = Array.isArray(event) ? event[0] : event;
                printThermalReceipt(data);
            });
        });

        function printThermalReceipt(data) {
            if (typeof qz === 'undefined') {
                alert("QZ Tray SDK tidak terload. Periksa koneksi internet Anda.");
                return;
            }

            if (!qz.websocket.isActive()) {
                qz.websocket.connect().then(() => {
                    findAndPrint(data);
                }).catch((err) => {
                    alert("Gagal terhubung ke QZ Tray. Pastikan aplikasi QZ-Tray di komputer Anda sudah dijalankan.");
                    console.error(err);
                });
            } else {
                findAndPrint(data);
            }
        }

        function findAndPrint(data) {
            const configuredPrinter = localStorage.getItem('qz_printer_name') || '';
            
            if (configuredPrinter.trim() !== '') {
                console.log("Using configured printer: " + configuredPrinter);
                sendToPrinter(configuredPrinter, data);
            } else {
                qz.printers.getDefault().then((printer) => {
                    console.log("Using default printer: " + printer);
                    sendToPrinter(printer, data);
                }).catch((err) => {
                    console.warn("Default printer not found. Searching for active printers...");
                    qz.printers.find().then((printers) => {
                        if (printers.length > 0) {
                            sendToPrinter(printers[0], data);
                        } else {
                            alert("Printer tidak ditemukan. Pastikan printer thermal sudah terinstall di perangkat Anda atau konfigurasikan namanya di menu Pengaturan Printer.");
                        }
                    }).catch((e) => {
                        alert("Error mencari printer: " + e.message);
                    });
                });
            }
        }

        function sendToPrinter(printerName, data) {
            var config = qz.configs.create(printerName, {
                size: { width: 3.12, height: 6.0 }, // 80mm
                units: 'in',
                margins: { top: 0.1, right: 0.1, bottom: 0.1, left: 0.1 }
            });

            const formattedNominal = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(data.nominal);

            var htmlData = `
                <div style="font-family: 'Courier New', Courier, monospace; font-size: 11px; width: 280px; margin: 0 auto; color: #000; line-height: 1.4;">
                    <div style="text-align: center; font-weight: bold; font-size: 13px; margin-bottom: 2px;">
                        PESANTREN DARUL LUGHAH<br>WAL KAROMAH
                    </div>
                    <div style="text-align: center; font-size: 9px; margin-bottom: 5px;">
                        Krapyak, Kraksaan, Probolinggo
                    </div>
                    <div style="border-top: 1px dashed #000; margin: 6px 0;"></div>
                    <div style="text-align: center; font-weight: bold; font-size: 11px; margin-bottom: 8px;">
                        BUKTI PEMBAYARAN DEKOSAN
                    </div>
                    <table style="width: 100%; border-collapse: collapse; font-size: 11px;">
                        <tr>
                            <td style="width: 35%; padding: 2px 0; vertical-align: top;">No. Transaksi</td>
                            <td style="width: 5%; padding: 2px 0; vertical-align: top;">:</td>
                            <td style="width: 60%; padding: 2px 0; font-weight: bold;">DKS-${data.id}</td>
                        </tr>
                        <tr>
                            <td style="padding: 2px 0; vertical-align: top;">Nama Santri</td>
                            <td style="padding: 2px 0; vertical-align: top;">:</td>
                            <td style="padding: 2px 0; font-weight: bold; text-transform: uppercase;">${data.nama}</td>
                        </tr>
                        <tr>
                            <td style="padding: 2px 0; vertical-align: top;">Lembaga</td>
                            <td style="padding: 2px 0; vertical-align: top;">:</td>
                            <td style="padding: 2px 0;">${data.lembaga}</td>
                        </tr>
                        <tr>
                            <td style="padding: 2px 0; vertical-align: top;">Gender</td>
                            <td style="padding: 2px 0; vertical-align: top;">:</td>
                            <td style="padding: 2px 0;">${data.jkl}</td>
                        </tr>
                        <tr>
                            <td style="padding: 2px 0; vertical-align: top;">Tempat Kos</td>
                            <td style="padding: 2px 0; vertical-align: top;">:</td>
                            <td style="padding: 2px 0; font-weight: bold; text-decoration: underline;">${data.tempat_kos}</td>
                        </tr>
                        <tr>
                            <td style="padding: 2px 0; vertical-align: top;">Tanggal</td>
                            <td style="padding: 2px 0; vertical-align: top;">:</td>
                            <td style="padding: 2px 0;">${data.tanggal}</td>
                        </tr>
                        <tr>
                            <td style="padding: 2px 0; vertical-align: top;">Nominal</td>
                            <td style="padding: 2px 0; vertical-align: top;">:</td>
                            <td style="padding: 2px 0; font-weight: bold; font-size: 12px;">${formattedNominal}</td>
                        </tr>
                    </table>
                    <div style="border-top: 1px dashed #000; margin: 8px 0;"></div>
                    <table style="width: 100%; text-align: center; font-size: 10px;">
                        <tr>
                            <td style="width: 50%;">Penerima/Kasir,</td>
                            <td style="width: 50%;">Penyetor,</td>
                        </tr>
                        <tr style="height: 35px;">
                            <td colspan="2"></td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold; text-transform: uppercase;">(${data.kasir})</td>
                            <td>(..................)</td>
                        </tr>
                    </table>
                    <div style="border-top: 1px dashed #000; margin: 8px 0;"></div>
                    <div style="text-align: center; font-size: 9px; font-style: italic; margin-top: 6px;">
                        Simpan bukti pembayaran ini sebagai tanda bukti yang sah. Terima kasih.
                    </div>
                </div>
            `;

            var printData = [
                {
                    type: 'html',
                    format: 'plain',
                    data: htmlData
                }
            ];

            return qz.print(config, printData).then(() => {
                console.log("Print job sent successfully!");
            }).catch((err) => {
                alert("Error printing: " + err.message);
            });
        }
    </script>
</div>
