<nav class="fixed top-0 left-0 w-full bg-black z-50 shadow-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center h-16">
        <a href="/" class="flex items-center space-x-2 font-extrabold text-2xl tracking-tight text-white">
            <span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-8 w-8 mr-2 text-white">
                  <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2" fill="none"/>
                  <circle cx="12" cy="12" r="2" fill="currentColor"/>
                  <circle cx="7.5" cy="8.5" r="1" fill="currentColor"/>
                  <circle cx="16.5" cy="8.5" r="1" fill="currentColor"/>
                  <circle cx="7.5" cy="15.5" r="1" fill="currentColor"/>
                  <circle cx="16.5" cy="15.5" r="1" fill="currentColor"/>
                </svg>
            </span>
            <span>Echo</span>
        </a>
        <div class="flex items-center space-x-6">
            @guest
                <a href="{{ route('login') }}" class="px-4 py-2 text-white border border-white rounded font-medium hover:bg-white hover:text-black transition">Login</a>
                <a href="{{ route('register') }}" class="px-4 py-2 text-white border border-white rounded font-medium hover:bg-white hover:text-black transition">Register</a>
            @else
                <div class="relative group">
                    <button class="flex items-center space-x-2 text-white font-medium focus:outline-none">
                        <span>{{ Auth::user()->name }}</span>
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div class="absolute right-0 mt-2 w-40 bg-black border border-white rounded shadow-lg opacity-0 group-hover:opacity-100 group-focus:opacity-100 transition-opacity duration-150 z-10">
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-white hover:bg-white hover:text-black">Profile</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-white hover:bg-white hover:text-black rounded">Sign Out</button>
                        </form>
                    </div>
                </div>
            @endguest
        </div>
    </div>
</nav>
<div class="h-16"></div> <!-- Spacer for fixed nav -->
