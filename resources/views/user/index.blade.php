@extends('layouts.app')

@section('title', 'Dashboard')

@section('sidebar')
    @if (auth()->user()->role == 'Super Admin')
        @include('sidebar.superadmin.sidebar')
    @elseif(auth()->user()->role == 'Admin')
        @include('sidebar.admin.sidebar')
    @elseif(auth()->user()->role == 'user')
        @include('sidebar.user.sidebar')
    @endif
@endsection

@section('content')

    <section class="relative shadow-md sm:rounded-lg overflow-hidden antialiased">

        <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
            <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                <div class="w-full md:w-1/2">
                    <form class="flex items-center" method="GET" action="{{ route('dashboard.peserta') }}">
                        <label for="simple-search" class="sr-only">Search</label>
                        <div class="relative w-full">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor"
                                    viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input type="text" id="simple-search"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Search" name="badge_no">
                        </div>
                    </form>
                    @if ($message)
                        <p>{{ $message }}</p>
                    @endif

                </div>
                <div
                    class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                    <a href="{{ route('user.create') }}"
                        class="flex items-center justify-center text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                        <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewbox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path clip-rule="evenodd" fill-rule="evenodd"
                                d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                        </svg>
                        Add User
                    </a>
                </div>
            </div>
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-center text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-4 py-4">No</th>
                            <th scope="col" class="px-4 py-4">Username</th>
                            <th scope="col" class="px-4 py-3">Name</th>
                            <th scope="col" class="px-4 py-3">Role</th>
                            <th scope="col" class="px-4 py-3">Department</th>
                            @php

                                $canViewActionColumn = (auth()->user()->role === 'Super Admin') ||
                                    (auth()->user()->role === 'Admin' );
                            @endphp
                            @if ($canViewActionColumn)
                                <th scope="col" class="px-4 py-3">Action</th>
                            @endif
                        </tr>
                    </thead>
                    @if ($user->isEmpty())
                        <p>{{ $message }}</p>
                    @else
                        <?php    $no = ($user->currentPage() - 1) * $user->perPage(); ?>
                        @foreach ($user as $p)
                            <tbody class="text-gray-600 dark:text-gray-200 bg-gray-50 dark:bg-gray-700">
                                <tr class=>
                                    <th scope="row" name="id"
                                        class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ ++$no }}
                                    </th>
                                    <td class="px-4 py-3">{{ $p->user }}</td>
                                    <td class="px-4 py-3">{{ $p->pesertaLogin->employee_name }}</td>
                                    <td class="px-4 py-3">{{ $p->role }}</td>
                                    <td class="px-4 py-3">{{ $p->pesertaLogin->dept }}</td>
                                    <td class="relative px-4 py-3 text-center">
                                    @if ($p->can_be_edited)
                                            <!-- Trigger & Dropdown wrapper -->
                                            <div class="inline-block text-left">
                                                <!-- Button -->
                                                <button onclick="toggleDropdown(event, this)"
                                                    class="hover:bg-gray-100 dark:hover:bg-gray-600 rounded-full p-2">
                                                    <!-- SVG icon -->
                                                    <svg class="h-6 w-6 text-gray-500" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <circle cx="12" cy="12" r="1" />
                                                        <circle cx="12" cy="5" r="1" />
                                                        <circle cx="12" cy="19" r="1" />
                                                    </svg>
                                                </button>
                                                <div
                                                    class="dropdown-menu hidden absolute top-0 right-full ml-2 bg-white border rounded shadow-md z-50 w-32">

                                                    <!-- Button Edit -->

                                                    <a href="{{ route('user.edit', $p->id) }}"
                                                        class="w-full flex items-center gap-2 text-left px-4 py-2 hover:bg-gray-100">
                                                        <svg class="h-4 w-4 text-blue-500" fill="none" stroke="currentColor"
                                                            stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round"
                                                            stroke-linejoin="round">
                                                            <path d="M12 20h9" />
                                                            <path d="M16.5 3.5a2.121 2.121 0 013 3L7 19l-4 1 1-4 12.5-12.5z" />
                                                        </svg>
                                                        Edit
                                                    </a>


                                                    <form action="{{ route('user.destroy', $p->id) }}" method="POST"
                                                        onsubmit="return confirm('Are you sure you want to delete this user?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button
                                                            class="w-full flex items-center gap-2 text-left px-4 py-2 text-red-600 hover:bg-red-100">
                                                            <svg class="h-4 w-4 text-red-600" fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                            Delete
                                                        </button>
                                                    </form>

                                                </div>
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            </tbody>
                        @endforeach
                    @endif
                </table>
            </div>
        </div>

        <div class="mt-4">
            {{ $user->links() }}
        </div>
    </section>

    <script>
        function toggleDropdown(event, btn) {
            event.stopPropagation(); // cegah event bubbling
            const dropdown = btn.nextElementSibling;
            const allDropdowns = document.querySelectorAll('.dropdown-menu');

            allDropdowns.forEach(d => {
                if (d !== dropdown) d.classList.add('hidden');
            });

            dropdown.classList.toggle('hidden');
        }

        // Tutup dropdown saat klik di luar
        document.addEventListener('click', function (e) {
            if (!e.target.closest('.dropdown-menu') && !e.target.closest('button')) {
                document.querySelectorAll('.dropdown-menu').forEach(d => d.classList.add('hidden'));
            }
        });
    </script>
@endsection