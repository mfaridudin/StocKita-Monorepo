<x-app-layout title="Detail Gudang">

    <div class="space-y-6">

        <div class="bg-white border border-slate-100 rounded-3xl p-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-12 h-12 bg-green-500 rounded-2xl flex items-center justify-center shadow-md">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h1
                                class="text-3xl font-bold bg-gradient-to-r from-gray-900 to-slate-800 bg-clip-text text-transparent">
                                {{ $warehouse->name }}
                            </h1>
                            <p class="text-sm font-medium text-slate-600 mt-1">
                                {{ $warehouse->location ?? 'Lokasi tidak tersedia' }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap gap-4">
                    <button id="openStockModal"
                        class="group relative px-6 py-3 bg-green-500 text-white font-semibold rounded-2xl shadow-md transform hover:scale-105 transition-all duration-300 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Tambah Barang
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8 pt-8 border-t border-slate-200">
                <div class="text-center p-4 bg-white/50 backdrop-blur-sm rounded-2xl border border-white/50">
                    <div class="text-2xl font-bold text-green-600">{{ $stocks->count() }}</div>
                    <div class="text-sm text-slate-600">Total Produk</div>
                </div>
                <div class="text-center p-4 bg-white/50 backdrop-blur-sm rounded-2xl border border-white/50">
                    <div class="text-2xl font-bold text-blue-600">{{ $stocks->sum('qty') }}</div>
                    <div class="text-sm text-slate-600">Total Stok</div>
                </div>
                <div class="text-center p-4 bg-white/50 backdrop-blur-sm rounded-2xl border border-white/50">
                    <div class="text-2xl font-bold text-emerald-600">Rp
                        {{ number_format($stocks->sum(function ($stock) {return $stock->qty * $stock->product->price;}),0,',','.') }}
                    </div>
                    <div class="text-sm text-slate-600">Nilai Stok</div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

            @forelse ($stocks as $stock)
                <div
                    class="bg-white border border-gray-100 rounded-2xl shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden group">

                    <div class="relative h-40 bg-gray-100 overflow-hidden">
                        <img src="{{ asset('storage/' . $stock->product->image) }}"
                            class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        <span
                            class="absolute top-3 left-2 flex items-center justify-center w-7 h-7 px-1 text-xs font-bold bg-green-500/90 backdrop-blur-sm text-white rounded-xl shadow-lg border border-white/50">
                            {{ $stock->qty }}
                        </span>

                        <span
                            class="absolute top-3 right-2 flex items-center justify-center w-7 h-7 bg-green-500/90 backdrop-blur-sm text-white rounded-xl shadow-lg border border-white/50 group-hover:bg-green-600 transition-all duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                            </svg>
                        </span>
                    </div>

                    <div class="p-4 space-y-2">

                        <h3 class="font-semibold text-gray-800 truncate">
                            {{ $stock->product->name }}
                        </h3>

                        <p class="text-xs text-gray-500">
                            {{ $stock->product->sku }}
                        </p>

                        <p class="text-sm font-bold text-green-600">
                            Rp {{ number_format($stock->product->price, 0, ',', '.') }}
                        </p>

                    </div>

                </div>
            @empty
                <div class="col-span-full text-center text-gray-400 py-10">
                    Belum ada produk di gudang ini
                </div>
            @endforelse

        </div>

    </div>


    {{-- modal  --}}
    <div id="stockModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">

        <div class="bg-white w-full max-w-xl rounded-xl shadow-lg p-6 relative">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold">Tambah Barang</h2>
                <button id="closeStockModal" class="text-gray-400 hover:text-gray-600">✕</button>
            </div>

            <form action="{{ route('stocks.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="text-sm font-medium">Produk</label>
                    <select name="product_id"
                        class="w-full mt-1 px-3 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                        <option value="">--- Pilih Produk ---</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}">
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-sm font-medium">Jumlah</label>
                    <input type="number" name="qty"
                        class="w-full mt-1 px-3 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
                <input type="hidden" name="warehouse_id" value="{{ $warehouse->id }}">

                <div class="flex justify-end gap-2 pt-3">
                    <button type="button" id="closeModal" class="px-3 py-2 text-sm bg-gray-200 rounded-lg">
                        Batal
                    </button>

                    <button type="submit" class="px-4 py-2 text-sm bg-green-600 text-white rounded-lg">
                        Simpan
                    </button>
                </div>

            </form>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const modal = document.getElementById("stockModal");
            const openBtn = document.getElementById("openStockModal");
            const closeBtn = document.getElementById("closeStockModal");
            const cancelBtn = document.getElementById("cancelStockModal");

            openBtn.onclick = () => {
                modal.classList.remove("hidden");
                modal.classList.add("flex");

                gsap.fromTo(modal.firstElementChild, {
                    scale: 0.8,
                    opacity: 0,
                    y: 20
                }, {
                    scale: 1,
                    opacity: 1,
                    y: 0,
                    duration: 0.25,
                    ease: "power3.out"
                });
            };

            function closeModal() {
                modal.classList.add("hidden");
                modal.classList.remove("flex");
            }

            closeBtn.onclick = closeModal;
            cancelBtn.onclick = closeModal;

            modal.addEventListener("click", (e) => {
                if (e.target === modal) closeModal();
            });
        });
    </script>

</x-app-layout>
