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
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-center text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-4 py-4 ">No</th>
                                    <th scope="col" class="px-4 py-4">Badge No</th>
                                    <th scope="col" class="px-4 py-3">Emp Name</th>
                                    <th scope="col" class="px-4 py-3">Dept</th>
                                    <th scope="col" class="px-4 py-3">Position</th>
                                    <th scope="col" class="px-4 py-3">Action</th>
                                </tr>
                            </thead>
                           
                                <tbody>
                                    <tr class=>
                                        <th scope="row" name="id"
                                            class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                           </th>
                                        <td class="px-4 py-3">
                                           
                                        </td>
                                        <td class="px-4 py-3">
                                           

                                        </td>
                                        <td class="px-4 py-3">
                                    

                                        </td>
                                        <td class="px-4 py-3">
                                           
                                        </td>

                                        <td class="px-4 py-3 flex items-center justify-center">
                                          
                                         
                                        </td>

                                    </tr>
                                </tbody>
                    
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

@endsection
