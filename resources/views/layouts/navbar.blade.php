<header id="navbar" class="fixed z-10 top-0 left-0 right-0 py-4 bg-white shadow-md dark:bg-gray-800 transition-all duration-300">

    <div class="container flex items-center justify-between px-6 mx-auto dark:text-purple-300">
        <button id="toggleSidebarBtn" class="p-2  text-white rounded-md focus:outline-none">
            <svg class="h-6 w-6 text-slate-500" viewBox="0 0 20 20" stroke-width="2" stroke="currentColor" fill="none"
                stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" />
                <line x1="4" y1="6" x2="20" y2="6" />
                <line x1="4" y1="12" x2="20" y2="12" />
                <line x1="4" y1="18" x2="20" y2="18" />
            </svg>

        </button>
        <!-- Search input -->
        <div class="flex items-center justify-end flex-1">
            <!-- Teks Welcome -->
            <div class="text-lg font-bold text-gray-800 dark:text-gray-200 mr-4">
                @auth
                    {{ Auth::user()->pesertaLogin->employee_name ?? 'User' }}
                @endauth
            </div>

            <!-- Profile Picture & Dropdown -->
            <div class="relative">
                <button type="button" id="user-menu-button"
                    class="relative flex items-center rounded-full bg-gray-800 text-sm focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800">
                    <img class="w-8 h-8 rounded-full bg-white"
                        src="{{ asset('/images/bg-profile.png') }}"
                        onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'User') }}';"
                        alt="User Avatar">
                </button>

                <!-- Dropdown Menu -->
                <div id="user-menu" class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black/5
                    transition ease-out duration-100 transform opacity-0 scale-95 hidden">
                    <button id='theme-toggle'
                        class="w-full flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <svg class="h-5 w-5 text-slate-500" width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" />
                            <path d="M16.2 4a9.03 9.03 0 1 0 3.9 12a6.5 6.5 0 1 1 -3.9 -12" />
                        </svg>
                        Dark Mode
                    </button>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <button type="submit" id="logout-button"
                            class="w-full flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <svg class="w-5 h-5 mr-2 text-red-500" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7">
                                </path>
                            </svg>
                            Log Out
                        </button>

                    </form>
                </div>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const menuButton = document.getElementById("user-menu-button");
            const userMenu = document.getElementById("user-menu");

            menuButton.addEventListener("click", function (event) {
                if (userMenu.classList.contains("hidden")) {
                    userMenu.classList.remove("hidden");
                    userMenu.classList.add("opacity-100", "scale-100");
                    userMenu.classList.remove("opacity-0", "scale-95");
                } else {
                    userMenu.classList.add("opacity-0", "scale-95");
                    userMenu.classList.remove("opacity-100", "scale-100");
                    setTimeout(() => userMenu.classList.add("hidden"), 75); // Delay agar efek transisi berjalan
                }
                event.stopPropagation();
            });

            document.addEventListener("click", function (event) {
                if (!userMenu.contains(event.target) && !menuButton.contains(event.target)) {
                    userMenu.classList.add("opacity-0", "scale-95");
                    userMenu.classList.remove("opacity-100", "scale-100");
                    setTimeout(() => userMenu.classList.add("hidden"), 75);
                }
            });
        });


        document.addEventListener("DOMContentLoaded", function () {
            const toggleSidebarBtn = document.getElementById("toggleSidebarBtn");

            toggleSidebarBtn.addEventListener("click", function () {
                let sidebarState = localStorage.getItem("sidebarHidden");

                if (sidebarState === "true") {
                    localStorage.setItem("sidebarHidden", "false");
                } else {
                    localStorage.setItem("sidebarHidden", "true");
                }

                // Kirim event ke halaman lain (sidebar)
                window.dispatchEvent(new Event("sidebarToggle"));
            });
        });

        document.addEventListener("DOMContentLoaded", function () {
            let themeToggle = document.getElementById("theme-toggle");
            let isLoggedIn = @json(Auth::check()); // Cek apakah user login

            function updateThemeButton() {
                if (document.documentElement.classList.contains("dark")) {
                    themeToggle.innerHTML = `
                    <svg class="h-5 w-5 text-slate-500" width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" />
                        <circle cx="12" cy="12" r="4" />
                        <path d="M3 12h1m8 -9v1m8 8h1m-9 8v1m-6.4 -6.4l.7 .7m12.7 -.7l-.7 .7m-12.7 -.7l.7 .7m12.7 -.7l-.7 .7" />
                    </svg>
                    Light Mode
                `;
                } else {
                    themeToggle.innerHTML = `
                    <svg class="h-5 w-5 text-slate-500" width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" />
                        <path d="M16.2 4a9.03 9.03 0 1 0 3.9 12a6.5 6.5 0 1 1 -3.9 -12" />
                    </svg>
                    Dark Mode
                `;
                }
            }


            // Toggle mode saat tombol diklik
            themeToggle.addEventListener("click", function () {
                document.documentElement.classList.toggle("dark");

                let newTheme = document.documentElement.classList.contains("dark") ? "dark" : "light";

                // Simpan theme hanya jika user login
                if (isLoggedIn) {
                    localStorage.setItem("theme", newTheme);
                }

                updateThemeButton();
            });

            document.getElementById("logout-button").addEventListener("click", function () {
                localStorage.removeItem("theme"); // Hapus data tema

            });
        });
    </script>

</header>
