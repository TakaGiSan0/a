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
</head>

<style>
    form {
        margin-bottom: 0 !important;
    }
</style>

<body>
    <div class="flex h-screen bg-gray-50 dark:bg-gray-900">
        @yield('sidebar')

        <div class="flex flex-col flex-1 w-full">
            @include('layouts.navbar')

            <div class="flex-1 p-4">
                @yield('content')
            </div>

        </div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.4.1/dist/flowbite.min.js"></script>
</body>
<script>
    document.addEventListener("alpine:init", () => {
        Alpine.data("layout", () => ({
            profileOpen: false,
            asideOpen: true,
        }));
    });

</script>

</html>
