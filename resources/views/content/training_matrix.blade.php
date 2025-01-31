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
                            <?php $no = ($peserta_records->currentPage() - 1) * $peserta_records->perPage(); ?>
                            @foreach ($peserta_records as $rc)
                                <tbody>
                                    <tr class=>
                                        <th scope="row" name="id"
                                            class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            {{ ++$no }}</th>
                                        <td class="px-4 py-3">
                                            {{ $rc->badge_no ?? 'N/A' }}
                                        </td>
                                        <td class="px-4 py-3">
                                            {{ $rc->employee_name ?? 'N/A' }}

                                        </td>
                                        <td class="px-4 py-3">
                                            {{ $rc->dept ?? 'N/A' }}

                                        </td>
                                        <td class="px-4 py-3">
                                            {{ $rc->position ?? 'N/A' }}
                                        </td>

                                        <td class="px-4 py-3 flex items-center justify-center">
                                            @if (in_array(Auth::user()->role, ['Super Admin', 'Admin']))
                                                <a href="{{ route('download.employee', $rc->id) }}">
                                                    <button type="button" data-modal-target="updateProductModal"
                                                        data-modal-toggle="updateProductModal"
                                                        class="flex items-center justify-center py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white text-gray-700 dark:text-gray-200">
                                                        <svg class="w-5 h-5 flex-shrink-0"
                                                            xmlns="http://www.w3.org/2000/svg" viewbox="0 0 20 20"
                                                            fill="currentColor" aria-hidden="true">
                                                            <path
                                                                d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                                d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" />
                                                        </svg>
                                                    </button>
                                                </a>
                                            @endif

                                            <button type="button" data-modal-target="readProductModal"
                                                data-modal-toggle="readProductModal"
                                                onclick="BukaModal({{ $rc->id }})"
                                                class="flex items-center justify-center py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white text-gray-700 dark:text-gray-200">
                                                <svg class="w-5 h-5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg"
                                                    viewbox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" />
                                                </svg>
                                            </button>
                                        </td>

                                    </tr>
                                </tbody>
                            @endforeach
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $peserta_records->appends(['dept' => request('dept')])->links() }}
                    </div>
                </div>
            </div>
    </div>
    </section>
    <!-- End block -->

    </div>

@endsection
