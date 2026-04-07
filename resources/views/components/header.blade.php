<header class="h-16 sticky top-0 z-10 bg-white border-b border-green-100 flex items-center justify-between px-6">
    <div class="flex items-center gap-4">
        <button id="toggleCollapse" class="p-2 relative  rounded-lg hover:bg-green-50">
            <svg id="iconMenu" class="w-6 h-6 text-gray-600 absolute inset-0 m-auto" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>

            <svg id="iconArrow" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor" class="w-6 h-6 text-gray-600 absolute inset-0 m-auto opacity-0">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
            </svg>
        </button>

        <h1 class="text-lg font-semibold text-gray-800">
            {{ $title ?? 'Dashboard' }}
        </h1>

    </div>

    <div class="flex items-center gap-4">
        {{-- <div
            class="flex items-center bg-gray-100 rounded-lg px-3 py-2 focus-within:bg-white focus-within:ring-2 focus-within:ring-green-200 focus-within:shadow-sm transition-all duration-200">
            <svg class="w-4 h-4 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            <input type="text" id="searchInput" name="search" placeholder="Cari..."
                class="bg-transparent border-0 outline-none focus:outline-none focus:ring-0 focus:border-0 text-sm w-48 md:w-64 pl-2 pr-3 py-1"
                autocomplete="off">
            <button
                class="hidden ml-2 p-1 text-gray-400 hover:text-gray-600 rounded-full hover:bg-gray-200 transition-colors duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div> --}}

        {{-- <button class="relative p-2 rounded-lg hover:bg-green-50">
            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                </path>
            </svg>

            <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
        </button> --}}

        <div class="relative group inline-block">
            <button class="flex items-center gap-2 p-2 rounded-lg hover:bg-green-50">
                <div
                    class="w-8 h-8 bg-green-500 text-white flex items-center justify-center rounded-full text-sm font-semibold">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                </div>
                <span class="hidden md:block text-sm font-medium text-gray-700">
                    {{ auth()->user()->name ?? 'User' }}
                </span>
            </button>

            <div
                class="absolute right-0 mt-2 w-64 bg-white/95 backdrop-blur-xl border border-white/50 rounded-2xl shadow-2xl opacity-0 invisible group-hover:opacity-100 group-hover:visible scale-95 group-hover:scale-100 transition-all duration-200 origin-top-right border-b-0 overflow-hidden">

                <div x-data class="py-2 space-y-1">
                    <a href="/profile"
                        class="group/menu flex items-center gap-3 px-5 py-3 text-gray-700 hover:bg-gradient-to-r hover:from-green-50 hover:to-emerald-50 hover:text-green-700 transition-all duration-200 relative overflow-hidden">
                        <div
                            class="w-10 h-10 bg-indigo-400 flex items-center justify-center rounded-xl text-white text-sm font-semibold shadow-md group-hover/parent:scale-105 transition-transform duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="font-medium block">Profile</span>
                            <span class="text-xs opacity-75 block">Lihat profil Anda</span>
                        </div>
                        <div
                            class="w-2 h-2 bg-green-400 rounded-full opacity-0 group-hover:opacity-100 transition-opacity ml-auto">
                        </div>
                    </a>

                    <div class="px-4 py-2">
                        <div class="w-full h-px bg-gradient-to-r from-transparent via-gray-200 to-transparent"></div>
                    </div>

                    <button @click="$dispatch('open-modal', { name: 'logout' })"
                        class="w-full flex items-center gap-3 px-5 py-3 text-red-600 hover:bg-gradient-to-r hover:from-red-50 hover:to-rose-50 hover:text-red-700 group/logout transition-all duration-200 relative overflow-hidden">
                        <div
                            class="w-10 h-10 bg-gradient-to-r from-red-500 to-rose-600 flex items-center justify-center rounded-xl text-white text-sm font-semibold shadow-md group-hover/logout:scale-105 transition-transform duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1 text-left">
                            <span class="font-medium block">Keluar</span>
                            <span class="text-xs opacity-75 block">Keluar dari akun</span>
                        </div>
                    </button>
                    {{-- </form> --}}
                </div>
            </div>
        </div>
    </div>
</header>
