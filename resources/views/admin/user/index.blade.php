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
    <div class="container mx-auto">
        <!-- Dashboard Header -->
        <!-- Start block -->
        <section class="bg-gray-100 dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden antialiased">
            <div class="mx-auto max-w-screen-xl px-4 lg:px-12 py-5">
                <!-- Start coding here -->
                <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                    <div
                        class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                        <div class="w-full md:w-1/2">
                            <form class="flex items-center" method="GET" action="{{ route('admin.peserta') }}">
                                <label for="simple-search" class="sr-only">Search</label>
                                <div class="relative w-full">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400"
                                            fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
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
                            @if (isset($message) && !empty($message))
                                <div class="alert alert-warning">
                                    {{ $message }}
                                </div>
                            @endif

                        </div>
                        <div
                            class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                            <a href="{{ route('admin.user.create') }}">
                                <button type="button" id="createProductModalButton" data-modal-target="createProductModal"
                                    data-modal-toggle="createProductModal"
                                    class="flex items-center justify-center text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                    <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewbox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                        <path clip-rule="evenodd" fill-rule="evenodd"
                                            d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                                    </svg>
                                    Add User
                                </button>
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
                                    <th scope="col" class="px-4 py-3">Action</th>
                                </tr>
                            </thead>
                            @if ($user->isEmpty())
                                <p>{{ $message }}</p>
                            @else
                                <?php $no = ($user->currentPage() - 1) * $user->perPage(); ?>
                                @foreach ($user as $p)
                                    <tbody>
                                        <tr class=>
                                            <th scope="row" name="id"
                                                class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                {{ ++$no }}</th>
                                            <td class="px-4 py-3">{{ $p->user }}</td>
                                            <td class="px-4 py-3">{{ $p->name }}</td>
                                            <td class="px-4 py-3">{{ $p->role }}</td>
                                            <td class="px-4 py-3 flex items-center justify-center">
                                                <a href="{{ route ('admin.user.edit', $p->id)}}">
                                                    <svg class="h-8 w-8 text-slate-500" width="24" height="24"
                                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                        fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" />
                                                        <path d="M4 20h4l10.5 -10.5a1.5 1.5 0 0 0 -4 -4l-10.5 10.5v4" />
                                                        <line x1="13.5" y1="6.5" x2="17.5" y2="10.5" />
                                                    </svg>
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                @endforeach
                        </table>
                        @endif
                    </div>
                </div>
            </div>
            <div class="mt-4">
                {{ $user->links() }}
            </div>
        </section>
        <!-- End block -->
        <!-- Create modal -->

    </div>
@endsection
