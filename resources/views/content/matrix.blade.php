@extends('layouts.app')

@section('title', 'Dashboard')

@section('sidebar')
    @if (auth()->user()->role == 'Super Admin')
        @include('sidebar.superadmin.sidebar')
    @elseif(auth()->user()->role == 'Admin')
        @include('sidebar.admin.sidebar')
    @elseif(auth()->user()->role == 'User')
        @include('sidebar.user.sidebar')
    @endif
@endsection

@section('content')
    <div class="container mx-auto">
        <!-- Dashboard Header -->
        <!-- Start block -->
        <section
            class="bg-gray-100 dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden antialiased min-h-[300px]">
            <div class="mx-auto max-w-screen-xl px-4 lg:px-12 py-5">
                <!-- Start coding here -->
                <div
                    class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-xl overflow-hidden border border-gray-200">
                    <div
                        class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                        <div class="w-full md:w-1/2">
                            <form class="flex items-center">
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
                                        placeholder="Badge No/Employee Name" value="" name="searchQuery">
                                </div>
                            </form>
                        </div>
                        <div
                            class="w-full relative md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">


                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-center text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-4 py-4 ">No</th>
                                    <th scope="col" class="px-4 py-4">Badge No</th>
                                    <th scope="col" class="px-4 py-3">Emp Name</th>
                                    <th scope="col" class="px-4 py-3">Dept</th>
                                    <th scope="col" class="px-4 py-3">Training Name</th>
                                    <th scope="col" class="px-4 py-3">Training Date</th>
                                    <th scope="col" class="px-4 py-3">Certificate No</th>
                                    <th scope="col" class="px-4 py-3">Expired Date</th>
                                    <th scope="col" class="px-4 py-3">Category</th>
                                    <th scope="col" class="px-4 py-3">Status</th>
                                    <th scope="col" class="px-4 py-3">Action</th>
                                </tr>
                            </thead>
                            @foreach ($matrix as $item)
                                @php $no = 0; @endphp
                                <tbody>
                                    <tr class=>
                                        <th scope="row" name="id"
                                            class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            {{ ++$no }}
                                        </th>
                                        <td class="px-4 py-3">
                                            {{ $item->pesertas->badge_no ?? '-' }}

                                        </td>
                                        <td class="px-4 py-3">
                                            {{ $item->pesertas->employee_name ?? '-' }}

                                        </td>
                                        <td class="px-4 py-3">
                                            {{ $item->pesertas->dept ?? '-' }}

                                        </td>
                                        <td class="px-4 py-3">
                                            {{ $item->trainingrecord->training_name ?? '-' }}

                                        </td>
                                        <td class="px-4 py-3">
                                            {{ $item->trainingrecord->date_start ?? '-' }} - 
                                            {{ $item->trainingrecord->date_end ?? '-' }}

                                        </td>
                                        <td class="px-4 py-3">

                                        </td>
                                        <td class="px-4 py-3">

                                        </td>
                                        <td class="px-4 py-3">

                                        </td>
                                        <td class="px-4 py-3">

                                        </td>
                                        <td class="px-4 py-3 flex items-center justify-center space-x-4">
                                            <a href="">
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
                    </div>
                    <div class="mt-4">

                    </div>
                </div>
            </div>
    </div>
    </section>
    <!-- End block -->

    </div>

    <!-- Read modal -->
    <div id="readProductModal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-6xl max-h-full">
            <!-- Modal content -->
            <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
                <!-- Modal header -->
                <div class="flex justify-between mb-4 rounded-t sm:mb-5">
                    <div class="text-lg text-gray-900 md:text-xl dark:text-white">
                        Detail Training Record
                    </div>
                    <div>
                        <button type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 inline-flex dark:hover:bg-gray-600 dark:hover:text-white"
                            data-modal-toggle="readProductModal" onclick="closeModal()">
                            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewbox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                </div>
                <!-- Modal body -->
                <div id="modalBody">
                    <div class="grid gap-4 mb-4 sm:grid-cols-2 text-center">
                        <span id="id" hidden>N/A</span>
                        <div><label for="category" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Emp
                                Name:</label>
                            <span id="employeeName">N/A</span>
                        </div>
                        <div><label for="category"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Dept:</label>
                            <span id="dept">N/A</span>
                        </div>
                        <div><label for="category"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Badge No:</label>
                            <span id="badgeNo">N/A</span>
                        </div>
                        <div><label for="category"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Position:</label>
                            <span id="position">N/A</span>
                        </div>
                    </div>
                    <div id="trainingCategories">
                        <!-- Training categories and tables will be filled by JavaScript -->
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
