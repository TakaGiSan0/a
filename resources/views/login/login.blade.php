<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-[#E5E7EB] flex h-screen items-center justify-center">
    <div class="w-full max-w-sm bg-white shadow-lg rounded-lg p-8">
        <h2 class="text-center text-2xl font-bold text-gray-900">Sign in to your account</h2>

        <form class="mt-6 space-y-4" action="{{ route('login') }}" method="POST">
            @csrf

            <!-- Username Field -->
            <div>
                <label for="user" class="block text-sm font-medium text-gray-700">Username</label>
                <input id="user" name="user" type="text" required
                    class="mt-1 block w-full rounded-md border border-gray-300 bg-gray-50 py-2 px-3 text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @error('user')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <!-- Password Field -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input id="password" name="password" type="password" required
                    class="mt-1 block w-full rounded-md border border-gray-300 bg-gray-50 py-2 px-3 text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @error('password')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit"
                    class="w-full rounded-md bg-indigo-600 py-2 text-sm font-semibold text-white shadow-md hover:bg-indigo-500 focus:ring-2 focus:ring-indigo-400 focus:outline-none">
                    Sign in
                </button>
            </div>

            @if (session('message'))
                <div class="mt-4 text-center text-sm text-red-500">
                    {{ session('message') }}
                </div>
            @endif
        </form>
    </div>
</body>

</html>
