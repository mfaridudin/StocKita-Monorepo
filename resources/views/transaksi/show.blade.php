<x-app-layout title="Detail Transaksi">

    <div class="max-w-3xl mx-auto bg-white p-6 rounded-xl shadow">

        <h1 class="text-lg font-semibold mb-4">
            {{ $transaction->invoice_code }}
        </h1>

        <p class="text-sm text-gray-500 mb-4">
            {{ $transaction->created_at->format('d M Y H:i') }}
        </p>

        <!-- ITEMS -->
        <div class="space-y-2">
            @foreach ($transaction->items as $item)
                <div class="flex justify-between border-b pb-2">
                    <span>
                        {{ $item->product->name }} x{{ $item->qty }}
                    </span>
                    <span>
                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                    </span>
                </div>
            @endforeach
        </div>

        <!-- TOTAL -->
        <div class="flex justify-between font-bold text-lg mt-4">
            <span>Total</span>
            <span>
                Rp {{ number_format($transaction->total, 0, ',', '.') }}
            </span>
        </div>

        @if ($transaction->receipt)
            <img src="{{ asset('storage/' . $transaction->receipt) }}" class="w-full max-w-sm mt-4 border rounded">
        @endif

    </div>

</x-app-layout>
