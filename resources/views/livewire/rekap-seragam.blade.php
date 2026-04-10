<div>
    <div class="bg-white rounded-lg shadow sm:p-6 mb-6">
        <div class="flex flex-col sm:flex-row justify-between items-center bg-green-50 p-4 rounded border border-green-200 mb-6">
            <div class="flex items-center mb-4 sm:mb-0">
                <img src="{{ asset('logo/pp.png') }}" alt="Logo PP" class="h-12 w-12 object-contain mr-4 drop-shadow-sm">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Data Rekap Pengisian Seragam</h2>
                    <p class="text-xs text-gray-500 font-semibold tracking-wider">PSB 2026/2027 TAHUN PELAJARAN BARU</p>
                </div>
            </div>
            <div>
                <!-- placeholder for action buttons if needed -->
            </div>
        </div>

        <!-- Filter Controls -->
        <div class="flex flex-col md:flex-row gap-4 mb-6">
            <div class="relative w-full md:w-1/3">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input wire:model.live="search" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full pl-10 p-2.5" placeholder="Cari berdasarkan nama santri...">
            </div>

            <div class="w-full md:w-1/4">
                <select wire:model.live="lembaga" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5">
                    <option value="">-- Semua Lembaga --</option>
                    @foreach($listLembaga as $lbg)
                        <option value="{{ $lbg }}">{{ $lbg }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto relative shadow-sm rounded-lg border border-gray-200">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th scope="col" class="px-6 py-3 w-16">No</th>
                        <th scope="col" class="px-6 py-3">Nama Santri</th>
                        <th scope="col" class="px-6 py-3">Jkl</th>
                        <th scope="col" class="px-6 py-3">Lembaga</th>
                        <th scope="col" class="px-6 py-3 text-center">Atasan (Baju)</th>
                        <th scope="col" class="px-6 py-3 text-center">Bawahan (Celana/Rok)</th>
                        <th scope="col" class="px-6 py-3 text-center">Songkok</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($datas as $index => $data)
                        <tr class="bg-white border-b hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $datas->firstItem() + $index }}</td>
                            <td class="px-6 py-4 font-bold text-gray-800">{{ $data->nama }}</td>
                            <td class="px-6 py-4">
                                @if(strtoupper(substr($data->jkl, 0, 1)) === 'L')
                                    <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded border border-blue-400">L</span>
                                @else
                                    <span class="bg-pink-100 text-pink-800 text-xs font-semibold px-2.5 py-0.5 rounded border border-pink-400">P</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-600">{{ strtoupper($data->lembaga) }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-3 py-1 bg-gray-100 rounded text-gray-800 font-bold border border-gray-200">{{ $data->atasan }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-3 py-1 bg-gray-100 rounded text-gray-800 font-bold border border-gray-200">{{ $data->bawahan }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if(strtoupper(substr($data->jkl, 0, 1)) === 'L' && $data->songkok !== '0')
                                    <span class="px-3 py-1 bg-gray-100 rounded text-gray-800 font-bold border border-gray-200">{{ $data->songkok }}</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-folder-open text-4xl mb-3 text-gray-300"></i>
                                    <p>Belum ada data pengisian seragam yang ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $datas->links('pagination::tailwind') }}
        </div>
    </div>
</div>
