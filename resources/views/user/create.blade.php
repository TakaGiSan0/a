<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    @vite('resources/css/app.css')

</head>


<body>
    <div class="flex-1 p-4 bg-gray-100 dark:bg-gray-400">
        <div class="overflow-x-auto shadow-md ">
            <div class="w-full max-w-xs mx-auto">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('user.store') }}"
                    class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 dark:bg-gray-700" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block dark:text-white text-gray-700 text-sm font-bold mb-2 " for="username">
                            Username
                        </label>
                        <input
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            id="user" name="user" type="text" placeholder="Username">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-white text-sm font-bold mb-2" for="username">
                            Name
                        </label>
                        <select
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            id="name" type="text" name="employee_name" placeholder="Name">
                            <option value="">Pilih Peserta</option>
                            @foreach ($pesertaTanpaUser as $peserta)
                                <option value="{{ $peserta->employee_name }}" {{ old('employee') == $peserta->employee_name ? 'selected' : '' }}>
                                    {{ $peserta->employee_name }} - {{ $peserta->badge_no }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @php
                        $role = auth()->user()->role; // Ambil role user yang login
                        $dept = auth()->user()->dept; // Ambil dept user yang login
                    @endphp

                    <div class="mb-4">
                        <label class=" block text-gray-700 text-sm font-bold dark:text-white mb-2" for="username"> Role
                        </label>
                        <select id="role" name="role"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                            @if (auth()->user()->role === 'Super Admin')
                                <option value="Super Admin" {{ old('role', $user->role ?? '') === 'Super Admin' ? 'selected' : '' }}>Super Admin</option>
                                <option value="admin" {{ old('role', $user->role ?? '') === 'admin' ? 'selected' : '' }}>Admin
                                </option>
                            @endif
                            <option name="user">User</option>
                        </select>
                    </div>
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2 dark:text-white" for="password">
                            Password
                        </label>
                        <div class="relative">
                            <input
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                id="password" type="password" name="password" placeholder="Password">
                            <button type="button" id="togglePassword"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5">
                                <svg class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path id="eyeIconOpen" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path id="eyeIconClosed" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="flex items-center justify-center">
                        <button
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded         focus:outline-none focus:shadow-outline"
                            type="submit">
                            Add
                        </button>
                        <a href="{{ route('user.index') }}"
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline ml-4">
                            Cancel
                        </a>
                        @error('user')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                        @error('password')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

<script>
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const eyeIconOpen = document.getElementById('eyeIconOpen');
    const eyeIconClosed = document.getElementById('eyeIconClosed');

    // Sembunyikan ikon mata tertutup secara default
    eyeIconClosed.style.display = 'none';

    togglePassword.addEventListener('click', function (e) {
        // Alihkan tipe input antara 'password' dan 'text'
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);

        // Alihkan ikon mata
        if (type === 'password') {
            eyeIconOpen.style.display = 'block';
            eyeIconClosed.style.display = 'none';
        } else {
            eyeIconOpen.style.display = 'none';
            eyeIconClosed.style.display = 'block';
        }
    });
</script>