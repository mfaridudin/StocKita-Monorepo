<x-app-layout title="Detail Produk">

    <div class="space-y-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex justify-between items-center pt-4 pb-8 border-b border-gray-200">
            <div>
                <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">{{ $product->name }}</h1>
                <p class="text-sm text-gray-500 mt-1">
                    Terakhir diupdate: {{ $product->updated_at->format('d M Y, H:i') }}
                </p>
            </div>
            <a href="{{ route('products.edit', $product->id) }}"
                class="px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg text-sm font-medium transition-colors shadow-sm">
                Edit
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="lg:col-span-1">
                <div class="rounded-xl shadow-sm border border-gray-200 h-full relative group">
                    <img src="{{ asset('storage/' . $product->image) }}"
                        class="w-full h-80 lg:h-full object-cover rounded-lg"
                        onerror="this.src='https://via.placeholder.com/300x400?text=No+Image'">

                    <div class="absolute top-3 right-3 opacity-0 group-hover:opacity-100 transition-all duration-200">
                        <a href="{{ route('products.edit', $product->id) }}#image"
                            class="bg-white/90 hover:bg-white text-gray-700 p-2 rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 flex items-center gap-1 text-sm font-medium border border-gray-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>
                            </svg>
                            Edit
                        </a>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <div class="flex items-start justify-between mb-6">
                        <div>
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-2">SKU</p>
                            <p class="text-sm text-gray-500">{{ $product->sku }}</p>
                        </div>
                        <span
                            class="px-3 py-1 bg-emerald-50 text-emerald-700 text-sm rounded-full font-medium border border-emerald-200">
                            {{ $product->category->name ?? '-' }}
                        </span>
                    </div>
                    <div class="bg-emerald-50/50 p-4 rounded-lg border border-emerald-100">
                        <p class="text-sm font-medium text-emerald-800 mb-1">Total Stok</p>
                        <p class="text-xl font-bold text-emerald-900">{{ $product->stocks->sum('qty') }} pcs</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <p class="text-sm font-medium text-gray-600 uppercase tracking-wide mb-3">Harga Satuan</p>
                    <div class="flex items-baseline gap-1">
                        <span class="text-2xl font-bold text-gray-900">Rp</span>
                        <span class="text-3xl font-bold text-gray-900 tracking-tight">
                            {{ number_format($product->price, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>

        </div>


        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-emerald-500 px-6 py-4 text-white">
                <h2 class="text-lg font-semibold flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    Distribusi Gudang
                </h2>
            </div>

            <div class="divide-y divide-gray-100">
                @forelse($product->stocks as $stock)
                    <div class="flex justify-between items-center px-6 py-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 bg-emerald-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $stock->warehouse->name }}</p>
                                <p class="text-sm text-gray-500">Gudang</p>
                            </div>
                        </div>
                        <span
                            class="px-3 py-1 bg-emerald-50 text-emerald-700 rounded-lg text-sm font-semibold border border-emerald-200">
                            {{ $stock->qty }} pcs
                        </span>
                    </div>
                @empty
                    <div class="text-center py-12 px-6">
                        <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-lg flex items-center justify-center">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                </path>
                            </svg>
                        </div>
                        <p class="text-gray-500 font-medium">Belum ada distribusi stok di gudang</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>

</x-app-layout>
