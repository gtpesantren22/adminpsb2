<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Pengisian Seragam</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('sweetalert2::index')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f3f4f6;
        }
    </style>
    @livewireStyles
</head>

<body class="text-gray-800 font-sans antialiased min-h-screen pb-10">

    <div class="max-w-6xl mx-auto w-full pt-8 px-4">
        {{ $slot }}
    </div>

    @livewireScripts
</body>

</html>
