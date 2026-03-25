@if ($outStock->count() > 0)
    <div
        class="flex items-start justify-between border-b border-red-200 bg-red-50 px-4 py-3 text-red-800 hover:bg-red-100 transition-colors">
        <div class="flex items-center gap-3 text-sm flex-1 min-w-0">
            {{-- Icon & Counter --}}
            <div class="flex items-center gap-2 font-semibold bg-red-100 px-3 py-1 rounded-full">
                <div
                    class="w-5 h-5 flex items-center justify-center bg-red-500 text-white text-xs font-bold rounded-full">
                    {{ $outStock->count() }}
                </div>
                <span>Stok Kosong di Gudang</span>
            </div>

            {{-- Product Preview --}}
            <div x-data class="flex items-center gap-2 text-xs text-red-700 flex-1 min-w-0">
                @foreach ($outStock->take(2) as $index => $stock)
                    <div class="flex items-center gap-1 truncate max-w-[200px]">
                        <span class="font-medium truncate">{{ $stock->product->name }}</span>
                        <span class="opacity-70 text-[11px]">(Gudang
                            {{ Str::limit($stock->warehouse->name, 15) }})</span>
                        <a href="/warehouse/{{ $stock->warehouse_id }}"
                            class="ml-1 text-red-600 hover:text-red-800 underline underline-offset-2 text-[11px] hover:no-underline transition-all">
                            Lihat →
                        </a>
                    </div>
                @endforeach

                {{-- + Lainnya Button --}}
                @if ($outStock->count() > 2)
                    <div class="flex items-center gap-1 ml-1">
                        <span class="text-[11px] opacity-60 font-medium">+{{ $outStock->count() - 2 }} lainnya</span>
                        <button @click="$dispatch('open-modal', { name: 'outStockModal' })"
                            class="text-red-600 hover:text-red-800 text-xs font-semibold px-2 py-1 hover:bg-red-200 rounded transition-all">
                            Lihat Semua
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endif

<x-modal name="outStockModal">
    <div class="bg-gradient-to-r from-red-500 to-red-600 p-6 text-white">
        <div class="flex items-center justify-between">
            <h3 class="text-xl font-bold flex items-center gap-2">
                <i class="fas fa-times-circle"></i>
                Stok Kosong di Gudang
            </h3>
            <button @click="$dispatch('close-modal', 'outStockModal')"
                class="text-white/80 hover:text-white p-1 rounded-full hover:bg-white/20 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <div class="p-6 max-h-[500px] overflow-y-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach ($outStock as $stock)
                <div class="group border border-red-100 hover:border-red-200 p-4 rounded-xl hover:bg-red-50 transition-all cursor-pointer"
                    onclick="window.location.href='/warehouse/{{ $stock->warehouse_id }}'">
                    <div class="flex items-start justify-between mb-2">
                        <h4 class="font-semibold text-red-800 truncate">{{ $stock->product->name }}</h4>
                        <span class="px-2 py-1 bg-red-100 text-red-800 text-xs font-medium rounded-full">
                            0 Stok
                        </span>
                    </div>
                    <p class="text-sm text-red-600 mb-2">Gudang: {{ $stock->warehouse->name }}</p>
                    <div class="flex items-center gap-2 text-xs text-red-500">
                        <i class="fas fa-eye"></i>
                        <span>Lihat Detail</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-modal>
