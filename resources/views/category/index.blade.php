<x-app-layout title="Kategori">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if ($message = session('success') ?? (session('error') ?? (session('warning') ?? session('info'))))
        <script>
            let type =
                "{{ session('success') ? 'success' : (session('error') ? 'error' : (session('warning') ? 'warning' : 'info')) }}";
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: type,
                title: "{{ $message }}",
                showConfirmButton: false,
                timer: 3000
            });
        </script>
    @endif

    <div class="space-y-4">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Manajemen Kategori</h1>
                <p class="text-gray-600 mt-1">Kelola kategori produk dengan mudah</p>
            </div>

            <div class="flex gap-3">
                <button x-data @click="$dispatch('open-modal', { name: 'create-category' })"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-green-500 text-white font-medium text-sm rounded-xl shadow-lg hover:shadow-xl hover:from-emerald-600 hover:to-emerald-700 transition-all duration-200 transform hover:-translate-y-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Tambah Kategori
                </button>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 text-left font-semibold">Nama</th>
                        <th class="px-6 py-4 text-left font-semibold">Slug</th>
                        <th class="px-6 py-4 text-left font-semibold">Total Produk</th>
                        <th class="px-6 py-4 text-right font-semibold">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @forelse ($categories as $cat)
                        <tr class="hover:bg-gray-50 transition">

                            <td class="px-6 py-4 font-medium text-gray-900">
                                {{ $cat->name }}
                            </td>

                            <td class="px-6 py-4 text-gray-500">
                                {{ $cat->slug }}
                            </td>

                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-700 rounded-lg">
                                    {{ $cat->products_count ?? $cat->products->count() }} produk
                                </span>
                            </td>

                            <td class="px-6 py-4">
                                <div x-data class="flex justify-end gap-2">

                                    <button
                                        @click="$dispatch('open-modal', { name: 'edit-category', id: {{ $cat->id }}, categoryName: '{{ $cat->name }}' })"
                                        class="px-3 py-1.5 text-xs font-medium bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition">
                                        Edit
                                    </button>

                                    <button
                                        @click="$dispatch('open-modal', { name: 'delete-category', id: {{ $cat->id }} })"
                                        class="px-3 py-1.5 text-xs font-medium bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition">
                                        Hapus
                                    </button>

                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-12 text-gray-400">
                                Belum ada kategori
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- create modal --}}
    <x-modal name="create-category" maxWidth="md">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6 pb-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold">Tambah Kategori Baru</h3>
                <button type="button" @click="$dispatch('close-modal', 'create-category')">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
            <form action="/categories" method="POST" class="space-y-4">
                @csrf
                @method('POST')

                <div>
                    <label class="text-sm font-medium">Nama</label>
                    <input type="text" name="name"
                        class="w-full mt-1 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                </div>

                <div class="flex justify-end gap-2 pt-4">
                    <button type="button" @click="$dispatch('close-modal', 'create-category')"
                        class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">
                        Batal
                    </button>

                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </x-modal>

    <x-modal name="edit-category" maxWidth="md">
        <div x-data="{ categoryId: null, categoryName: '' }"
            x-on:open-modal.window="
        if ($event.detail.name === 'edit-category') {
            categoryId = $event.detail.id
            name = $event.detail.categoryName
        }
        "
            class="p-6">
            <div class="flex justify-between items-center mb-5 pb-3 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900">
                    Edit Kategori
                </h3>

                <button type="button" @click="$dispatch('close-modal', 'edit-category')"
                    class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form :action="`/categories/${categoryId}`" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="text-sm font-medium">Nama</label>
                    <input type="text" name="name" x-model="name" :value="'{{ old('name') }}' || name"
                        class="w-full mt-1 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                </div>

                <div class="flex justify-end gap-2 pt-4">
                    <button type="button" @click="$dispatch('close-modal', 'edit-category')"
                        class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">
                        Batal
                    </button>

                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        Simpan
                    </button>
                </div>
            </form>

        </div>
    </x-modal>

    {{-- delete modal --}}
    <x-modal name="delete-category" maxWidth="md">
        <div x-data="{ categoryId: null }"
            x-on:open-modal.window="
            if ($event.detail.name === 'delete-category') {
                categoryId = $event.detail.id
            }"
            class="p-6">
            <div class="flex justify-between items-center mb-5 pb-3 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900">
                    Hapus Kategori
                </h3>

                <button type="button" @click="$dispatch('close-modal', 'delete-category')"
                    class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="text-center space-y-3">
                <p class="text-gray-700 text-md">
                    Apakah kamu yakin ingin menghapus kategori ini?
                </p>

                <p class="text-sm text-gray-400">
                    Data yang dihapus tidak dapat dikembalikan.
                </p>
            </div>

            <form :action="`/categories/${categoryId}`" method="POST" class="mt-6">
                @csrf
                @method('DELETE')

                <div class="flex gap-3">
                    <button type="button" @click="$dispatch('close-modal', 'delete-category')"
                        class="flex-1 px-4 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                        Batal
                    </button>

                    <button type="submit"
                        class="flex-1 px-4 py-2.5 bg-red-500 hover:bg-red-600 text-white rounded-lg font-medium shadow-sm hover:shadow transition">
                        Ya, Hapus
                    </button>
                </div>
            </form>

        </div>
    </x-modal>
</x-app-layout>
