<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Privacy Policy - StocKita</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-white text-gray-800">

    <header class="w-full border-b bg-white sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
            <a href="/" class="font-bold text-lg text-emerald-600">StocKita</a>

            <nav class="flex items-center gap-6 text-sm">
                <a href="/privacy" class="text-gray-600 hover:text-emerald-600">Privacy</a>
                <a href="/dmca" class="text-gray-600 hover:text-emerald-600">DMCA & Hak Cipta</a>
                <a href="/terms" class="text-gray-600 hover:text-emerald-600"> Syarat & Ketentuan</a>
            </nav>
        </div>
    </header>

    {{ $slot }}

    <footer class="border-t py-6 text-center text-sm text-gray-500">
        © 2026 StocKita. All rights reserved.
    </footer>

</body>

</html>
