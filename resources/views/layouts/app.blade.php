<html lang="en" x-data="{ open: false }">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>
    @vite('resources/css/app.css')
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.4.1/dist/flowbite.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.x.x/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css">
    <link rel="stylesheet" href="https://unpkg.com/tailwindcss@2.2.19/dist/tailwind.min.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!--Replace with your tailwind.css once created-->
    <script>
        // Cek theme dari localStorage
        if (localStorage.getItem("theme") === "dark") {
            document.documentElement.classList.add("dark");
        } else {
            document.documentElement.classList.remove("dark");
        }
        updateThemeButton();

    </script>
</head>

<style>
    form {
        margin-bottom: 0 !important;
    }
</style>

<body class="h-screen flex flex-col bg-[#E5E7EB]">
    <div class="relative flex-1 flex bg-white dark:bg-gray-900 w-full">
        <!-- Sidebar -->
        @if(!isset($hideSidebar) || !$hideSidebar)
            <div id="sidebar" class="w-64 text-white h-screen fixed top-0 left-0 transition-all duration-300 z-50">
                @yield('sidebar')
            </div>
        @endif

        <!-- Main Content -->
        <div id="main-content" class="flex-1 transition-all duration-300 ml-0 pt-16 bg-gray-100 dark:bg-gray-400">
            @include('layouts.navbar')

            <div class="flex-1 p-4 bg-gray-100 dark:bg-gray-400">
                <div class="overflow-x-auto shadow-md">
                    @yield('content')
                </div>
            </div>
            @yield('footer')
        </div>
    </div>
</body>

@auth
<script>
(function() {
    // Pengaturan
    const sessionLifetimeInMinutes = {{ config('session.lifetime', 120) }};
    const sessionLifetime = sessionLifetimeInMinutes * 60 * 1000;

    let idleTimer;
    let countdownLogInterval;

    /**
     * Fungsi untuk logout.
     * Karena route 'logout' sudah diubah menjadi GET, kita hanya perlu mengarahkan browser.
     */
    const logoutUser = () => {
        console.warn("WAKTU HABIS! Sesi telah berakhir. Mengarahkan ke halaman logout...");
        // Langsung arahkan ke route logout
        window.location.href = '{{ route('logout') }}';
    };

    /**
     * Fungsi untuk me-reset timer.
     */
    const resetIdleTimer = () => {
        clearTimeout(idleTimer);
        clearInterval(countdownLogInterval);
        
        idleTimer = setTimeout(logoutUser, sessionLifetime);

        let timeLeftInSeconds = sessionLifetimeInMinutes * 60;

        countdownLogInterval = setInterval(() => {
            timeLeftInSeconds--;
            if (timeLeftInSeconds <= 0) {
                clearInterval(countdownLogInterval);
            }
        }, 1000);
    };

    // Daftar event aktivitas pengguna.
    const userActivityEvents = ['load', 'mousemove', 'mousedown', 'click', 'keydown', 'scroll'];

    // Pasang listener untuk setiap event.
    userActivityEvents.forEach(event => {
        window.addEventListener(event, resetIdleTimer, true);
    });

})();
</script>
@endauth
<script src="https://cdn.jsdelivr.net/npm/flowbite@2.4.1/dist/flowbite.min.js"></script>


</html>