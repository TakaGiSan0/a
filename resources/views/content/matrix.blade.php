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

    <section class="relative shadow-md sm:rounded-lg overflow-hidden antialiased min-h-[300px]">

        <!-- Start coding here -->
        <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-xl overflow-hidden border border-gray-200">
            <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                <div class="w-full md:w-1/2">
                    <form method="GET" action="{{ route('matrix.index') }}" class="flex items-center">
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
                                placeholder="Badge No/Employee Name" value="{{ request('searchQuery') }}"
                                name="searchQuery">
                        </div>
                    </form>

                </div>
                <div
                    class="w-full relative md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                </div>
                @if (auth()->user()->role == 'Super Admin')
                    <div
                        class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                        <a href="{{ route('export.matrix') }}"
                            class="flex items-center justify-center text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800">
                            <svg class="h-4 w-4 mr-2 text-white-500" width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" />
                                <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" />
                                <polyline points="7 11 12 16 17 11" />
                                <line x1="12" y1="4" x2="12" y2="16" />
                            </svg>
                            Download
                        </a>
                    </div>
                @endif
            </div>
            @if (session('success'))
                <div class="alert alert-success ml-4 dark:bg-gray-800 dark:text-gray-400">
                    {{ session('success') }}
                </div>
            @endif
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
                            <th scope="col" class="px-4 py-3">attachment</th>
                            <th scope="col" class="px-4 py-3">Status</th>
                            @if (auth()->user()->role == 'Super Admin')
                                <th scope="col" class="px-4 py-3">Action</th>
                            @endif
                        </tr>
                    </thead>
                    <?php $no = 0; ?>
                    @foreach ($matrix as $item)
                        <tbody class="text-gray-600 dark:text-gray-200 bg-gray-50 dark:bg-gray-700">
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
                                    {{ $item->trainingrecord->formatted_date_range ?? '-' }}

                                </td>
                                <td class="px-4 py-3">
                                    {{ $item->certificate ?? '-' }}
                                </td>
                                <td class="px-4 py-3">
                                    {{ $item->expired_date ?? '-' }}
                                </td>
                                <td class="px-4 py-3">
                                    {{ $item->category ?? '-' }}
                                </td>
                                <td class="px-4 py-3">
                               
                                    {{-- Periksa apakah variabel $item->attachment tidak null --}}
                                    @if ($item->attachment)
                                        <div class="flex items-center">
                                            {{-- Ikon File --}}
                                            <svg class="h-8 w-8 text-slate-500" width="24" height="24" viewBox="0 0 24 24"
                                                stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" />
                                                <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                                <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                                                <line x1="9" y1="9" x2="10" y2="9" />
                                                <line x1="9" y1="13" x2="15" y2="13" />
                                                <line x1="9" y1="17" x2="15" y2="17" />
                                            </svg>

                                            {{-- Tautan View, dengan sedikit jarak ke kiri (ml-2) --}}
                                            <a href="{{ asset('storage/' . $item->attachment) }}" target="_blank"
                                                class="text-blue-500 underline ml-2">
                                                View
                                            </a>
                                        </div>
                                    @endif
                                
                                </td>
                                <td class="px-4 py-3">
                                    {{ $item->status }}
                                </td>
                                @if (auth()->user()->role == 'Super Admin')
                                    <td class="px-4 py-3 flex items-center justify-center space-x-4">
                                        <button type="button" data-modal-target="readProductModal"
                                            data-modal-toggle="readProductModal" data-id="{{ $item->id }}"
                                            class="trigger-modal items-center justify-center over:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white text-gray-700 dark:text-gray-200">
                                            <svg class="h-8 w-8 text-slate-500" width="24" height="24" viewBox="0 0 24 24"
                                                stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" />
                                                <path d="M4 20h4l10.5 -10.5a1.5 1.5 0 0 0 -4 -4l-10.5 10.5v4" />
                                                <line x1="13.5" y1="6.5" x2="17.5" y2="10.5" />
                                            </svg>
                                        </button>
                                    </td>
                                @endif
                            </tr>
                        </tbody>
                    @endforeach
                </table>
            </div>
            <div class="mt-4">

            </div>
        </div>
    </section>
    <!-- End block -->

    </div>

    <!-- Read modal -->
    <div id="readProductModal" tabindex="-1" aria-hidden="true"
        class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full bg-black bg-opacity-50">
        <div class="relative w-full max-w-2xl max-h-full mx-auto">
            <!-- Modal content -->
            <div class="bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Training Matrix
                    </h3>
                    <button type="button" data-modal-hide="readProductModal"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        aria-label="Close">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
                <!-- Modal body -->
                <form id="commentForm" action="" method="POST" enctype="multipart/form-data">
                    <div class="p-6 space-y-6">
                        @csrf
                        @method('PUT')
                        <div><label for="category"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Certificate No
                            </label>
                            <input type="text" name="certificate" id="certificate"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                        </div>
                        <div><label for="category"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Expired Date
                            </label>
                            <input type="date" name="expired_date" id='expired_date'
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        </div>
                        <div><label for="category"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Category
                            </label>
                            <input type="text" name="category" id='category'
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                        </div>
                        <div>
                            <label for="attachment" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Attachment (Max 5MB)
                            </label>
                            <input type="file" name="attachment" id="attachment" accept=".pdf"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                >
                        </div>

                    </div>
                    <!-- Modal footer -->

                    <div class="flex items-center justify-end p-4 border-t border-gray-200 rounded-b dark:border-gray-600">

                        <button type="submit" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded">Update</button>

                        <button data-modal-hide="readProductModal" type="button"
                            class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:bg-gray-600 dark:hover:text-white">
                            Tutup
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection

<script>

    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('readProductModal');
        const commentForm = modal.querySelector('form');
        const certificateField = modal.querySelector('#certificate');
        const expiredField = modal.querySelector('#expired_date');
        const categoryField = modal.querySelector('#category');
        const editButtons = document.querySelectorAll('.trigger-modal');

        editButtons.forEach(button => {
            button.addEventListener('click', () => {
                const recordId = button.getAttribute('data-id');
                window.baseURL = '{{ url('/') }}'
                // Fetch data dari server
                fetch(window.baseURL + `/matrix/${recordId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.message) {
                            alert(data.message); // Jika ada pesan error
                            return;
                        }

                        // Set form action
                        commentForm.action = window.baseURL + `/matrix/update/${recordId}`;

                        certificateField.value = data.certificate;
                        expiredField.value = data.expired_date;
                        categoryField.value = data.category;

                        // Show modal
                        modal.classList.remove('hidden');
                        modal.classList.add('flex');

                      
                    })
                    .catch(error => {
                        console.error('Error fetching data:', error);
                        alert('Terjadi kesalahan saat mengambil data.');
                    });
            });
        });

        // Close modal
        const closeButton = modal.querySelector('[data-modal-hide]');
        closeButton.addEventListener('click', () => {
            modal.classList.add('hidden');
        });
    });


    function closeModal() {
        // Menutup modal
        document.getElementById('readProductModal').classList.add('hidden');
    }

    // Menutup modal
    document.querySelectorAll('[data-modal-hide="readProductModal"]').forEach(button => {
        button.addEventListener('click', function () {
            const modal = document.getElementById('readProductModal');
            modal.classList.add('hidden');
        });
    });


</script>