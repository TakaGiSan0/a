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
                <div
                    class="bg-white dark:bg-gray-800 relative shadow-lg sm:rounded-xl overflow-hidden border border-gray-200">
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
                                    <input type="text" id="simple-search" name="search" id="search"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        placeholder="Training Name" value="{{ request('searchQuery') }}">
                                </div>
                            </form>
                        </div>
                        <div
                            class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                            <form action="{{ route('dashboard.index') }}" method="GET" class="flex items-center">
                                <label for="year" class="sr-only">Year</label>
                                <div class="relative">
                                    <select id="year" name="year"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        @foreach ($years as $year)
                                            <option value="{{ $year }}"
                                                {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <button type="submit"
                                    class="ml-2 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Filter</button>
                            </form>
                        </div>
                        @if (auth()->user()->role == 'Super Admin')
                            <div
                                class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                                <button
                                    class="open-modal flex items-center justify-center text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800"
                                    type="button">

                                    <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewbox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                        <path clip-rule="evenodd" fill-rule="evenodd"
                                            d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                                    </svg>Import Excel
                                </button>
                            </div>
                            <div
                                class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                                <a href="{{ route('export.training') }}"
                                    class="flex items-center justify-center text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800">
                                    <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewbox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                        <path clip-rule="evenodd" fill-rule="evenodd"
                                            d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                                    </svg>
                                    <input type="file" name="file" class="hidden" accept=".xlsx,.xls">Export Excel
                                </a>
                            </div>
                        @endif
                        <div
                            class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                            <a href="{{ route('dashboard.create') }}"
                                class="flex items-center justify-center text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewbox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <path clip-rule="evenodd" fill-rule="evenodd"
                                        d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                                </svg>
                                New Event
                            </a>
                        </div>
                    </div>
                    @if (session('success'))
                        <div class="alert alert-success ml-4">
                            {{ session('success') }}
                        </div>
                    @endif
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-center text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-4 py-4">No</th>
                                    <th scope="col" class="px-1 py-1">Doc Name</th>
                                    <th scope="col" class="px-4 py-4">Training Name</th>
                                    <th scope="col" class="px-4 py-3">Training Date</th>
                                    <th scope="col" class="px-4 py-3">Trainer Name</th>
                                    <th scope="col" class="px-4 py-3">Approval</th>
                                    <th scope="col" class="px-4 py-3">Status</th>
                                    <th scope="col" class="px-4 py-3">Action</th>
                                </tr>
                            </thead>
                            <?php $no = ($training_records->currentPage() - 1) * $training_records->perPage(); ?>
                            @foreach ($training_records as $rc)
                                <tbody>
                                    <tr class="border-b dark:border-gray-700">
                                        <!-- Nomor -->
                                        <td class="px-4 py-3">{{ ++$no }}</td>
                                        <td>
                                            <p class="px-4 py-3 text-center">{{ $rc->doc_ref }}</p>
                                        </td>
                                        <!-- Logo dan Nama Training -->
                                        <td>
                                            <p class="px-4 py-3 text-center">{{ $rc->training_name }}</p>
                                        </td>
                                        <!-- Tanggal Training -->
                                        <td class="px-4 py-3 text-center">{{ $rc->training_date }}</td>

                                        <!-- Nama Trainer -->
                                        <td class="px-4 py-3 text-center">{{ $rc->trainer_name }}</td>

                                        <td class="px-4 py-3 text-center"> {{ $rc->approval }}</td>

                                        <!-- Status -->
                                        <td class="px-4 py-3 text-center">{{ $rc->status }}</td>

                                        <!-- Edit dan Delete Actions -->
                                        <td class="px-4 py-3 flex items-center justify-center space-x-4">
                                            <a href="{{ route('dashboard.edit', $rc->id) }}">
                                                <svg class="h-8 w-8 text-slate-500" width="24" height="24"
                                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" />
                                                    <path d="M4 20h4l10.5 -10.5a1.5 1.5 0 0 0 -4 -4l-10.5 10.5v4" />
                                                    <line x1="13.5" y1="6.5" x2="17.5" y2="10.5" />
                                                </svg>
                                            </a>
                                            @if (Auth::user()->role == 'Super Admin')
                                                <form action="{{ route('dashboard.destroy', $rc->id) }}" method="POST"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus peserta ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">
                                                        <svg class="h-8 w-8 text-slate-500" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif
                                            <button type="button" data-modal-target="readProductModal"
                                                data-modal-toggle="readProductModal" data-id="{{ $rc->id }}"
                                                data-comment="{{ $rc->comment }}" data-approval="{{ $rc->approval }}"
                                                data-status="{{ $rc->status }}"
                                                class="trigger-modal items-center justify-center over:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white text-gray-700 dark:text-gray-200">
                                                <svg class="w-8 h-8 flex-shrink-0 text-slate-500"
                                                    xmlns="http://www.w3.org/2000/svg" viewbox="0 0 24 24"
                                                    fill="currentColor" aria-hidden="true">
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
                </div>
            </div>
            <div class="mt-4">
                {{ $training_records->appends(['year' => request('year')])->links() }}
            </div>
        </section>
    </div>


    <div id="uploadModal"
        class="hidden fixed inset-0 p-4 justify-center items-center w-full h-full z-50 overflow-auto font-[sans-serif]">

        <!-- Overlay (background hitam) -->
        <div class="fixed inset-0 bg-black bg-opacity-50"></div>
        <!-- Konten Modal -->
        <div class="w-full max-w-lg bg-white shadow-lg rounded-lg p-6 relative z-10">
            <div class="flex items-center pb-3 border-b border-gray-200">
                <div class="flex-1">
                    <h3 class="text-gray-800 text-xl font-bold">Upload File</h3>
                    <p class="text-gray-600 text-xs mt-1">Upload file to this project</p>
                </div>

                <!-- Tombol Close -->
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="w-3 ml-2 cursor-pointer shrink-0 fill-gray-400 hover:fill-red-500 close-modal"
                    viewBox="0 0 320.591 320.591">
                    <path
                        d="M30.391 318.583a30.37 30.37 0 0 1-21.56-7.288c-11.774-11.844-11.774-30.973 0-42.817L266.643 10.665c12.246-11.459 31.462-10.822 42.921 1.424 10.362 11.074 10.966 28.095 1.414 39.875L51.647 311.295a30.366 30.366 0 0 1-21.256 7.288z"
                        data-original="#000000"></path>
                    <path
                        d="M287.9 318.583a30.37 30.37 0 0 1-21.257-8.806L8.83 51.963C-2.078 39.225-.595 20.055 12.143 9.146c11.369-9.736 28.136-9.736 39.504 0l259.331 257.813c12.243 11.462 12.876 30.679 1.414 42.922-.456.487-.927.958-1.414 1.414a30.368 30.368 0 0 1-23.078 7.288z"
                        data-original="#000000"></path>
                </svg>
            </div>

            <!-- Konten Lainnya -->
            <form action="{{ route('import.training') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class=" border-gray-200 border-dashed mt-6">
                    <!-- Konten Upload -->
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="file_input">Upload
                        file</label>
                    <input
                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                        id="file_input" type="file" name="file" accept=".xlsx,.xls">
                </div>

                <div class="border-t border-gray-200 pt-6 flex justify-between gap-4 mt-6">
                    <button type="button"
                        class="w-full px-4 py-2 rounded-lg text-gray-800 text-sm border-none outline-none tracking-wide bg-gray-200 hover:bg-gray-300 active:bg-gray-200 close-modal">Cancel</button>
                    <button type="submit"
                        class="w-full px-4 py-2 rounded-lg text-white text-sm border-none outline-none tracking-wide bg-blue-600 hover:bg-blue-700 active:bg-blue-600">Import</button>
                </div>
            </form>
        </div>
    </div>

    <div id="readProductModal" tabindex="-1" aria-hidden="true"
        class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full bg-black bg-opacity-50">
        <div class="relative w-full max-w-2xl max-h-full mx-auto">
            <!-- Modal content -->
            <div class="bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Detail Produk
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
                    @csrf
                    @method('PUT')
                    <div class="p-6 space-y-6">
                        <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400" id="modal-content">
                            Komentar
                        </p>
                        @php
                            $isSuperAdmin = auth()->user()->role === 'Super Admin'; //
                        @endphp

                        <textarea name="comment" id="comment" cols="30" rows="10"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-500 focus:border-blue-500 
                        @if (!$isSuperAdmin) text-gray-400 @endif"
                            @if (!$isSuperAdmin) readonly @endif>{{ !$isSuperAdmin ? 'Tunggu komentar dari super admin' : old('comment', $comment ?? '') }}</textarea>
                        <div class="mt-4">
                            <iframe id="modal-attachment" class="w-full h-96" src="" frameborder="0"></iframe>
                        </div>

                    </div>
                    @if (Auth::user()->role == 'Super Admin')
                        <div>
                            <label for="category"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Approval</label>
                            <select id="approval" name="approval"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                <option value="Pending"
                                    {{ old('approval', $approval ?? '') == 'Pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="Approved"
                                    {{ old('approval', $approval ?? '') == 'Approved' ? 'selected' : '' }}>Approved
                                </option>
                                <option value="Reject"
                                    {{ old('approval', $approval ?? '') == 'Reject' ? 'selected' : '' }}>Reject</option>
                            </select>
                        </div>
                        <div>
                            <label for="category"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Status</label>
                            <select id="status" name="status"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                <option value="Pending" {{ old('status', $status ?? '') == 'Pending' ? 'selected' : '' }}>
                                    Pending
                                </option>
                                <option value="Completed"
                                    {{ old('status', $status ?? '') == 'Completed' ? 'selected' : '' }}>Completed
                                </option>
                            </select>
                        </div>
                    @endif
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
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('uploadModal');
        const openModalButtons = document.querySelectorAll(
            '.open-modal'); // Sesuaikan tombol untuk membuka modal
        const closeModalButtons = document.querySelectorAll('.close-modal');
        // Fungsi untuk membuka modal
        openModalButtons.forEach(button => {
            button.addEventListener('click', () => {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            });
        });
        // Fungsi untuk menutup modal
        closeModalButtons.forEach(button => {
            button.addEventListener('click', () => {
                modal.classList.add('hidden');
            });
        });
    });

    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('readProductModal');
        const commentForm = modal.querySelector('form');
        const commentField = modal.querySelector('#comment');
        const approvalField = modal.querySelector('#approval');
        const statusField = modal.querySelector('#status');
        const attachmentFrame = modal.querySelector('#modal-attachment');

        const editButtons = document.querySelectorAll('.trigger-modal');

        editButtons.forEach(button => {
            button.addEventListener('click', () => {
                const recordId = button.getAttribute('data-id');

                // Fetch data dari server
                fetch(`/training-record/${recordId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.message) {
                            alert(data.message); // Jika ada pesan error
                            return;
                        }

                        // Set form action
                        commentForm.action = `/training-record/${recordId}/comment`;

                        // Set field values
                        commentField.value = data.comment || '';
                        approvalField.value = data.approval || '';
                        statusField.value = data.status || '';

                        // Set attachment (PDF)
                        if (data.attachment) {
                            attachmentFrame.src = data.attachment;
                        } else {
                            attachmentFrame.src = '';
                            alert('Attachment tidak tersedia.');
                        }

                        // Show modal
                        modal.classList.remove('hidden');
                        modal.classList.add('flex');
                    })
                    .catch(error => {
                        console.error('Error fetching data:', error);
                        alert('Terjadi kesalahan saat mengambil data.');
                    });
                    console.log(data.attachment);
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
        button.addEventListener('click', function() {
            const modal = document.getElementById('readProductModal');
            modal.classList.add('hidden');
        });
    });
</script>
