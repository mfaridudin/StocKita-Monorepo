<x-app-layout>
    <div class="min-h-screen">
        <div class="bg-white/80 backdrop-blur-md shadow-sm border-b border-green-200 sticky top-0 z-40">
            <div class="max-w-7xl mx-auto px-6 py-6 lg:px-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1
                            class="text-3xl flex items-center gap-3 font-bold bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#0C9482" class="size-8">
                                <path
                                    d="M5.223 2.25c-.497 0-.974.198-1.325.55l-1.3 1.298A3.75 3.75 0 0 0 7.5 9.75c.627.47 1.406.75 2.25.75.844 0 1.624-.28 2.25-.75.626.47 1.406.75 2.25.75.844 0 1.623-.28 2.25-.75a3.75 3.75 0 0 0 4.902-5.652l-1.3-1.299a1.875 1.875 0 0 0-1.325-.549H5.223Z" />
                                <path fill-rule="evenodd"
                                    d="M3 20.25v-8.755c1.42.674 3.08.673 4.5 0A5.234 5.234 0 0 0 9.75 12c.804 0 1.568-.182 2.25-.506a5.234 5.234 0 0 0 2.25.506c.804 0 1.567-.182 2.25-.506 1.42.674 3.08.675 4.5.001v8.755h.75a.75.75 0 0 1 0 1.5H2.25a.75.75 0 0 1 0-1.5H3Zm3-6a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75v3a.75.75 0 0 1-.75.75h-3a.75.75 0 0 1-.75-.75v-3Zm8.25-.75a.75.75 0 0 0-.75.75v5.25c0 .414.336.75.75.75h3a.75.75 0 0 0 .75-.75v-5.25a.75.75 0 0 0-.75-.75h-3Z"
                                    clip-rule="evenodd" />
                            </svg>

                            Dashboard Toko, @php
                                $user = Auth::user();
                            @endphp
                            {{ $user->store->name }}
                        </h1>
                        <p class="text-emerald-700 mt-1 font-medium">Selamat datang kembali!</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mx-auto py-8">

            {{-- statis --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                {{-- total order --}}
                <div
                    class="bg-white/70 backdrop-blur-md p-8 rounded-2xl shadow-lg hover:shadow-xl transition-all border border-green-200 hover:border-green-500 hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-emerald-700 uppercase tracking-wide">Total Order</p>
                            <p class="text-3xl font-bold text-emerald-900 mt-1">
                                {{ number_format($totalOrder) }}
                            </p>
                            @php
                                $isUp = $percent >= 0;
                            @endphp

                            <p
                                class="font-semibold mt-1 flex items-center 
                                 {{ $isUp ? 'text-emerald-600' : 'text-red-600' }}">
                                <i
                                    class="fas {{ $isUp ? 'fa-arrow-up text-green-500' : 'fa-arrow-down text-red-500' }} mr-1"></i>

                                {{ abs(number_format($percent, 1)) }}% dari kemarin
                            </p>
                        </div>
                        <div class="w-16 h-16 bg-green-500 rounded-2xl flex items-center justify-center">
                            <i class="fas fa-shopping-cart text-2xl text-white"></i>
                        </div>
                    </div>
                </div>

                {{-- revenue --}}
                <div
                    class="bg-white/70 backdrop-blur-md p-8 rounded-2xl shadow-lg hover:shadow-xl transition-all border border-green-200 hover:border-green-500 hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-emerald-700 uppercase tracking-wide">
                                Revenue Hari Ini
                            </p>

                            <p class="text-3xl font-bold text-emerald-900 mt-1">
                                {{ 'Rp ' . number_format($todayRevenue, 0, ',', '.') }}
                            </p>

                            @php
                                $isUp = $percentRevenue >= 0;
                            @endphp

                            <p
                                class="font-semibold mt-1 flex items-center 
                                {{ $isUp ? 'text-emerald-600' : 'text-red-600' }}">

                                <i
                                    class="fas {{ $isUp ? 'fa-arrow-up text-green-500' : 'fa-arrow-down text-red-500' }} mr-1"></i>

                                {{ abs(number_format($percentRevenue, 1)) }}%
                            </p>
                        </div>

                        <div class="w-16 h-16 bg-green-500 rounded-2xl flex items-center justify-center">
                            <i class="fas fa-money-bill-wave text-2xl text-white"></i>
                        </div>
                    </div>
                </div>

                {{-- stock ready --}}
                <div
                    class="bg-white/70 backdrop-blur-md p-8 rounded-2xl shadow-lg hover:shadow-xl transition-all border border-green-200 hover:border-green-500 hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-emerald-700 uppercase tracking-wide">Stock Siap Jual</p>
                            <p class="text-3xl font-bold text-emerald-900 mt-1">
                                {{ number_format($totalStock) }}
                            </p>

                            @php
                                $isUp = $percentStock >= 0;
                            @endphp

                            <p
                                class="font-semibold mt-1 flex items-center 
                                {{ $isUp ? 'text-emerald-600' : 'text-orange-600' }}">

                                <i
                                    class="fas {{ $isUp ? 'fa-arrow-up text-green-500' : 'fa-arrow-down text-orange-500' }} mr-1"></i>

                                {{ abs(number_format($percentStock, 1)) }}%
                            </p>
                        </div>
                        <div class="w-16 h-16 bg-green-500 rounded-2xl flex items-center justify-center">
                            <i class="fas fa-boxes text-2xl text-white"></i>
                        </div>
                    </div>
                </div>

                {{-- low stock --}}
                <div
                    class="bg-white/70 backdrop-blur-md p-8 rounded-2xl shadow-lg hover:shadow-xl transition-all border-2 {{ $isUp ? 'border-green-200' : 'border-amber-200' }} hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-amber-700 uppercase tracking-wide">Low Stock</p>
                            <p class="text-3xl font-bold text-amber-900 mt-1">
                                {{ $lowStockCount }}
                            </p>

                            @php
                                $isUp = $percentLow >= 0;
                            @endphp

                            <p
                                class="font-bold mt-1 text-lg flex items-center 
                                {{ $isUp ? 'text-green-500' : 'text-amber-600' }}">

                                {!! $lowStockCount > 0
                                    ? '<i class="fas fa-exclamation-triangle text-amber-500 mr-1"></i> Periksa!'
                                    : '<i class="fas fa-exclamation-triangle text-green-500 mr-1"></i> Aman' !!}
                            </p>
                        </div>
                        <div
                            class="w-16 h-16 {{ $isUp ? 'bg-green-500' : 'bg-yellow-400' }}  rounded-2xl flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-2xl text-white"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
                <div class="lg:col-span-3">
                    <div class="bg-white/70 backdrop-blur-md p-8 rounded-3xl shadow-xl border border-green-200">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-2xl font-bold text-emerald-900 flex items-center gap-3">
                                <i class="fas fa-chart-line"></i>
                                Trend Revenue & Order
                            </h3>
                            <form method="GET">
                                <div class="relative w-full sm:w-48">
                                    <select name="range" onchange="this.form.submit()"
                                        class="w-full appearance-none px-4 py-2 pr-10 border border-gray-200 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                        <option value="7" {{ $range == 7 ? 'selected' : '' }}>7 Hari Terakhir
                                        </option>
                                        <option value="30" {{ $range == 30 ? 'selected' : '' }}>30 Hari</option>
                                        <option value="90" {{ $range == 90 ? 'selected' : '' }}>3 Bulan</option>
                                    </select>

                                    <div
                                        class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-4">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                        </svg>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <canvas id="revenueChart" height="100"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- chart.JS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('revenueChart');
            if (ctx) {
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: @json($chartLabels),
                        datasets: [{
                                label: 'Revenue',
                                data: @json($chartRevenue),
                                borderWidth: 3,
                                tension: 0.4
                            },
                            {
                                label: 'Order',
                                data: @json($chartOrders),
                                borderWidth: 3,
                                tension: 0.4
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        interaction: {
                            mode: 'index',
                            intersect: false
                        },
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        if (context.dataset.label === 'Revenue') {
                                            return 'Rp ' + context.raw.toLocaleString('id-ID');
                                        }
                                        return context.raw + ' order';
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>

    <style>
        @import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');
    </style>
</x-app-layout>
