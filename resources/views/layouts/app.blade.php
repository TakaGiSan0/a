<!DOCTYPE html>
<html lang="en" x-data="{ open: false }">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>
    @vite('resources/css/app.css')
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.4.1/dist/flowbite.min.css" rel="stylesheet" />

</head>

<body class="bg-gray-100 font-sans antialiased">

    <!-- Navbar -->
    @include("layouts.navbar")

    <!-- Sidebar -->
    <div class="flex h-screen overflow-hidden pt-16">
        @include("layouts.sidebar")
        <!-- Main Content -->
        <div class="flex flex-col flex-1 overflow-y-auto bg-gray-50">
            <main class="p-6">
                @yield('content')
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.4.1/dist/flowbite.min.js"></script>
</body>

</html>
