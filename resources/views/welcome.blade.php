<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StocKita - Inventory & POS Modern</title>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
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

            <div class="flex items-center gap-4">
                <div class="relative hidden lg:block group">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6 absolute left-4 top-1/2 -translate-y-1/2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>

                    <input id="searchInput" type="text" placeholder="Cari..."
                        class="pl-12 pr-4 py-3 w-72 bg-slate-100 border border-green-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:bg-white transition-all duration-200 shadow-sm group-hover:shadow-md">

                    <button id="clearSearch"
                        class="absolute right-3 top-1/2 -translate-y-1/2 w-7 h-7 flex items-center justify-center hidden transition">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <div id="searchResults"
                        class="absolute mt-2 w-full bg-white shadow-lg rounded-xl max-h-60 overflow-y-auto hidden z-50">
                    </div>
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

    {{-- blog --}}
    <x-blogs />

    {{-- subscribe --}}
    <x-offer />

    {{-- footer --}}
    <x-footer />

    <x-cookie-consent />

    <script>
        const input = document.getElementById('searchInput');
        const clearBtn = document.getElementById('clearSearch');
        const resultsBox = document.getElementById('searchResults');

        input.addEventListener('input', function() {
            if (this.value.length > 0) {
                clearBtn.classList.remove('hidden');
            } else {
                clearBtn.classList.add('hidden');
            }
        });

        clearBtn.addEventListener('click', function() {
            input.value = '';
            resultsBox.innerHTML = '';
            resultsBox.classList.add('hidden');

            removeHighlights();

            clearBtn.classList.add('hidden');
            input.focus();

        });

        input.addEventListener('input', function() {
            const keyword = this.value.toLowerCase();

            resultsBox.innerHTML = '';
            resultsBox.classList.add('hidden');

            removeHighlights();

            if (keyword.length < 2) return;

            let results = [];
            const elements = document.querySelectorAll('h1, h2, h3, p, span, a, li');

            elements.forEach(el => {
                const text = el.innerText.toLowerCase();

                if (text.includes(keyword)) {
                    results.push({
                        element: el,
                        text: el.innerText
                    });

                    highlightWord(el, keyword);
                }
            });

            if (results.length === 0) {
                resultsBox.innerHTML = `<div class="p-3 text-sm text-gray-500">Tidak ditemukan</div>`;
            } else {
                results.slice(0, 5).forEach((res, index) => {
                    const item = document.createElement('div');
                    item.className = 'p-3 text-sm hover:bg-gray-100 cursor-pointer';

                    item.innerHTML = createSnippet(res.text, keyword)
                        .replace(new RegExp(`(${keyword})`, 'gi'),
                            '<span class="bg-yellow-200 rounded">$1</span>');

                    item.addEventListener('click', () => {
                        res.element.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    });

                    resultsBox.appendChild(item);
                });
            }

            resultsBox.classList.remove('hidden');
        });

        function highlightWord(element, keyword) {
            const walker = document.createTreeWalker(element, NodeFilter.SHOW_TEXT, null, false);

            const nodes = [];

            while (walker.nextNode()) {
                nodes.push(walker.currentNode);
            }

            nodes.forEach(node => {
                const text = node.nodeValue;
                const lower = text.toLowerCase();

                if (lower.includes(keyword)) {
                    const span = document.createElement('span');
                    const regex = new RegExp(`(${keyword})`, 'gi');

                    span.innerHTML = text.replace(regex,
                        `<mark class="bg-yellow-200/50 rounded">$1</mark>`);

                    node.replaceWith(span);
                }
            });
        }

        function removeHighlights() {
            document.querySelectorAll('mark').forEach(mark => {
                const parent = mark.parentNode;
                parent.replaceWith(document.createTextNode(parent.innerText));
            });
        }

        function createSnippet(text, keyword) {
            const lower = text.toLowerCase();
            const index = lower.indexOf(keyword);

            if (index === -1) return text.substring(0, 50) + '...';

            const start = Math.max(index - 20, 0);
            const end = Math.min(index + keyword.length + 20, text.length);

            let snippet = text.substring(start, end);

            if (start > 0) snippet = '...' + snippet;
            if (end < text.length) snippet = snippet + '...';

            return snippet;
        }
    </script>
</body>

</html>
