<x-app-layout title="Settings">
    <div class="mx-auto">

        <div class="mb-12">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Settings</h1>
            <p class="text-lg text-gray-600">Kelola konfigurasi aplikasi dan toko</p>
        </div>

        <div class="space-y-8">

            <!-- GENERAL -->
            <div class="bg-white border border-gray-200 rounded-2xl p-8 shadow-sm">
                <div x-data class="flex justify-between items-start mb-8">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-900 mb-1">General</h2>
                        <p class="text-sm text-gray-500">Identitas aplikasi</p>
                    </div>
                    <button @click="$dispatch('open-modal', { name: 'edit-app'})"
                        class="text-sm font-medium text-green-600 hover:text-green-700 px-3 py-1 border border-green-100 rounded-lg hover:bg-green-50 transition-colors">
                        Edit
                    </button>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Nama Aplikasi</p>
                        <p class="text-2xl font-bold text-gray-900">{{ config('app.name', 'StocKita') }}</p>
                    </div>
                    <div class="space-y-2">
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Deskripsi</p>
                        <p class="text-lg font-semibold text-gray-900">{{ setting('app.description', 'POS ') }}</p>
                    </div>
                </div>
            </div>

            <!-- STORE -->
            <div class="bg-white border border-gray-200 rounded-2xl p-8 shadow-sm">
                <div x-data class="flex justify-between items-start mb-8">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-900 mb-1">Store</h2>
                        <p class="text-sm text-gray-500">Informasi toko</p>
                    </div>
                    <button @click="$dispatch('open-modal', { name: 'edit-store'})"
                        class="text-sm font-medium text-green-600 hover:text-green-700 px-3 py-1 border border-green-100 rounded-lg hover:bg-green-50 transition-colors">
                        Edit
                    </button>
                </div>

                <div class="grid grid-cols-2 gap-8">
                    <div class="space-y-1">
                        <p class="text-sm font-medium text-gray-500">Nama Toko</p>
                        <p class="font-semibold text-lg text-gray-900">{{ setting('store.name', 'StocKita ') }}</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-sm font-medium text-gray-500">Email</p>
                        <p class="font-semibold text-gray-900">{{ setting('store.email', 'StocKita@gmail.com') }}</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-sm font-medium text-gray-500">No HP</p>
                        <p class="font-semibold text-gray-900">{{ setting('store.phone', '0812-3456-7890') }}</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-sm font-medium text-gray-500">Alamat</p>
                        <p class="font-semibold text-gray-900">
                            {{ setting(
                                'store.address',
                                'Jl. Sudirman No. 123 (Samping Bank BCA), RT 01/RW 04, Kel. Menteng, Kec. Menteng, Kota Jakarta Pusat, DKI Jakarta, 10310 Telp/WA: 0812-3456-7890',
                            ) }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- email --}}

            <div x-data class="bg-white border border-gray-200 rounded-2xl p-8 shadow-sm">
                <div class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-1">Email Template</h2>
                    <p class="text-sm text-gray-500">Digunakan untuk komunikasi customer</p>
                </div>

                @php
                    $template = setting('email.welcome');

                    $template = str_replace(
                        ['{{ name }}', '{{ store . name }}'],
                        ['Customer', setting('store.name')],
                        $template,
                    );
                @endphp

                <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 mb-6">

                    <p class="text-xs text-gray-400 mb-3">Preview Email</p>

                    <div class="text-sm text-gray-700 leading-relaxed space-y-2">
                        {!! nl2br(e($template)) !!}
                    </div>

                </div>

                <button @click="$dispatch('open-modal', { name: 'email-template'})"
                    class="w-full sm:w-auto text-sm font-medium text-green-600 hover:text-green-700 px-4 py-2 border border-green-100 rounded-lg hover:bg-green-50 transition-colors">
                    Edit Template
                </button>
            </div>

            {{-- subscribtion --}}
            <div class="bg-white border border-gray-200 rounded-2xl p-8 shadow-sm">
                <div class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-1">Subscription</h2>
                    <p class="text-sm text-gray-500">Status paket saat ini</p>
                </div>

                @if ($plan)
                    <div class="flex items-center justify-between p-6 border border-gray-100 rounded-xl bg-green-50/50">
                        <div>
                            <p class="text-xl font-bold text-gray-900">{{ $plan->name }} Plan</p>
                            <p class="text-sm text-gray-600">
                                Limit {{ $plan->max_products }} produk • {{ ucfirst($subscription->status) }}
                            </p>
                        </div>
                        <form action="{{ route('subscription.index') }}" method="GET">
                            <button type="submit"
                                class="bg-green-600 text-white px-6 py-2.5 rounded-lg font-medium text-sm hover:bg-green-700 transition-colors shadow-sm hover:shadow-md">
                                Upgrade
                            </button>
                        </form>
                    </div>
                @else
                    <div class="p-6 border border-gray-100 rounded-xl bg-gray-50 text-center">
                        <p class="text-gray-500">Belum ada paket aktif</p>
                        <a href="{{ route('subscription.index') }}"
                            class="mt-4 inline-block bg-green-600 text-white px-6 py-2.5 rounded-lg font-medium text-sm hover:bg-green-700 transition-colors shadow-sm hover:shadow-md">
                            Pilih Paket
                        </a>
                    </div>
                @endif
            </div>

        </div>
    </div>

    {{-- edit app --}}
    <x-modal name="edit-app" maxWidth="lg">
        <div class="p-6">
            <div class="flex justify-between items-center mb-5 pb-3 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900">
                    Edit Informasi Aplikasi
                </h3>

                <button type="button" @click="$dispatch('close-modal', 'edit-app')"
                    class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form method="POST" action="/settings" class="space-y-5">
                @csrf
                <div class="space-y-4">

                    <div>
                        <label class="text-sm font-medium">Nama Aplikasi</label>
                        <input type="text" name="app[name]" value="{{ setting('store.name', 'StocKita ') }}"
                            class="w-full border px-3 py-2 rounded-xl">
                    </div>

                    <div>
                        <label class="text-sm font-medium">Deskripsi</label>
                        <input type="text" name="app[description]" value="{{ setting('store.email', 'Deskripsi') }}"
                            class="w-full border px-3 py-2 rounded-xl">
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-4 border-t">
                    <button type="button" @click="$dispatch('close-modal', 'edit-app')"
                        class="px-4 py-2 text-sm border rounded-lg">
                        Batal
                    </button>

                    <button type="submit"
                        class="px-4 py-2 text-sm bg-green-600 text-white rounded-lg hover:bg-green-700">
                        Simpan
                    </button>
                </div>

            </form>
        </div>
    </x-modal>

    {{-- edit toko --}}
    <x-modal name="edit-store" maxWidth="lg">
        <div class="p-6">
            <div class="flex justify-between items-center mb-5 pb-3 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900">
                    Edit Informasi Toko
                </h3>

                <button type="button" @click="$dispatch('close-modal', 'edit-store')"
                    class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form method="POST" action="/settings" class="space-y-5">
                @csrf
                <div class="space-y-4">

                    <div>
                        <label class="text-sm font-medium">Nama Toko</label>
                        <input type="text" name="store[name]" value="{{ setting('store.name', 'StocKita ') }}"
                            class="w-full border px-3 py-2 rounded-xl">
                    </div>

                    <div>
                        <label class="text-sm font-medium">Email</label>
                        <input type="email" name="store[email]"
                            value="{{ setting('store.email', 'StocKita@gmail.com') }}"
                            class="w-full border px-3 py-2 rounded-xl">
                    </div>

                    <div>
                        <label class="text-sm font-medium">No HP</label>
                        <input type="text" name="store[phone]"
                            value="{{ setting('store.phone', '0812-3456-7890') }}"
                            class="w-full border px-3 py-2 rounded-xl">
                    </div>

                    <div>
                        <label class="text-sm font-medium">Alamat</label>
                        <textarea name="store[address]" class="w-full h-32 border px-3 py-2 rounded-xl"> {{ setting(
                            'store.address',
                            'Jl. Sudirman No. 123 (Samping Bank BCA), RT 01/RW 04, Kel. Menteng, Kec. Menteng, Kota Jakarta Pusat, DKI Jakarta, 10310 Telp/WA: 0812-3456-7890',
                        ) }}</textarea>
                    </div>

                </div>

                <div class="flex justify-end gap-2 pt-4 border-t">
                    <button type="button" @click="$dispatch('close-modal', 'edit-store')"
                        class="px-4 py-2 text-sm border rounded-lg">
                        Batal
                    </button>

                    <button type="submit"
                        class="px-4 py-2 text-sm bg-green-600 text-white rounded-lg hover:bg-green-700">
                        Simpan
                    </button>
                </div>

            </form>
        </div>
    </x-modal>

    {{-- email templte --}}
    <x-modal name="email-template" maxWidth="lg">
        <div class="p-6">
            <div class="flex justify-between items-center mb-5 pb-3 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900">
                    Edit Template Email
                </h3>

                <button type="button" @click="$dispatch('close-modal', 'email-template')"
                    class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form method="POST" action="/settings" class="space-y-6">
                @csrf
                <div>
                    <label class="text-sm font-medium text-gray-700">
                        Template Email
                    </label>

                    <textarea name="email[welcome]" rows="8"
                        class="mt-2 w-full border border-gray-200 rounded-xl px-4 py-3 font-mono text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500"
                        placeholder="Tulis template email di sini...">{{ setting('email.welcome') }}</textarea>

                    <p class="text-xs text-gray-500 mt-2">
                        Gunakan variable:
                        <span class="font-mono">@{{ name }}</span>,
                        <span class="font-mono">@{{ store_name }}</span>
                    </p>
                </div>

                <!-- PREVIEW -->
                <div>
                    <p class="text-sm font-medium text-gray-700 mb-2">Preview</p>

                    <div
                        class="bg-gray-50 border border-gray-200 rounded-xl p-4 text-sm text-gray-700 leading-relaxed">
                        {!! nl2br(
                            e(
                                str_replace(
                                    ['{{ name }}', '{{ store_name }}'],
                                    ['JAYA', setting('store.name')],
                                    setting('email.welcome'),
                                ),
                            ),
                        ) !!}
                    </div>
                </div>

                <!-- ACTION -->
                <div class="flex justify-end">
                    <button type="submit"
                        class="px-5 py-2.5 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition">
                        Simpan Template
                    </button>
                </div>

            </form>
        </div>
    </x-modal>
</x-app-layout>
