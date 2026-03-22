<x-app-layout title="Kategori">
    <div class="space-y-4">

        <div class="flex justify-between">
            <h1 class="text-lg font-semibold">Kategori</h1>
            <button id="openCategoryModal" class="px-4 py-2 bg-green-600 text-white rounded-lg">
                + Tambah
            </button>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="p-3 text-left">Nama</th>
                        <th class="p-3 text-left">Slug</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                    @forelse ($categories as $cat)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="p-3">{{ $cat->name }}</td>
                            <td class="p-3 text-gray-500">{{ $cat->slug }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">

                                    <a href="/categories/{{ $cat->id }}/edit"
                                        class="px-3 py-1 text-xs bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200">
                                        Edit
                                    </a>

                                    <form action="/categories/{{ $cat->id }}" method="POST"
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
                                Belum ada kategori
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- modal add --}}
    <div id="categoryModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">

        <div class="bg-white w-full max-w-xl rounded-xl shadow-lg p-6 relative">

            <!-- Header -->
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold">Tambah Kategori</h2>
                <button id="closeCategoryModal" class="text-gray-400 hover:text-gray-600">✕</button>
            </div>

            <!-- Form -->
            <form action="/categories" method="POST" class="space-y-4">
                @csrf
                @method('POST')

                <div>
                    <label class="text-sm font-medium">Nama</label>
                    <input type="text" name="name"
                        class="w-full mt-1 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                </div>

                <div class="flex justify-end gap-2 pt-4">
                    <button type="button" id="cancelCategoryModal"
                        class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">
                        Batal
                    </button>

                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        Simpan
                    </button>
                </div>
            </form>

        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const modal = document.getElementById("categoryModal");
            const openBtn = document.getElementById("openCategoryModal");
            const closeBtn = document.getElementById("closeCategoryModal");
            const cancelBtn = document.getElementById("cancelCategoryModal");

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
