<x-app-layout title="Detail Pelanggan">
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
    <div class="max-w-7xl mx-auto space-y-6">

        <div
            class="bg-white p-6 rounded-2xl shadow-sm border flex flex-col md:flex-row md:items-center md:justify-between gap-4">

            <div class="flex items-center gap-4">
                <div
                    class="w-14 h-14 bg-green-500 text-white flex items-center justify-center rounded-xl text-lg font-bold shadow">
                    {{ strtoupper(substr($customer->user->name, 0, 1)) }}
                </div>

                <div>
                    <h2 class="text-xl font-bold text-gray-800">
                        {{ $customer->user->name }}
                    </h2>
                    <p class="text-sm text-gray-500">{{ $customer->user->email ?? '-' }}</p>
                    <p class="text-sm text-gray-500">{{ $customer->phone ?? '-' }}</p>
                </div>
            </div>

            <div x-data class="flex gap-2">
                <button @click="$dispatch('open-modal', { name: 'edit-customer' })"
                    class="px-4 py-2 bg-blue-100 text-blue-600 rounded-lg text-sm hover:bg-blue-200">
                    Edit
                </button>

                <form action="/customers/{{ $customer->id }}/email" method="POST">
                    @csrf
                    <button class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm hover:bg-green-700">
                        Kirim Email
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            <div class="bg-white p-5 rounded-xl border shadow-sm">
                <p class="text-sm text-gray-500">Total Transaksi</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">
                    {{ $totalOrders }}
                </p>
            </div>

            <div class="bg-white p-5 rounded-xl border shadow-sm">
                <p class="text-sm text-gray-500">Total Belanja</p>
                <p class="text-2xl font-bold text-green-600 mt-1">
                    Rp {{ number_format($totalSpent, 0, ',', '.') }}
                </p>
            </div>

            <div class="bg-white p-5 rounded-xl border shadow-sm">
                <p class="text-sm text-gray-500">Terakhir Beli</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">
                    {{ $lastOrder ?? '-' }}
                </p>
            </div>

        </div>

        <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">

            <div class="p-6 border-b">
                <h3 class="text-lg font-semibold text-gray-800">
                    Riwayat Transaksi
                </h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">

                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                        <tr>
                            <th class="px-6 py-3 text-left">Tanggal</th>
                            <th class="px-6 py-3 text-left">Total</th>
                            <th class="px-6 py-3 text-left">Status</th>
                            <th class="px-6 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y">

                        @forelse ($orders as $order)
                            <tr class="hover:bg-gray-50 transition">

                                <td class="px-6 py-4">
                                    {{ $order->created_at->format('d M Y') }}
                                </td>

                                <td class="px-6 py-4 font-medium text-gray-700">
                                    Rp {{ number_format($order->total, 0, ',', '.') }}
                                </td>

                                <td class="px-6 py-4">
                                    <span
                                        class="px-3 py-1 text-xs rounded-full
                                        {{ $order->status === 'selesai' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-right">
                                    <a href="/transactions/{{ $order->id }}"
                                        class="text-blue-500 text-xs hover:underline">
                                        Detail
                                    </a>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-10 text-gray-400">
                                    Belum ada transaksi
                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>
            </div>
        </div>

    </div>

    {{-- modal edit --}}
    <x-modal name="edit-customer" maxWidth="md">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6 pb-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold">Edit Pelanggan</h3>
                <button type="button" @click="$dispatch('close-modal', 'edit-customer')">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
            <form action="{{ route('customers.update', $customer->id) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap *</label>
                    <input type="text" name="name" required value="{{ $customer->user->name }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" value="{{ $customer->user->email }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Pelanggan *</label>
                        <select name="type" required class="w-full px-3 py-2 border border-gray-300 rounded-lg">

                            <option value="regular" {{ $customer->type == 'regular' ? 'selected' : '' }}>
                                Regular
                            </option>
                            <option value="exclusive" {{ $customer->type == 'exclusive' ? 'selected' : '' }}>
                                Exclusive
                            </option>

                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                        <select name="status" required class="w-full px-3 py-2 border border-gray-300 rounded-lg">

                            <option value="active" {{ $customer->status == 'active' ? 'selected' : '' }}>
                                Aktif
                            </option>
                            <option value="inactive" {{ $customer->status == 'inactive' ? 'selected' : '' }}>
                                Tidak Aktif
                            </option>

                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nomor WhatsApp</label>
                    <input type="tel" name="phone" value="{{ $customer->formatted_phone }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="button" @click="$dispatch('close-modal', 'edit-customer')"
                        class="close-modal flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-1 px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg font-medium">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </x-modal>

</x-app-layout>
