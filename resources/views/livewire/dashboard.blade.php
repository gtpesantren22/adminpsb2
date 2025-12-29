<div class="page-content">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-secondary-blue shadow-hover">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Jumlah Santri</p>
                    <p class="text-2xl font-bold text-gray-800 mt-2">{{ $jml_santri }}</p>
                    <p class="text-xs text-secondary-blue mt-1"><i class="fas fa-user mr-1"></i>Santri baru dan lanjutan
                    </p>
                </div>
                <div class="bg-blue-50 p-3 rounded-lg">
                    <i class="fas fa-users text-secondary-blue text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500 shadow-hover">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total pendaftaran</p>
                    <p class="text-2xl font-bold text-gray-800 mt-2">Rp. {{ number_format($nominal_pendaftaran) }}</p>
                    <p class="text-xs text-green-500 mt-1"></i>dari
                        {{ $jml_pendaftaran }} transaksi</p>
                </div>
                <div class="bg-green-50 p-3 rounded-lg">
                    <i class="fas fa-wallet text-green-500 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-yellow-500 shadow-hover">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-500">Registrasi Ulang</p>
                    <p class="text-2xl font-bold text-gray-800 mt-2">Rp. 0</p>
                    <p class="text-xs text-yellow-500 mt-1">Pembayaran Registrasi Ulang</p>
                </div>
                <div class="bg-yellow-50 p-3 rounded-lg">
                    <i class="fas fa-wallet text-yellow-500 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-red-500 shadow-hover">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-500">Gagal</p>
                    <p class="text-2xl font-bold text-gray-800 mt-2">42</p>
                    <p class="text-xs text-green-500 mt-1"><i class="fas fa-arrow-up mr-1"></i>1.7% dari
                        bulan lalu</p>
                </div>
                <div class="bg-red-50 p-3 rounded-lg">
                    <i class="fas fa-times-circle text-red-500 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Transaction Chart -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-md p-6 shadow-hover">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-semibold text-gray-800">Statistik Transaksi Bulan Ini</h3>
                <select
                    class="border border-gray-300 rounded-lg px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-secondary-blue">
                    <option>Bulan Ini</option>
                    <option>Bulan Lalu</option>
                    <option>Tahun Ini</option>
                </select>
            </div>
            <div class="h-64">
                <canvas id="transactionChart"></canvas>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-xl shadow-md p-6 shadow-hover">
            <h3 class="text-lg font-semibold text-gray-800 mb-6">Aktivitas Terbaru</h3>
            <div class="space-y-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0 bg-blue-100 p-2 rounded-lg">
                        <i class="fas fa-user-plus text-secondary-blue"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-800">Data baru ditambahkan</p>
                        <p class="text-xs text-gray-500">2 menit yang lalu</p>
                    </div>
                </div>

                <div class="flex items-start">
                    <div class="flex-shrink-0 bg-green-100 p-2 rounded-lg">
                        <i class="fas fa-check text-green-600"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-800">Pembayaran berhasil</p>
                        <p class="text-xs text-gray-500">15 menit yang lalu</p>
                    </div>
                </div>

                <div class="flex items-start">
                    <div class="flex-shrink-0 bg-yellow-100 p-2 rounded-lg">
                        <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-800">Pembayaran tertunda</p>
                        <p class="text-xs text-gray-500">1 jam yang lalu</p>
                    </div>
                </div>

                <div class="flex items-start">
                    <div class="flex-shrink-0 bg-purple-100 p-2 rounded-lg">
                        <i class="fas fa-file-export text-purple-600"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-800">Laporan diexport</p>
                        <p class="text-xs text-gray-500">3 jam yang lalu</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
