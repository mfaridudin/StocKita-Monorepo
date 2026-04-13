<x-app-layout title="Produk">
    @if ($message = session('success') ?? (session('error') ?? (session('warning') ?? session('info'))))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
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
            });
        </script>
    @endif
    <div class="space-y-6">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Manajemen Produk</h1>
                <p class="text-gray-600 mt-1">Kelola semua produk toko Anda</p>
            </div>

            <div x-data class="flex gap-3">
                <button
                    @click.prevent="if (!canCreateProducts) {
                        Swal.fire({
                            toast: true,
                            icon: 'error',
                            position: 'top-end',
                            title: 'Kamu tidak punya izin menambah produk!',
                            showConfirmButton: false,
                            timer: 3000
                        });
                    } else {
                        $dispatch('open-modal', { name: 'add-produk'})
                    }"
                    class="inline-flex items-center gap-2 px-6 py-3 text-white font-medium text-sm rounded-xl {{ auth()->user()->can('create products')
                        ? 'bg-green-500 shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5'
                        : 'bg-green-200 border-gray-200 cursor-not-allowed' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Tambah Produk
                </button>
            </div>
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

                            <td x-data class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">

                                    <a href="/products/{{ $product->id }}"
                                        class="px-3 py-1 text-xs bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200">
                                        Detail
                                    </a>

                                    <button
                                        class="px-3 py-1.5 text-xs font-medium bg-red-50 text-red-600 rounded-lg {{ auth()->user()->can('delete products') ? 'hover:bg-red-100 transition' : 'cursor-not-allowed' }}"
                                        @click="if (!canDeleteProducts) {
                                            Swal.fire({
                                                toast: true,
                                                icon: 'error',
                                                position: 'top-end',
                                                title: 'Kamu tidak punya izin menghapus produk!',
                                                showConfirmButton: false,
                                                timer: 3000
                                            });
                                        } else {
                                            $dispatch('open-modal', { name: 'delete-product', id: {{ $product->id }} })
                                        }">
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

    {{-- delete modal --}}
    <x-modal name="delete-product" maxWidth="md">
        <div x-data="{ productId: null }"
            x-on:open-modal.window="
            if ($event.detail.name === 'delete-product') {
                productId = $event.detail.id
            }"
            class="p-6">
            <div class="flex justify-between items-center mb-5 pb-3 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900">
                    Hapus Produk
                </h3>

                <button type="button" @click="$dispatch('close-modal', 'delete-product')"
                    class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <p>Apakah anda yakin ingin meghapus produk ini?</p>

            <form :action="`/products/${productId}`" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex justify-end gap-2 pt-4">
                    <button type="button" @click="$dispatch('close-modal', 'delete-product')"
                        class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">
                        Batal
                    </button>

                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-green-700">
                        Hapus
                    </button>
                </div>
            </form>

        </div>
    </x-modal>

    {{-- modal add produk --}}
    <x-modal name="add-produk" maxWidth="xl" :show="$errors->any()">
        <div class="p-6">
            <form action="/products" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="flex justify-between items-center mb-6 pb-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Tambah Produk</h3>
                    <button type="button" @click="$el.closest('form').reset(); $dispatch('close-modal', 'add-produk')">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </button>
                </div>

                <div class="space-y-4">
                    <div class="grid grid-col-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Produk</label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 outline-none">
                            <x-input-error :messages="$errors->get('name')" class="mt-2 text-red-500 text-sm" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Harga</label>
                            <input type="number" name="price" value="{{ old('price') }}"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 outline-none">
                            <x-input-error :messages="$errors->get('price')" class="mt-2 text-red-500 text-sm" />
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                        <select name="category_id"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 outline-none">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('category_id')" class="mt-2 text-red-500 text-sm" />
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Gambar Produk</label>
                        <div
                            class="border-2 border-dashed border-gray-200 rounded-xl p-6 text-center hover:border-gray-300 hover:bg-gray-50 transition-colors duration-200">
                            <input type="file" name="image" accept="image/*" class="hidden" id="imageUpload">
                            <div id="uploadPlaceholder">
                                <label for="imageUpload" class="cursor-pointer block">
                                    <svg class="w-12 h-12 mx-auto mb-4 text-gray-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p class="text-sm font-medium text-gray-700 mb-1">Klik untuk upload gambar</p>
                                    <p class="text-xs text-gray-500">PNG, JPG, GIF (max 5MB)</p>
                                </label>
                            </div>
                            <div id="imagePreview"
                                class="mx-auto w-60 aspect-square overflow-hidden rounded-lg border hidden cursor-pointer">
                                <img class="w-full h-full object-cover">
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('image')" class="mt-2 text-red-500 text-sm" />

                    </div>

                    <div class="flex justify-end gap-3 pt-4">
                        <button @click.prevent="$el.closest('form').reset(); $dispatch('close-modal', 'add-produk')"
                            class="px-4 py-2 text-sm bg-gray-200 rounded-lg hover:bg-gray-300">
                            Batal
                        </button>

                        <button type="submit"
                            class="px-5 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700">
                            Simpan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </x-modal>

    <script>
        const canCreateProducts = @json(auth()->user()->can('create products'));
        const canDeleteProducts = @json(auth()->user()->can('delete products'));
        const canUploadImage = @json(auth()->user()->can('upload product images'));
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const imageUpload = document.getElementById('imageUpload');
            const imagePreview = document.getElementById('imagePreview');
            const uploadPlaceholder = document.getElementById('uploadPlaceholder');

            if (!imageUpload || !imagePreview) return;

            const img = imagePreview.querySelector('img');

            imageUpload.addEventListener('click', function(e) {
                if (!canUploadImage) {
                    e.preventDefault();
                    Swal.fire({
                        toast: true,
                        icon: 'error',
                        position: 'top-end',
                        title: 'Kamu tidak punya izin upload gambar!',
                        showConfirmButton: false,
                        timer: 3000
                    });
                }
            });

            imageUpload.addEventListener('change', function(e) {
                if (!canUploadImage) {
                    imageUpload.value = '';
                    Swal.fire({
                        toast: true,
                        icon: 'error',
                        position: 'top-end',
                        title: 'Kamu tidak punya izin upload gambar!',
                        showConfirmButton: false,
                        timer: 3000
                    });
                    return;
                }

                const file = e.target.files[0];

                if (file) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        if (img) {
                            img.src = e.target.result;
                        }

                        imagePreview.classList.remove('hidden');
                        uploadPlaceholder?.classList.add('hidden');
                    };

                    reader.readAsDataURL(file);
                }
            });

            imagePreview.addEventListener('click', function() {
                if (!canUploadImage) {
                    Swal.fire({
                        toast: true,
                        icon: 'error',
                        position: 'top-end',
                        title: 'Kamu tidak punya izin upload gambar!',
                        showConfirmButton: false,
                        timer: 3000
                    });
                    return;
                }

                imageUpload.click();
            });
        });
    </script>
</x-app-layout>
