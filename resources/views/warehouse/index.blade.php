<x-app-layout title="Gudang">
    <div class="space-y-4">

        <div class="flex justify-between">
            <h1 class="text-lg font-semibold">Gudang</h1>
            <button id="openWarehouseModal" class="px-4 py-2 bg-green-600 text-white rounded-lg">
                + Tambah
            </button>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="p-3 text-left">Kode</th>
                        <th class="p-3 text-left">Nama</th>
                        <th class="p-3 text-left">Lokasi</th>
                        <th class="p-3 text-left">Deskripsi</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                    @forelse ($warehouses as $w)
                        <tr class="hover:bg-gray-50 transition">

                            <td class="p-3 font-medium">{{ $w->code }}</td>
                            <td class="p-3">{{ $w->name }}</td>
                            <td class="p-3 text-gray-500">{{ $w->location }}</td>
                            <td class="p-3 text-gray-500">{{ $w->description }}</td>

                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">

                                    <a href="/products/{{ $w->id }}/edit"
                                        class="px-3 py-1 text-xs bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200">
                                        Edit
                                    </a>

                                    <form action="/products/{{ $w->id }}" method="POST"
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
                                Belum ada gudang
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- modal add --}}
    <div id="warehouseModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">

        <div class="bg-white w-full max-w-xl rounded-xl shadow-lg p-6 relative">

            <!-- Header -->
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold">Tambah Gudang</h2>
                <button id="closeWarehouseModal" class="text-gray-400 hover:text-gray-600">✕</button>
            </div>

            <!-- Form -->
            <form action="/warehouse" method="POST" class="space-y-4">
                @csrf
                @method('POST')

                <div>
                    <label class="text-sm font-medium">Nama Gudang</label>
                    <input type="text" name="name"
                        class="w-full mt-1 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                </div>

                <div>
                    <label class="text-sm font-medium">Lokasi</label>
                    <input type="text" name="location"
                        class="w-full mt-1 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                </div>

                <div>
                    <label class="text-sm font-medium">Deskripsi</label>
                    <textarea name="description" class="w-full mt-1 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500"></textarea>
                </div>

                <div class="flex justify-end gap-2 pt-4">
                    <button type="button" id="cancelWarehouseModal"
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
            const modal = document.getElementById("warehouseModal");
            const openBtn = document.getElementById("openWarehouseModal");
            const closeBtn = document.getElementById("closeWarehouseModal");
            const cancelBtn = document.getElementById("cancelWarehouseModal");

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
