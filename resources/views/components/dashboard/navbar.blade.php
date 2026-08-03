   <header class="h-16 bg-white border-b border-gray-100 flex items-center justify-between px-4 sm:px-6 sticky top-0 z-30">
       <div class="flex items-center gap-4">
           <button @click="sidebarOpen = true" class="lg:hidden text-gray-500 hover:text-gray-700 p-1 rounded-md hover:bg-gray-50">
               <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
               </svg>
           </button>
           <h2 class="text-lg font-semibold text-gray-800 hidden sm:block">@yield('header_title', 'Dashboard')</h2>
       </div>
       <div class="flex items-center gap-3 sm:gap-4">
           {{-- <button class="p-2 text-gray-400 hover:text-gray-600 rounded-full hover:bg-gray-50 relative">
               <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
               </svg>
               <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
           </button>
           <div class="h-6 w-px bg-gray-200"></div> --}}
           <div class="relative" x-data="{ open: false }">
               <button @click="open = !open" @click.outside="open = false" class="flex items-center gap-2 text-sm font-medium text-gray-700 hover:text-gray-900 focus:outline-none p-1 rounded-lg hover:bg-gray-50 transition-colors">
                   <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Admin') }}&background=e0e7ff&color=4f46e5" alt="User" class="w-7 h-7 rounded-full border border-gray-200">
                   <span class="hidden sm:block">{{ auth()->user()->name ?? 'Admin' }}</span>
                   <svg class="w-4 h-4 text-gray-400 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                   </svg>
               </button>
               <div x-show="open" x-transition.origin.top.right style="display: none;" class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-50">
                   <a href="#" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50">
                       <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                       </svg>
                       Profil Saya
                   </a>
                   <hr class="my-1 border-gray-100">
                   <form method="POST" action="{{ route('logout') }}">
                       @csrf
                       <button type="submit" class="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50">
                           <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                           </svg>
                           Keluar
                       </button>
                   </form>
               </div>
           </div>
       </div>
   </header>
