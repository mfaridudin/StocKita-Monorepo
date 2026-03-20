<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-figtree bg-gray-50 antialiased">

    <div class="flex w-full min-h-screen overflow-hidden">

        <!-- Sidebar -->
        <x-sidebar />

        <!-- Main -->
        <div id="mainContent" class="flex flex-col flex-1 min-w-0">

            <!-- Header -->
            <x-header />

            <!-- Content -->
            <main class="flex-1 p-6 lg:p-8 overflow-y-auto w-full">
                <div class= mx-auto">
                    <div class="w-full bg-black">p</div>
                    @php
                        $i = 1;
                        while ($i <= 50) {
                            echo "Angka: $i <br>";
                            $i++; // Increment: meningkatkan $i agar perulangan berhenti
                        }
                    @endphp

                </div>
            </main>

        </div>

    </div>

</body>

</html>
