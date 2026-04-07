 <div x-data="{ open: false }" class="relative">

     <button @click="open = !open" class="relative p-2 rounded-lg hover:bg-green-50">
         <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                 d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
             </path>
         </svg>

         @if ($notifications->count() > 0)
             <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
         @endif
     </button>

     <div x-show="open" @click.outside="open = false" x-transition
         class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-lg border z-50 overflow-hidden">

         <div class="p-3 font-semibold border-b text-gray-700">
             Notifikasi
         </div>

         <div class="max-h-72 overflow-y-auto">

             @forelse ($notifications as $notif)
                 <a href="{{ $notif['url'] }}" class="block p-3 border-b hover:bg-gray-50 transition">
                     <p
                         class="text-sm font-semibold
                            @if ($notif['type'] == 'danger') text-red-600
                            @elseif($notif['type'] == 'warning') text-yellow-600
                            @else text-gray-700 @endif">
                         {{ $notif['title'] }}
                     </p>

                     <p class="text-xs text-gray-500 truncate">
                         {{ $notif['message'] }}
                     </p>
                 </a>
             @empty
                 <div class="p-4 text-center text-sm text-gray-500">
                     Tidak ada notifikasi
                 </div>
             @endforelse

         </div>
     </div>
 </div>
