<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @vite('resources/css/app.css')
</head>

<style>
    body {
        background-image: url("{{ asset('images/bg-login-1.png') }}");
        background-size: cover;
        background-repeat: no-repeat;

    }

    ,
    h1 {
        font-size: 64px !important;
        font-style: italic;
    }

    ;
</style>

<body class="bg-white flex h-screen flex-col items-center justify-center">

    <div class="w-full max-w-sm text-center mb-10 leading-none">
        <h1 style="font-size: 64px !important; font-style: italic; font-family: 'Times New Roman', serif;"
            class="text-center font-bold text-blue-600 m-0">
            TMS
        </h1>
        <p class="text-sm text-red-400 font-semibold mb-5 leading-none">
            Training Management System
        </p>
    </div>
    <!-- Form Login -->
    <div class="w-full max-w-sm bg-white shadow-xl rounded-lg p-6">
        <h2 class="text-center text-xl font-semibold text-gray-900 mb-6">Sign in to your account</h2>

        <form action="{{ route('login') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="user" class="block text-sm font-medium text-gray-700">Username</label>
                <input id="user" name="user" type="text" required
                    class="mt-1 block w-full rounded-md border border-gray-300 py-2 px-3 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                @error('user')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input id="password" name="password" type="password" required
                    class="mt-1 block w-full rounded-md border border-gray-300 py-2 px-3 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                @error('password')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit"
                class="w-full bg-indigo-600 text-white py-2 rounded-md hover:bg-indigo-500 focus:ring-2 focus:ring-indigo-400 focus:outline-none font-semibold">
                Sign in
            </button>
        </form>
    </div>
</body>





</html>