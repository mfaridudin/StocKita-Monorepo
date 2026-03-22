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

                            <td class="px-6 py-4 text-gray-600">
                                {{ $product->category->name ?? '-' }}
                            </td>

                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">

                                    <a href="/products/{{ $product->id }}/edit"
                                        class="px-3 py-1 text-xs bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200">
                                        Edit
                                    </a>

                                    <button
                                        class="openDeleteModal px-3 py-1 text-xs bg-red-100 text-red-600 rounded-lg hover:bg-red-200"
                                        data-id="{{ $product->id }}">
                                        Hapus
                                    </button>
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

    {{-- modal delete --}}
    <div id="deleteModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">

        <div class="bg-white w-full max-w-xl rounded-xl shadow-lg p-6 relative">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold">Hapus Produk</h2>
                <button id="closeDeleteModal" class="text-gray-400 hover:text-gray-600">✕</button>
            </div>

            <p>Apakah anda yakin ingin meghapus produk ini?</p>

            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex justify-end gap-2 pt-4">
                    <button type="button" id="cancelDeleteModal"
                        class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">
                        Batal
                    </button>

                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-green-700">
                        Hapus
                    </button>
                </div>
            </form>

        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const modal = document.getElementById("deleteModal");
            const closeBtn = document.getElementById("closeDeleteModal");
            const cancelBtn = document.getElementById("cancelDeleteModal");
            const deleteForm = document.getElementById("deleteForm");

            const openBtns = document.querySelectorAll(".openDeleteModal");

            openBtns.forEach(btn => {
                btn.addEventListener("click", () => {
                    const id = btn.dataset.id;

                    deleteForm.action = `/products/${id}`;

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
                });
            });

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
