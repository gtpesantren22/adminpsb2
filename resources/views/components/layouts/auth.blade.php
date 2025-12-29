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

    @yield('content')

    @livewireScripts
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
