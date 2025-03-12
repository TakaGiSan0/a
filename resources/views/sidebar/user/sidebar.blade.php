{{-- Sidebar Element Employee --}}

<aside
    class="z-20 w-64 h-full text-white bg-[#2D435F] dark:bg-gray-800 overflow-y-auto transition-transform duration-300 ease-in-out flex inset-y-0 left-0 transform">
    <div class="py-4 text-[#F1F1F1] dark:text-gray-400 text-center">
        <a class="text-lg font-bold text-white dark:text-gray-200 text-center" href="#">
            @auth
                {{ Auth::user()->role }}
            @endauth
        </a>
        <ul class="mt-6">
            <li>
                <a href="{{ route('dashboard.summary') }}"
                    class="relative flex items-center px-6 py-3 w-full text-sm font-semibold transition-colors duration-150 
                        {{ request()->routeIs('dashboard.summary') ? 'bg-white text-[#2D435F]' : ' hover:text-white' }}">

                    <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                        <path
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                        </path>
                    </svg>

                    <span class="ml-4">Summary Training Record</span>
                </a>
            </li>
            <li>
                <a href="{{ route('dashboard.employee') }}"
                    class="relative flex items-center px-6 py-3 w-full text-sm font-semibold transition-colors duration-150 
                        {{ request()->routeIs('dashboard.employee') ? 'bg-white text-[#2D435F]' : ' hover:text-white' }}">

                    <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                        <path d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                        <path d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
                    </svg>
                    <span class="ml-4">Employee Training Record</span>
                </a>
            </li>
        </ul>

    </div>
</aside>
<!-- Mobile sidebar -->
<!-- Backdrop -->