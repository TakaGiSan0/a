<header class="z-10 py-4 bg-white shadow-md dark:bg-gray-800">

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
        <div class="flex justify-center flex-1 lg:mr-32">
            <div class="relative w-full max-w-xl text-center mr-6 focus-within:text-purple-500">
                <a class="text-lg font-bold text-center text-gray-800 dark:text-gray-200" href="#">Welcome,
                    @auth
                        {{ Auth::user()->name }}
                    @endauth
                </a>
            </div>
        </div>
        <div class="relative ml-3">
            <div class="relative">
                <!-- Profile Button -->
                <button type="button" id="user-menu-button"
                    class="relative flex items-center rounded-full bg-gray-800 text-sm focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800">
                    <img class="w-8 h-8 rounded-full"
                        src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                        alt="User Avatar">
                </button>

                <!-- Dropdown Menu -->
                <div id="user-menu" class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black/5 
                    transition ease-out duration-100 transform opacity-0 scale-95 hidden">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
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
    </script>

</header>