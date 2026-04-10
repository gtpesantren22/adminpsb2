<div>
    <!-- Modal Loader -->
    <div wire:loading wire:target="save" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50 backdrop-blur-sm">
        <div class="bg-white p-5 rounded-lg shadow-xl flex flex-col items-center">
            <svg class="animate-spin h-10 w-10 text-primary-green mb-3" viewBox="0 0 50 50">
                <circle cx="25" cy="25" r="20" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-dasharray="31.4 31.4" opacity="0.25" />
                <circle cx="25" cy="25" r="20" fill="none" stroke="#22c55e" stroke-width="4" stroke-linecap="round" stroke-dasharray="31.4 94.2" />
            </svg>
            <span class="text-gray-700 font-medium">Menyimpan data...</span>
        </div>
    </div>

    <!-- Header Section -->
    <div class="glass-effect rounded-t-2xl p-6 text-center border-b border-gray-200">
        <div class="inline-block mb-3">
            <img src="{{ asset('logo/logo pp.png') }}" alt="Logo PP" class="h-16 mx-auto object-contain drop-shadow-md">
        </div>
        <h1 class="text-2xl font-bold text-gray-800 tracking-tight">Formulir Seragam</h1>
        <p class="text-sm font-bold text-green-700 mt-1 uppercase tracking-widest">PSB 2026/2027</p>
        <p class="text-xs text-gray-500 mt-1">Pondok Pesantren Darul Lughah Wal Karomah</p>
    </div>

    <!-- Identitas Santri -->
    <div class="bg-white p-6 shadow-sm border-b border-gray-100">
        <h2 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Identitas Santri</h2>
        
        <div class="flex items-start space-x-3 mb-2">
            <div class="mt-1 flex-shrink-0">
                <i class="fas fa-user-circle text-gray-400 text-lg"></i>
            </div>
            <div>
                <p class="font-bold text-gray-800 text-lg leading-tight">{{ strtoupper($santri->nama) }}</p>
                <div class="flex items-center text-xs font-semibold px-2 py-1 bg-blue-50 text-blue-600 rounded mt-1 inline-block">
                    {{ strtoupper($santri->jkl) }}
                </div>
            </div>
        </div>

        <div class="flex items-start space-x-3 mb-2 mt-4">
            <div class="flex-shrink-0">
                <i class="fas fa-map-marker-alt text-gray-400 text-sm"></i>
            </div>
            <div>
                <p class="text-sm text-gray-600 leading-snug">
                    <span class="font-medium text-gray-700">Alamat:</span> {{ $santri->desa }} - {{ $santri->kec }} - {{ $santri->kab }}
                </p>
            </div>
        </div>

        <div class="flex items-start space-x-3">
            <div class="flex-shrink-0">
                <i class="fas fa-school text-gray-400 text-sm"></i>
            </div>
            <div>
                <p class="text-sm text-gray-600 leading-snug">
                    <span class="font-medium text-gray-700">Lembaga:</span> {{ strtoupper($santri->lembaga) }}
                </p>
            </div>
        </div>
    </div>

    <!-- Tampilan Selesai (Read-Only) -->
    @if($isSubmitted)
        <div class="bg-white p-6 rounded-b-2xl shadow-sm">
            <div class="bg-green-50 rounded-xl p-5 border border-green-100 text-center mb-6">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-check text-green-500 text-xl"></i>
                </div>
                <h3 class="font-bold text-green-800 text-lg mb-1">Data Tersimpan</h3>
                <p class="text-sm text-green-600">Terima kasih, ukuran seragam ananda telah berhasil disimpan dalam sistem kami.</p>
            </div>

            <h4 class="font-bold text-gray-700 mb-4 border-b pb-2">Rincian Ukuran Terpilih</h4>
            
            <div class="space-y-4">
                <div class="flex justify-between items-center bg-gray-50 p-4 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-tshirt text-gray-400"></i>
                        <span class="font-medium text-gray-600">Baju (Atasan)</span>
                    </div>
                    <span class="font-bold text-gray-800 text-lg">{{ $submittedData->atasan }}</span>
                </div>

                <div class="flex justify-between items-center bg-gray-50 p-4 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-socks text-gray-400"></i>
                        <span class="font-medium text-gray-600">Celana/Rok (Bawahan)</span>
                    </div>
                    <span class="font-bold text-gray-800 text-lg">{{ $submittedData->bawahan }}</span>
                </div>

                @if(strtoupper(substr($santri->jkl, 0, 1)) === 'L' && $submittedData->songkok !== '0')
                <div class="flex justify-between items-center bg-gray-50 p-4 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-hat-cowboy text-gray-400"></i>
                        <span class="font-medium text-gray-600">Songkok</span>
                    </div>
                    <span class="font-bold text-gray-800 text-lg">{{ $submittedData->songkok }}</span>
                </div>
                @endif
            </div>
            
            <div class="mt-8 text-center">
                <p class="text-xs text-gray-400">Anda dapat menutup halaman ini.</p>
            </div>
        </div>

    <!-- Tampilan Form (Input) -->
    @else
        <div class="bg-white p-6 rounded-b-2xl shadow-sm">
            
            <!-- Panduan -->
            <div class="bg-blue-50/50 border border-blue-100 rounded-lg p-4 mb-6">
                <h4 class="text-sm font-bold text-blue-800 mb-2 flex items-center">
                    <i class="fas fa-info-circle mr-2"></i> Perhatian
                </h4>
                <ul class="text-xs text-blue-700 space-y-2 list-disc pl-4">
                    <li>Untuk rincian panjang dan bahu bisa dicek keterangannya yang muncul di bawah saat memilih ukuran.</li>
                    <li><strong>Mohon ukur dengan cermat</strong>, ukuran yang sudah dipesan tidak dapat ditukar.</li>
                </ul>
            </div>

            <form wire:submit.prevent="save">
                
                <!-- Baju / Atasan -->
                <div class="mb-5">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Ukuran Baju (Atasan)</label>
                    <div class="relative">
                        <select wire:model.live="atasan_id" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 bg-gray-50 py-3 pl-4 pr-10 text-base">
                            <option value="">-- Pilih Ukuran Baju --</option>
                            @foreach($listAtasan as $atasan)
                                <option value="{{ $atasan->id }}">Ukuran: {{ $atasan->ukuran }}</option>
                            @endforeach
                        </select>
                        @error('atasan_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    @if($detailAtasan)
                        <div class="mt-3 bg-blue-50 border border-blue-200 rounded-lg p-4 shadow-inner">
                            <h5 class="text-xs font-bold text-blue-800 uppercase mb-2 border-b border-blue-200 pb-1">Detail Ukuran Baju</h5>
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-sm text-blue-700">Lebar Dada:</span> 
                                <span class="text-blue-900 font-bold text-base">{{ $detailAtasan->ld }} <span class="text-xs font-normal">cm</span></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-blue-700">Panjang:</span> 
                                <span class="text-blue-900 font-bold text-base">{{ $detailAtasan->pj }} <span class="text-xs font-normal">cm</span></span>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Celana/Rok / Bawahan -->
                <div class="mb-5 border-t border-gray-100 pt-5">
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        Ukuran {{ strtoupper(substr($santri->jkl, 0, 1)) === 'P' ? 'Rok' : 'Celana' }} (Bawahan)
                    </label>
                    <div class="relative">
                        <select wire:model.live="bawahan_id" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 bg-gray-50 py-3 pl-4 pr-10 text-base">
                            <option value="">-- Pilih Ukuran Bawahan --</option>
                            @foreach($listBawahan as $bawahan)
                                <option value="{{ $bawahan->id }}">Ukuran: {{ $bawahan->ukuran }}</option>
                            @endforeach
                        </select>
                        @error('bawahan_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    @if($detailBawahan)
                        <div class="mt-3 bg-indigo-50 border border-indigo-200 rounded-lg p-4 shadow-inner">
                            <h5 class="text-xs font-bold text-indigo-800 uppercase mb-2 border-b border-indigo-200 pb-1">Detail Ukuran Bawahan</h5>
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-sm text-indigo-700">Lingkar Pinggang:</span> 
                                <span class="text-indigo-900 font-bold text-base">{{ $detailBawahan->lp }} <span class="text-xs font-normal">cm</span></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-indigo-700">Panjang:</span> 
                                <span class="text-indigo-900 font-bold text-base">{{ $detailBawahan->pj }} <span class="text-xs font-normal">cm</span></span>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Songkok (Laki-laki) -->
                @if(strtoupper(substr($santri->jkl, 0, 1)) === 'L')
                <div class="mb-6 border-t border-gray-100 pt-5">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Ukuran Songkok</label>
                    <select wire:model="songkok" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 bg-gray-50 py-3 pl-4 pr-10 text-base">
                        <option value="">-- Pilih Ukuran Songkok --</option>
                        <option value="3">No 3</option>
                        <option value="4">No 4</option>
                        <option value="5">No 5</option>
                        <option value="6">No 6</option>
                        <option value="7">No 7</option>
                        <option value="8">No 8</option>
                        <option value="9">No 9</option>
                    </select>
                    @error('songkok') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                @endif

                <div class="pt-4 border-t border-gray-100">
                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3.5 px-4 rounded-xl shadow-md transition duration-200 ease-in-out transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <i class="fas fa-save mr-2"></i> Simpan Pilihan
                    </button>
                    <p class="text-center text-xs text-gray-400 mt-4"><i class="fas fa-lock mr-1"></i> Data Anda dienkripsi dan aman</p>
                </div>
            </form>
        </div>
    @endif
</div>
