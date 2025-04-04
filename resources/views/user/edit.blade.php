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
                <form action="{{ route('user.update', $user->id) }}"
                    class="bg-white shadow-md rounded dark:bg-gray-700 px-8 pt-6 pb-8 mb-4 justify-center items-center"
                    method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm dark:text-white font-bold mb-2" for="username">
                            Username
                        </label>
                        <input
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500""
                    name=" user" id="user" type="text" value="{{ old('user', $user->user) }}">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold dark:text-white mb-2 " for="username">
                            Name
                        </label>
                        <input
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            id="name" type="text" name="name" value="{{ old('name', $user->name) }}">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-white text-sm font-bold mb-2" for="username">
                            Departement
                        </label>
                        <input
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500""
                    id=" departement" type="text" name="dept" value="{{ old('dept', $user->dept) }}">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold dark:text-white mb-2" for="username">
                            Role
                        </label>
                        <select id="role" name="role"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                            @if (auth()->user()->role === 'Super Admin')
                                <option value="Super Admin" {{ old('role', $user->role ?? '') === 'Super Admin' ? 'selected' : '' }}>Super Admin</option>
                                <option value="admin" {{ old('role', $user->role ?? '') === 'admin' ? 'selected' : '' }}>Admin
                                </option>
                            @endif
                            <option value="user" {{ old('role', $user->role ?? '') === 'user' ? 'selected' : '' }}>User
                            </option>
                        </select>
                    </div>
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2 dark:text-white" for="password">
                            Password
                        </label>
                        <input
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500""
                    id=" password" type="password" name="password">
                    </div>
                    <div class="flex items-center justify-center">
                        <button
                            class="bg-blue-500  hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                            type="submit">
                            Edit
                        </button>
                        <a href="{{ route('user.index') }}"
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline ml-4">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>