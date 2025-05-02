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
            <div id="sidebar" class="w-64 text-white h-screen fixed top-0 left-0 transition-all duration-300 Z-50">
                @yield('sidebar')
            </div>
        @endif

        <!-- Main Content -->
        <div id="main-content" class="flex-1 transition-all duration-300 ml-0 md:ml-64 bg-gray-100 dark:bg-gray-400">
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

<script src="https://cdn.jsdelivr.net/npm/flowbite@2.4.1/dist/flowbite.min.js"></script>
<script>
    document.addEventListener("alpine:init", () => {
        Alpine.data("layout", () => ({
            profileOpen: false,
            asideOpen: true,
        }));
    });

</script>

</html>