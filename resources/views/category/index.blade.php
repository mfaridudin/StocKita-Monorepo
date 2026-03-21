<x-app-layout title="Kategori">
    <div class="space-y-4">

        <div class="flex justify-between">
            <h1 class="text-lg font-semibold">Kategori</h1>
            <a href="/categories/create" class="px-4 py-2 bg-green-600 text-white rounded-lg">
                + Tambah
            </a>
        </div>

        <div class="bg-white rounded-xl border overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="p-3 text-left">Nama</th>
                        <th class="p-3 text-left">Slug</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $cat)
                        <tr class="border-t">
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
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</x-app-layout>
