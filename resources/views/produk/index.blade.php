<x-app-layout title="Produk">
    <div class="space-y-6">

        <div class="flex items-center justify-between">

            <a href="/products/create"
                class="px-4 py-2 bg-green-600 text-white text-sm rounded-lg shadow hover:bg-green-700 transition">
                + Tambah Produk
            </a>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-3">Produk</th>
                        <th class="px-6 py-3">Harga</th>
                        <th class="px-6 py-3">Stok</th>
                        <th class="px-6 py-3">Kategori</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                    @forelse ($products as $product)
                        <tr class="hover:bg-gray-50 transition">

                            <td class="px-6 py-4 flex items-center gap-4">
                                <img src="{{ asset('storage/' . $product->image) }}"
                                    class="w-12 h-12 object-cover rounded-lg border"
                                    onerror="this.src='https://via.placeholder.com/100'">

                                <div>
                                    <p class="font-semibold text-gray-800">
                                        {{ $product->name }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        SKU: {{ $product->sku }}
                                    </p>
                                </div>
                            </td>

                            <td class="px-6 py-4 font-medium text-gray-700">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </td>

                            <td class="px-6 py-4">
                                <span
                                    class="px-3 py-1 text-xs rounded-full
                                {{ $product->stock > 10 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600' }}">
                                    {{ $product->stock }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-gray-600">
                                {{ $product->category->name ?? '-' }}
                            </td>

                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">

                                    <a href="/products/{{ $product->id }}/edit"
                                        class="px-3 py-1 text-xs bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200">
                                        Edit
                                    </a>

                                    <form action="/products/{{ $product->id }}" method="POST"
                                        onsubmit="return confirm('Yakin hapus produk?')">
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            class="px-3 py-1 text-xs bg-red-100 text-red-600 rounded-lg hover:bg-red-200">
                                            Hapus
                                        </button>
                                    </form>

                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-10 text-gray-400">
                                Belum ada produk
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</x-app-layout>
