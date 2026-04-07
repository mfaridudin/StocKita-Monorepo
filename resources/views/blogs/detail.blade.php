<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>

    @vite('resources/css/app.css')
</head>

<body class="bg-gray-50 text-gray-800">

    <header class="bg-white/80 backdrop-blur border-b sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-6 py-4 flex justify-between items-center">
            <a href="/" class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center shadow-lg">
                    <img src="/image/icon/icon.png" alt="icon">
                </div>
                <div>
                    <h1
                        class="font-bold text-2xl bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent">
                        StocKita</h1>
                    <p class="text-xs text-slate-500 font-medium">Inventory Pro</p>
                </div>
            </a>
        </div>
    </header>

    <section class="bg-white border-b">
        <div class="max-w-4xl mx-auto px-6 py-16">

            <a href="/#blog" class="flex items-center gap-2 text-sm text-emerald-600 hover:underline">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 15.75 3 12m0 0 3.75-3.75M3 12h18" />
                </svg>
                Kembali ke Artikel
            </a>

            <h1 class="text-4xl md:text-5xl font-bold mt-6 leading-tight text-gray-900">
                {{ $title }}
            </h1>

            <p class="text-lg text-gray-600 mt-4">
                Panduan lengkap untuk memahami dan menggunakan fitur POS secara efektif.
            </p>

            <div class="flex items-center gap-4 text-sm text-gray-500 mt-6">
                <span>Admin</span>
                <span>•</span>
                <span>5 menit baca</span>
                <span>•</span>
                <span>{{ date('d M Y') }}</span>
            </div>

            <img src="{{ $image }}" class="w-full h-80 object-cover rounded-2xl mt-10 shadow-sm">

        </div>
    </section>

    <section class="py-16">
        <div class="max-w-4xl mx-auto px-6">

            <div
                class="prose prose-lg max-w-none 
                prose-headings:font-bold 
                prose-h2:text-2xl 
                prose-h2:mt-12 
                prose-p:text-gray-700 
                prose-li:text-gray-700
                prose-strong:text-gray-900
                prose-blockquote:border-l-emerald-500
                prose-blockquote:bg-emerald-50
                prose-blockquote:px-6 prose-blockquote:py-3 prose-blockquote:rounded-lg">
                {!! $content !!}

            </div>
        </div>
    </section>

    <section class="bg-emerald-600 text-white py-20 text-center">
        <h3 class="text-2xl font-bold">
            Siap Mengelola Bisnismu Lebih Mudah?
        </h3>
        <p class="mt-2 text-emerald-100">
            Gunakan sistem POS kami untuk meningkatkan efisiensi dan penjualan.
        </p>
        <a href="/register"
            class="inline-block mt-4 px-6 py-3 bg-white text-emerald-600 rounded-xl font-semibold hover:bg-gray-100 transition">
            Coba Sekarang
        </a>
    </section>


    <footer class="bg-gray-50 border-t">
        <div class="max-w-6xl mx-auto px-6 py-8 text-center text-sm text-gray-500">
            © {{ date('Y') }} POS App. All rights reserved.
        </div>
    </footer>

</body>

</html>
