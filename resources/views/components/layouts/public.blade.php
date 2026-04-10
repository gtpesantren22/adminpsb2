<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi PSB - {{ $title ?? 'PSB Pesantren' }}</title>
    <!-- Tailwind CSS (Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('sweetalert2::index')
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        body {
            background-color: #f3f4f6;
            background-image: url('https://www.transparenttextures.com/patterns/arabesque.png');
        }
    </style>
    @livewireStyles
</head>

<body class="text-gray-800 font-sans antialiased min-h-screen pb-10">

    <div class="max-w-md mx-auto w-full pt-4 px-4 sm:px-0">
        {{ $slot }}
    </div>

    @livewireScripts
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('swal', (data) => {
                Swal.fire({
                    title: data[0].title ?? '',
                    text: data[0].text ?? '',
                    icon: data[0].icon ?? 'info',
                    timer: data[0].timer ?? null,
                    timerProgressBar: !!data[0].timer,
                    showConfirmButton: !data[0].timer,
                    confirmButtonText: data[0].confirmButtonText ?? 'OK',
                }).then((result) => {
                    if (data[0].redirect) {
                        window.location.href = data[0].redirect
                    }
                })
            })
        })
    </script>
</body>

</html>
