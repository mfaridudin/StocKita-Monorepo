<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite('resources/css/app.css')
</head>

<body class="bg-white text-slate-800">

    {{-- NAVBAR SIMPLE --}}
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

    {{-- HERO --}}
    <section class="bg-gradient-to-br from-emerald-600 to-emerald-500 text-white py-20">
        <div class="max-w-5xl mx-auto text-center px-6">

            <h1 class="text-4xl md:text-5xl font-bold mb-4">
                {{ $title }}
            </h1>

            <p class="max-w-2xl mx-auto text-emerald-100">
                {{ $excerpt ?? 'Fitur ini membantu operasional bisnis jadi lebih cepat dan efisien.' }}
            </p>

            <a href="/register"
                class="inline-block mt-6 bg-white text-emerald-600 px-6 py-3 rounded-xl font-semibold hover:bg-gray-100 transition">
                Mulai Gratis
            </a>

        </div>
    </section>

    {{-- IMAGE --}}
    <div class="max-w-6xl mx-auto px-6 -mt-16">
        <img src="{{ $image }}" class="w-full h-[400px] object-cover rounded-2xl shadow-xl">
    </div>

    <section class="max-w-6xl mx-auto px-6 mt-10">
        @if (!empty($highlights))
            <section class="max-w-6xl mx-auto mt-10">
                <div class="grid md:grid-cols-3 gap-6 text-center">

                    @foreach ($highlights as $item)
                        <div class="bg-white p-6 rounded-2xl shadow-sm border hover:shadow-md transition">
                            <h3 class="text-2xl font-bold text-emerald-600">
                                {{ $item['title'] }}
                            </h3>
                            <p class="text-sm text-gray-500 mt-1">
                                {{ $item['desc'] }}
                            </p>
                        </div>
                    @endforeach

                </div>
            </section>
        @endif
    </section>

    {{-- DESKRIPSI --}}
    <section class="max-w-6xl mx-auto px-6 py-16">

        <div class="grid md:grid-cols-2 gap-10 items-center">

            <div>
                <h2 class="text-2xl font-bold mb-4">
                    Tentang Fitur Ini
                </h2>

                <p class="text-gray-600 leading-relaxed text-lg">
                    {{ $description }}
                </p>
            </div>

            <div class="bg-emerald-50 p-6 rounded-2xl">
                <ul class="space-y-3 text-gray-700 text-sm">
                    @foreach ($benefits as $benefit)
                        <li>✔ {{ $benefit }}</li>
                    @endforeach
                </ul>
            </div>

        </div>

    </section>

    {{-- STEP --}}
    <section class="bg-gray-50 py-16">
        <div class="max-w-6xl mx-auto px-6">

            <h2 class="text-2xl font-bold text-center mb-10">
                Cara Menggunakan
            </h2>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">

                @foreach ($steps as $index => $step)
                    <div
                        class="bg-white p-6 rounded-2xl border hover:shadow-lg transition group relative overflow-hidden">

                        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-emerald-500 to-teal-500">
                        </div>

                        <div
                            class="w-10 h-10 flex items-center justify-center rounded-xl bg-emerald-100 text-emerald-600 font-bold mb-3">
                            {{ $index + 1 }}
                        </div>

                        <h3 class="font-semibold text-lg mb-2">
                            Langkah {{ $index + 1 }}
                        </h3>

                        <p class="text-sm text-gray-600 leading-relaxed">
                            {{ $step }}
                        </p>
                    </div>
                @endforeach

            </div>

        </div>
    </section>

    {{-- BENEFIT --}}
    <section class="py-16">
        <div class="max-w-6xl mx-auto px-6">

            <h2 class="text-2xl font-bold text-center mb-10">
                Keunggulan Fitur
            </h2>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">

                @foreach ($benefits as $benefit)
                    <div
                        class="bg-white p-6 rounded-2xl border hover:shadow-lg transition group relative overflow-hidden">

                        <div class="absolute top-0 right-0 w-20 h-20 bg-emerald-100 rounded-full blur-2xl opacity-50">
                        </div>

                        <div class="text-black text-xl mb-3">
                            ✔
                        </div>

                        <h3 class="font-semibold text-lg mb-2">
                            {{ $benefit }}
                        </h3>

                        <p class="text-sm text-gray-600">
                            Membantu meningkatkan efisiensi dan performa bisnis kamu.
                        </p>
                    </div>
                @endforeach

            </div>

        </div>
    </section>

    @if (!empty($use_cases))
        <section class="bg-gray-50 py-16">
            <div class="max-w-6xl mx-auto px-6">

                <h2 class="text-2xl font-bold text-center mb-10">
                    Cocok Digunakan Untuk
                </h2>

                <div class="grid md:grid-cols-3 gap-6">

                    @foreach ($use_cases as $case)
                        <div class="bg-white p-6 rounded-2xl border text-center hover:shadow-lg transition">
                            <p class="font-semibold text-gray-800">
                                {{ $case }}
                            </p>
                        </div>
                    @endforeach

                </div>

            </div>
        </section>
    @endif

    @if (!empty($faqs))
        <section class="py-16">
            <div class="max-w-6xl px-6 mx-auto">

                <h2 class="text-2xl font-bold text-center mb-10">
                    Pertanyaan Umum
                </h2>

                <div class="space-y-4">

                    @foreach ($faqs as $faq)
                        <details class="bg-white p-5 rounded-xl border group">
                            <summary class="cursor-pointer font-semibold text-gray-800">
                                {{ $faq['q'] }}
                            </summary>

                            <p class="text-sm text-gray-600 mt-2">
                                {{ $faq['a'] }}
                            </p>
                        </details>
                    @endforeach

                </div>

            </div>
        </section>
    @endif

    {{-- CTA --}}
    <section class="bg-emerald-600 text-white py-20 text-center">
        <h2 class="text-3xl font-bold mb-4">
            Siap Menggunakan Fitur Ini?
        </h2>

        <p class="mb-6 text-emerald-100">
            Mulai sekarang dan rasakan kemudahannya.
        </p>

        <a href="/register"
            class="bg-white text-emerald-600 px-6 py-3 rounded-xl font-semibold hover:bg-gray-100 transition">
            Mulai Gratis
        </a>
    </section>

    {{-- FOOTER --}}
    <footer class="border-t py-6 text-center text-sm text-gray-500">
        © {{ date('Y') }} StocKita. All rights reserved.
    </footer>

</body>

</html>
