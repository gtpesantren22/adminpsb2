<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin PSB - @yield('title')</title>
    <!-- Tailwind CSS -->
    {{-- <script src="https://cdn.tailwindcss.com"></script> --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('sweetalert2::index')
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Chart.js -->
    {{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> --}}
    <!-- Custom CSS -->

</head>

<body class="bg-gray-50 text-gray-800 font-sans">

    <!-- Admin Dashboard (Hidden by default) -->

    <!-- Sidebar -->
    <div class="fixed inset-y-0 left-0 z-50 w-64 bg-gradient-to-b from-primary-blue to-blue-800 text-white shadow-xl transform transition-transform duration-300 ease-in-out"
        id="sidebar">
        <div class="flex items-center justify-between h-16 px-6 border-b border-blue-700">
            <div class="flex items-center">
                <div class="bg-white p-2 rounded-lg mr-3">
                    <i class="fas fa-user-shield text-primary-blue text-xl"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold">Admin PSB</h1>
                    <p class="text-xs text-blue-200">Sistem Administrasi</p>
                </div>
            </div>
            <button id="sidebar-close" class="lg:hidden">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <div class="px-4 py-6">
            <div class="mb-8">
                <h2 class="text-xs uppercase tracking-wider text-blue-300 font-semibold mb-4">Menu Utama</h2>
                <ul class="space-y-1">
                    {{-- Dashboard --}}
                    <li>
                        <a href="/" wire:navigate
                            class="sidebar-link flex items-center px-4 py-3 rounded-lg transition duration-150
                            {{ request()->is('/') ? 'bg-secondary-blue text-white' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}
                            ">
                            <i class="fas fa-tachometer-alt w-6 text-center mr-3"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    {{-- Menu Santri --}}
                    @php
                        $santriActive = request()->is('santri-baru*') || request()->is('santri-lama*');
                        $pendaftaranActive =
                            request()->is('pendaftaran-baru*') ||
                            request()->is('pendaftaran-lama*') ||
                            request()->is('verifikasi-pendaftaran*');
                    @endphp

                    <li>
                        <details class="group" {{ $santriActive ? 'open' : '' }}>
                            <summary
                                class="sidebar-link flex items-center px-4 py-3 rounded-lg transition cursor-pointer list-none {{ $santriActive ? 'bg-blue-800 text-white' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
                                <i class="fas fa-users w-6 text-center mr-3"></i>
                                <span class="flex-1">Data Santri</span>

                                <!-- Arrow -->
                                <i
                                    class="fas fa-chevron-down text-sm transition-transform duration-200 group-open:rotate-180">
                                </i>
                            </summary>

                            <ul class="mt-1 ml-8 space-y-1 animate-fade-in">
                                <li>
                                    <a href="/santri-baru" wire:navigate
                                        class="block px-4 py-2 rounded-lg transition {{ request()->is('santri-baru*')
                                            ? 'bg-secondary-blue text-white'
                                            : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
                                        Santri Baru
                                    </a>
                                </li>

                                <li>
                                    <a href="/santri-lama" wire:navigate
                                        class="block px-4 py-2 rounded-lg transition {{ request()->is('santri-lama*')
                                            ? 'bg-secondary-blue text-white'
                                            : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
                                        Santri Lanjutan
                                    </a>
                                </li>
                            </ul>
                        </details>
                    </li>
                    <li>
                        <details class="group" {{ $pendaftaranActive ? 'open' : '' }}>
                            <summary
                                class="sidebar-link flex items-center px-4 py-3 rounded-lg transition cursor-pointer list-none {{ $santriActive ? 'bg-blue-800 text-white' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
                                <i class="fas fa-exchange-alt w-6 text-center mr-3"></i>
                                <span class="flex-1">Pendaftaran</span>

                                <!-- Arrow -->
                                <i
                                    class="fas fa-chevron-down text-sm transition-transform duration-200 group-open:rotate-180">
                                </i>
                            </summary>

                            <ul class="mt-1 ml-8 space-y-1 animate-fade-in">
                                <li>
                                    <a href="/pendaftaran-baru" wire:navigate
                                        class="block px-4 py-2 rounded-lg transition {{ request()->is('pendaftaran-baru*')
                                            ? 'bg-secondary-blue text-white'
                                            : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
                                        Pendaftaran Baru
                                    </a>
                                </li>

                                <li>
                                    <a href="/pendaftaran-lama" wire:navigate
                                        class="block px-4 py-2 rounded-lg transition {{ request()->is('pendaftaran-lama*')
                                            ? 'bg-secondary-blue text-white'
                                            : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
                                        Pendaftaran Lanjutan
                                    </a>
                                </li>
                                <li>
                                    <a href="/verifikasi-pendaftaran" wire:navigate
                                        class="block px-4 py-2 rounded-lg transition {{ request()->is('verifikasi-pendaftaran*')
                                            ? 'bg-secondary-blue text-white'
                                            : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
                                        Verifikasi Pendaftaran
                                    </a>
                                </li>
                            </ul>
                        </details>
                    </li>


                    {{-- <li>
                        <a href="#"
                            class="sidebar-link flex items-center px-4 py-3 rounded-lg {{ request()->is('/transaksi') ? 'bg-secondary-blue text-white' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }} transition duration-150">
                            <i class="fas fa-exchange-alt w-6 text-center mr-3"></i>
                            <span>Transaksi</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" data-page="laporan"
                            class="sidebar-link flex items-center px-4 py-3 rounded-lg text-blue-100 hover:bg-blue-700 hover:text-white transition duration-150">
                            <i class="fas fa-chart-bar w-6 text-center mr-3"></i>
                            <span>Laporan</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" data-page="export"
                            class="sidebar-link flex items-center px-4 py-3 rounded-lg text-blue-100 hover:bg-blue-700 hover:text-white transition duration-150">
                            <i class="fas fa-file-export w-6 text-center mr-3"></i>
                            <span>Export Data</span>
                        </a> --}}
                    </li>
                </ul>
            </div>

            <div>
                <h2 class="text-xs uppercase tracking-wider text-blue-300 font-semibold mb-4">Pengaturan</h2>
                <ul class="space-y-2">
                    <li>
                        <a href="#"
                            class="flex items-center px-4 py-3 rounded-lg text-blue-100 hover:bg-blue-700 hover:text-white transition duration-150">
                            <i class="fas fa-cog w-6 text-center mr-3"></i>
                            <span>Pengaturan</span>
                        </a>
                    </li>
                    {{-- <li>
                        <a href="#"
                            class="flex items-center px-4 py-3 rounded-lg text-blue-100 hover:bg-blue-700 hover:text-white transition duration-150">
                            <i class="fas fa-user-circle w-6 text-center mr-3"></i>
                            <span>Profil</span>
                        </a>
                    </li> --}}
                </ul>
            </div>
        </div>

        <div class="absolute bottom-0 w-full border-t border-blue-700 p-4">
            <div class="flex items-center">
                {{-- <div class="flex-shrink-0">
                    <div class="bg-blue-600 p-2 rounded-full">
                        <i class="fas fa-user text-white text-sm"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium">Admin User</p>
                    <p class="text-xs text-blue-300">Administrator</p>
                </div> --}}
                <a href="/logout"
                    class="flex items-center px-4 py-3 rounded-lg text-blue-100 hover:bg-red-600 hover:text-white transition duration-150">
                    <i class="fas fa-sign-out-alt w-6 text-center mr-3"></i>
                    <span>Keluar</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="lg:ml-64 min-h-screen flex flex-col transition-all duration-300">
        <!-- Top Navbar -->
        <nav class="bg-white shadow-sm border-b border-gray-200">
            <div class="px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center">
                        <button id="sidebar-toggle"
                            class="lg:hidden text-gray-500 hover:text-gray-700 focus:outline-none">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <div class="ml-4 lg:ml-0">
                            <h2 class="text-xl font-semibold text-gray-800">@yield('title')</h2>
                        </div>
                    </div>

                    <div class="flex items-center space-x-4">
                        <button class="relative text-gray-500 hover:text-gray-700 focus:outline-none">
                            <i class="fas fa-bell text-xl"></i>
                            <span
                                class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">3</span>
                        </button>

                        <div class="relative">
                            <button id="user-menu-button"
                                class="flex items-center space-x-2 text-gray-700 hover:text-primary-blue focus:outline-none">
                                <div class="bg-gradient-to-r from-primary-blue to-secondary-blue p-2 rounded-full">
                                    <i class="fas fa-user text-white text-sm"></i>
                                </div>
                                <span class="hidden md:inline font-medium">Admin User</span>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>

                            <!-- Dropdown menu -->
                            <div id="user-menu"
                                class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 z-10 border border-gray-200">
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"><i
                                        class="fas fa-user mr-2"></i>Profil</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"><i
                                        class="fas fa-cog mr-2"></i>Pengaturan</a>
                                <div class="border-t border-gray-200"></div>
                                <a href="#" id="logout-top-link"
                                    class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50"><i
                                        class="fas fa-sign-out-alt mr-2"></i>Keluar</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="p-4 sm:p-6 lg:p-8">
            <!-- Dashboard Page -->
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="mt-auto bg-white border-t border-gray-200 py-4">
            <div class="px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <p class="text-sm text-gray-500 mb-2 md:mb-0">&copy; 2023 Admin Panel. All rights reserved.</p>
                    <p class="text-sm text-gray-500">Versi 1.0.0</p>
                </div>
            </div>
        </footer>
    </div>

    <script>
        // DOM Elements

        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const sidebarClose = document.getElementById('sidebar-close');
        const userMenuButton = document.getElementById('user-menu-button');
        const userMenu = document.getElementById('user-menu');
        const pageTitle = document.getElementById('page-title');

        // Chart instances
        let transactionChart, distributionChart;


        // Sidebar Toggle for Mobile
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.remove('-translate-x-full');
        });

        sidebarClose.addEventListener('click', function() {
            sidebar.classList.add('-translate-x-full');
        });

        // User Menu Toggle
        userMenuButton.addEventListener('click', function() {
            userMenu.classList.toggle('hidden');
        });

        // Close user menu when clicking outside
        document.addEventListener('click', function(event) {
            if (!userMenuButton.contains(event.target) && !userMenu.contains(event.target)) {
                userMenu.classList.add('hidden');
            }
        });

        // Initialize Charts
        function initCharts() {
            // Transaction Chart
            const transactionCtx = document.getElementById('transactionChart');
            if (transactionCtx) {
                if (transactionChart) {
                    transactionChart.destroy();
                }

                transactionChart = new Chart(transactionCtx, {
                    type: 'line',
                    data: {
                        labels: ['1 Mei', '3 Mei', '5 Mei', '7 Mei', '9 Mei', '11 Mei', '13 Mei', '15 Mei'],
                        datasets: [{
                            label: 'Jumlah Transaksi',
                            data: [65, 78, 66, 84, 105, 120, 95, 130],
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.05)'
                                }
                            },
                            x: {
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.05)'
                                }
                            }
                        }
                    }
                });
            }

            // Distribution Chart
            const distributionCtx = document.getElementById('distributionChart');
            if (distributionCtx) {
                if (distributionChart) {
                    distributionChart.destroy();
                }

                distributionChart = new Chart(distributionCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Asuransi Kesehatan', 'Biaya Pendidikan', 'Asuransi Kendaraan', 'Biaya Sewa',
                            'Lainnya'
                        ],
                        datasets: [{
                            data: [35, 25, 20, 15, 5],
                            backgroundColor: [
                                '#3b82f6',
                                '#10b981',
                                '#f59e0b',
                                '#8b5cf6',
                                '#ef4444'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
            }
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            if (window.innerWidth < 1024) {
                if (!sidebar.contains(event.target) && !sidebarToggle.contains(event.target) && !sidebar.classList
                    .contains('-translate-x-full')) {
                    sidebar.classList.add('-translate-x-full');
                }
            }
        });

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Add mobile sidebar classes
            if (window.innerWidth < 1024) {
                sidebar.classList.add('-translate-x-full');
            }
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 1024) {
                sidebar.classList.remove('-translate-x-full');
            } else {
                sidebar.classList.add('-translate-x-full');
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('swal', (data) => {

                Swal.fire({
                    title: data.title ?? '',
                    text: data.text ?? '',
                    icon: data.icon ?? 'info',
                    timer: data.timer ?? null,
                    timerProgressBar: !!data.timer,
                    showConfirmButton: !data.timer,
                    confirmButtonText: data.confirmButtonText ?? 'OK',
                }).then((result) => {

                    // redirect otomatis
                    if (data.redirect) {
                        window.location.href = data.redirect
                    }

                    // callback event (opsional)
                    if (data.emit) {
                        Livewire.dispatch(data.emit)
                    }
                })
            })
        })
    </script>

</body>

</html>
