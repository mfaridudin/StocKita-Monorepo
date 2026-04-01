<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StocKita - Inventory & POS Modern</title>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'bounce-slow': 'bounce 3s infinite',
                    }
                }
            }
        }
    </script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        .hero-slide {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .float {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }
    </style>
</head>

<body class="bg-gradient-to-br from-slate-50 to-emerald-50 overflow-x-hidden">

    {{-- header --}}
    <header id="header"
        class="fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-xl shadow-lg border-b border-slate-200/50">
        <nav class="max-w-7xl mx-auto py-4 flex items-center justify-between">
            {{-- logo --}}
            <div class="flex items-center gap-3">
                <div
                    class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-store text-2xl text-white"></i>
                </div>
                <div>
                    <h1
                        class="font-bold text-2xl bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent">
                        StocKita</h1>
                    <p class="text-xs text-slate-500 font-medium">Inventory Pro</p>
                </div>
            </div>

            {{-- nav --}}
            <ul class="hidden md:flex items-center gap-8 mx-auto">
                <li>
                    <a href="#home"
                        class="text-slate-700 hover:text-emerald-600 font-medium px-3 py-2 rounded-lg transition-all group hover:bg-emerald-50">Home
                    </a>
                </li>
                <li>
                    <a href="#features"
                        class="text-slate-700 hover:text-emerald-600 font-medium px-3 py-2 rounded-lg transition-all group hover:bg-emerald-50">Fitur
                    </a>
                </li>

                <li>
                    <a href="#blog"
                        class="text-slate-700 hover:text-emerald-600 font-medium px-3 py-2 rounded-lg transition-all group hover:bg-emerald-50">Panduan
                    </a>
                </li>
                <li>
                    <a href="#offer"
                        class="text-slate-700 hover:text-emerald-600 font-medium px-3 py-2 rounded-lg transition-all group hover:bg-emerald-50">Harga
                    </a>
                </li>
            </ul>

            <!-- RIGHT SIDE -->
            <div class="flex items-center gap-4">
                <!-- SEARCH -->
                <div class="relative hidden lg:block group">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor"
                        class="size-6 absolute left-4 top-1/2 -translate-y-1/2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>

                    <input type="text" placeholder="Seacrh"
                        class="pl-12 pr-4 py-3 w-72 bg-slate-100/50 border borderrounded-2xl">
                </div>
                <div class="flex items-center gap-4">
                    <a href="/register"
                        class="px-6 py-3 bg-emerald-500 hover:bg-emerald-600 text-white font-semibold rounded-2xl shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5 hidden sm:block">
                        Daftar
                    </a>
                    <a href="/login"
                        class="px-6 py-3 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded-2xl shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5 hidden sm:block">
                        Masuk
                    </a>
                </div>
                <button
                    class="p-3 text-slate-600 hover:text-emerald-600 hover:bg-emerald-50 rounded-xl transition-all lg:hidden">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </nav>
    </header>

    <x-carousel />

    <x-sponsorship />


    {{-- features  --}}
    <x-features />

    <!-- BLOG SECTION -->
    <x-blogs />


    {{-- subscribe --}}
    <x-offer />

    {{-- footer --}}
    <x-footer />

    <x-cookie-consent />
</body>

</html>
