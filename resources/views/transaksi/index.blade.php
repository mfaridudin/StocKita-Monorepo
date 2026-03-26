<x-app-layout title="Transaksi">

<div class="space-y-6">

    <!-- HEADER -->
    <div class="flex justify-between items-center">
        <h1 class="text-lg font-semibold">Daftar Transaksi</h1>

        <a href="/transactions/create"
            class="px-4 py-2 bg-green-600 text-white text-sm rounded-lg shadow hover:bg-green-700">
            + Transaksi Baru
        </a>
    </div>

    <!-- LIST -->
    <div class="grid md:grid-cols-2 gap-4">

        @forelse ($transactions as $trx)
            <div class="bg-white p-5 rounded-xl shadow-sm border hover:shadow-md transition">

                <!-- TOP -->
                <div class="flex justify-between items-center mb-3">
                    <div>
                        <p class="font-semibold text-gray-800">
                            {{ $trx->invoice_code }}
                        </p>
                        <p class="text-xs text-gray-500">
                            {{ $trx->created_at->format('d M Y H:i') }}
                        </p>
                    </div>

                    <span class="text-xs px-3 py-1 rounded-full
                        {{ $trx->status == 'paid' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                        {{ strtoupper($trx->status) }}
                    </span>
                </div>

                <!-- BODY -->
                <div class="text-sm text-gray-600 space-y-1">

                    <p>
                        👤 {{ $trx->customer->name ?? 'Customer Umum' }}
                    </p>

                    <p>
                        🏭 {{ $trx->warehouse->name ?? '-' }}
                    </p>

                    <p class="font-semibold text-gray-800 pt-2">
                        Rp {{ number_format($trx->total, 0, ',', '.') }}
                    </p>
                </div>

                <!-- ACTION -->
                <div class="flex justify-end gap-2 mt-4">

                    <a href="/transactions/{{ $trx->id }}"
                        class="px-3 py-1 text-xs bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200">
                        Detail
                    </a>

                </div>

            </div>
        @empty
            <div class="col-span-2 text-center py-10 text-gray-400">
                Belum ada transaksi
            </div>
        @endforelse

    </div>

    <!-- PAGINATION -->
    <div>
        {{ $transactions->links() }}
    </div>

</div>

</x-app-layout>