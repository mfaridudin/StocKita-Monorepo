<x-app-layout title="Toko">
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
    <div class="space-y-4">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Manajemen Toko</h1>
                <p class="text-gray-600 mt-1">Kelola Toko produk dengan mudah</p>
            </div>

            <div class="flex gap-3">
                <button x-data @click="$dispatch('open-modal', { name: 'create-store' })"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-green-500 text-white font-medium text-sm rounded-xl shadow-lg hover:shadow-xl hover:from-emerald-600 hover:to-emerald-700 transition-all duration-200 transform hover:-translate-y-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Tambah Toko
                </button>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 text-left font-semibold">Nama</th>
                        <th class="px-6 py-4 text-left font-semibold">Pemilik</th>
                        <th class="px-6 py-4 text-left font-semibold">Slug</th>
                        <th class="px-6 py-4 text-left font-semibold">Email</th>
                        <th class="px-6 py-4 text-left font-semibold">Alamat</th>
                        <th class="px-6 py-4 text-right font-semibold">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @forelse ($stores as $store)
                        <tr class="hover:bg-gray-50 transition">

                            <td class="px-6 py-4 font-medium text-gray-900">
                                {{ $store->name ?? '-' }}
                            </td>

                            <td class="px-6 py-4 text-gray-500">
                                {{ $store->owner->name ?? '-' }}
                            </td>

                            <td class="px-6 py-4 text-gray-500">
                                {{ $store->slug ?? '-' }}
                            </td>

                            <td class="px-6 py-4 text-gray-500">
                                {{ $store->email ?? '-' }}
                            </td>

                            <td class="px-6 py-4 text-gray-500">
                                {{ $store->address ?? '-' }}
                            </td>

                            <td class="px-6 py-4">
                                <div x-data class="flex justify-end gap-2">

                                    <a href="/admin/store/{{ $store->id }}"
                                        class="px-3 py-1.5 text-xs font-medium bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition">
                                        Detail
                                    </a>

                                    <button
                                        @click="$dispatch('open-modal', { name: 'delete-store', id: {{ $store->id }} })"
                                        class="px-3 py-1.5 text-xs font-medium bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition">
                                        Hapus
                                    </button>

                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-12 text-gray-400">
                                Belum ada Toko
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- create modal --}}
    <x-modal name="create-store" maxWidth="xl" :show="$errors->any()">
        <div class="p-6">
            <form action="/admin/store" method="POST">
                @csrf

                <div class="flex justify-between items-center mb-6 pb-4 border-b">
                    <h3 class="text-lg font-semibold">Tambah Toko</h3>

                    <button type="button" @click="$el.closest('form').reset(); $dispatch('close-modal', 'create-store')">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </button>
                </div>

                <div class="space-y-4">

                    <div class="grid grid-cols-2 gap-4">
                        {{-- OWNER --}}
                        <div>
                            <label class="text-sm font-medium">Pemilik</label>
                            <input type="text" name="owner_name" class="w-full mt-1 px-4 py-2 border rounded-lg">
                            <x-input-error :messages="$errors->get('owner_name')" />
                        </div>

                        <div>
                            <label class="text-sm font-medium">Email Pemilik</label>
                            <input type="email" name="owner_email" class="w-full mt-1 px-4 py-2 border rounded-lg">
                            <x-input-error :messages="$errors->get('owner_email')" />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        {{-- NAME --}}
                        <div>
                            <label class="text-sm font-medium">Nama Toko</label>
                            <input type="text" name="name" class="w-full mt-1 px-4 py-2 border rounded-lg">
                            <x-input-error :messages="$errors->get('name')" />
                        </div>

                        {{-- EMAIL --}}
                        <div>
                            <label class="text-sm font-medium">Email</label>
                            <input type="email" name="email" class="w-full mt-1 px-4 py-2 border rounded-lg">
                            <x-input-error :messages="$errors->get('email')" />
                        </div>
                    </div>

                    {{-- PHONE --}}
                    <div>
                        <label class="text-sm font-medium">No Telepon</label>
                        <input type="text" name="phone" class="w-full mt-1 px-4 py-2 border rounded-lg">
                        <x-input-error :messages="$errors->get('phone')" />
                    </div>

                    {{-- ADDRESS --}}
                    <div>
                        <label class="text-sm font-medium">Alamat</label>
                        <textarea name="address" class="w-full mt-1 px-4 py-2 border rounded-lg"></textarea>
                        <x-input-error :messages="$errors->get('address')" />
                    </div>

                    {{-- ACTION --}}
                    <div class="flex justify-end gap-2 pt-4">
                        <button type="button" @click="$el.closest('form').reset(); $dispatch('close-modal', 'create-store')"
                            class="px-4 py-2 bg-gray-200 rounded-lg">
                            Batal
                        </button>

                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg">
                            Simpan
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </x-modal>

    {{-- delete modal --}}
    <x-modal name="delete-store" maxWidth="md">
        <div x-data="{ storeId: null }"
            x-on:open-modal.window="
            if ($event.detail.name === 'delete-store') {
                storeId = $event.detail.id
            }"
            class="p-6">
            <div class="flex justify-between items-center mb-5 pb-3 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900">
                    Hapus Toko
                </h3>

                <button type="button" @click="$dispatch('close-modal', 'delete-store')"
                    class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="text-center space-y-3">
                <p class="text-gray-700 text-md">
                    Apakah kamu yakin ingin menghapus Toko ini?
                </p>

                <p class="text-sm text-gray-400">
                    Data yang dihapus tidak dapat dikembalikan.
                </p>
            </div>

            <form :action="`/admin/store/${storeId}`" method="POST" class="mt-6">
                @csrf
                @method('DELETE')

                <div class="flex gap-3">
                    <button type="button" @click="$dispatch('close-modal', 'delete-store')"
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
