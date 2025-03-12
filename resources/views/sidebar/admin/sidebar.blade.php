{{-- Sidebar Element Employee --}}

<aside id="sidebar"
class="z-20 w-64 h-full text-white bg-[#2D435F] dark:bg-gray-800 overflow-y-auto transition-transform duration-300 ease-in-out flex inset-y-0 left-0 transform">
<div class="py-4 text-[#F1F1F1] dark:text-gray-400 text-center">
    <a class="text-lg font-bold text-white dark:text-gray-200 text-center" href="#">
            @auth
                {{ Auth::user()->role }}
            @endauth
        </a>
        <ul class="mt-6">
            <li>
                <a href="{{ route('dashboard.index') }}" class="relative flex items-center px-6 py-3 w-full text-sm font-semibold transition-colors duration-150 
                        {{ request()->routeIs('dashboard.index') ? 'bg-white text-[#2D435F]' : ' hover:text-white' }}">

                    <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                        <path
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                        </path>
                    </svg>

                    <span class="ml-4">Dashboard</span>
                </a>
            </li>
        </ul>
        <ul>
            <li>
                <a href="{{ route('dashboard.peserta') }}"
                    class="relative flex items-center px-6 py-3 w-full text-sm font-semibold transition-colors duration-150 
                        {{ request()->routeIs('dashboard.peserta') ? 'bg-white text-[#2D435F]' : ' hover:text-white' }}">

                    <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                        <path
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                        </path>
                    </svg>

                    <span class="ml-4">Master Data Employee</span>
                </a>
            </li>
            <li>
                <a href="{{ route('user.index') }}" class="relative flex items-center px-6 py-3 w-full text-sm font-semibold transition-colors duration-150 
                        {{ request()->routeIs('user.index') ? 'bg-white text-[#2D435F]' : ' hover:text-white' }}">

                    <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>

                    <span class="ml-4">Account Admin User</span>
                </a>
            </li>
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

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const sidebar = document.getElementById("sidebar");
        const mainContent = document.getElementById("main-content");

        function updateSidebarState() {
            let sidebarState = localStorage.getItem("sidebarHidden");

            if (sidebarState === "true") {
                sidebar.classList.add("-translate-x-64"); // Sidebar disembunyikan
                mainContent.classList.remove("ml-64"); // Hilangkan margin-left
            } else {
                sidebar.classList.remove("-translate-x-64"); // Sidebar ditampilkan
                mainContent.classList.add("ml-64"); // Tambahkan margin-left
            }
        }

        // Setel ulang berdasarkan localStorage
        updateSidebarState();

        // Dengarkan event dari navbar
        window.addEventListener("sidebarToggle", updateSidebarState);
    });
</script>


<!-- Mobile sidebar -->
<!-- Backdrop -->