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
                <a href="{{ route('dashboard.peserta') }}"
                    class="relative flex items-center px-6 py-3 w-full text-sm font-semibold transition-colors duration-150
                        {{ request()->routeIs('dashboard.peserta') ? 'bg-white text-[#2D435F]' : ' hover:text-white' }}">

                    <svg class="h-5 w-5 " fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>


                    <span class="ml-4">Master Data Employee</span>
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
            <li>
                <a href="{{ route('matrix.index') }}" class="relative flex items-center px-6 py-3 w-full text-sm font-semibold transition-colors duration-150
                        {{ request()->routeIs('matrix.index') ? 'bg-white text-[#2D435F]' : ' hover:text-white' }}">

                    <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke="none" d="M0 0h24v24H0z" />
                        <path
                            d="M15 21h-9a3 3 0 0 1 -3 -3v-1h10v2a2 2 0 0 0 4 0v-14a2 2 0 1 1 2 2h-2m2 -4h-11a3 3 0 0 0 -3 3v11" />
                        <line x1="9" y1="7" x2="13" y2="7" />
                        <line x1="9" y1="11" x2="13" y2="11" />
                    </svg>
                    <span class="ml-4">Training Matrix</span>
                </a>
            </li>
            <li>
                <a href="{{ route('training-matrix.index') }}"
                    class="relative flex items-center px-6 py-3 w-full text-sm font-semibold transition-colors duration-150
                        {{ request()->routeIs('training-matrix.index') ? 'bg-white text-[#2D435F]' : ' hover:text-white' }}">

                    <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke="none" d="M0 0h24v24H0z" />
                        <circle cx="12" cy="12" r="9" />
                        <path d="M9 12l2 2l4 -4" />
                    </svg>
                    <span class="">Production Competency Matrix</span>
                </a>
            </li>
            
            <li>
                <a href="{{ route('training-evaluation.index') }}"
                    class="relative flex items-center px-6 py-3 w-full text-sm font-semibold transition-colors duration-150
                        {{ request()->routeIs('training-evaluation.index') ? 'bg-white text-[#2D435F]' : ' hover:text-white' }}">

                    <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                        <path
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                        </path>
                    </svg>

                    <span class="ml-4">Training Evaluation</span>
                </a>
            </li>
            <li>
                <a href="{{ route('training-request.index') }}"
                    class="relative flex items-center px-6 py-3 w-full text-sm font-semibold transition-colors duration-150
                        {{ request()->routeIs('training-request.index') ? 'bg-white text-[#2D435F]' : ' hover:text-white' }}">

                    <svg class="h-5 w-5 " viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                        <polyline points="14 2 14 8 20 8" />
                        <line x1="12" y1="18" x2="12" y2="12" />
                        <line x1="9" y1="15" x2="15" y2="15" />
                    </svg>

                    <span class="ml-4">Training Request</span>
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