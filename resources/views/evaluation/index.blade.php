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
                @if (auth()->user()->role == 'Super Admin')
                    <div class="w-full md:w-1/2">
                        <form action="{{ route('training-evaluation.index') }}" method="GET" class="mb-4">
                            <div class="flex">
                                <input type="text" name="search" placeholder="Cari Nama Pegawai atau Badge No."
                                    value="{{ request('search') }}" {{-- Menjaga nilai pencarian sebelumnya --}}
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">

                            </div>
                        </form>

                    </div>
                    <div
                        class="w-full relative md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                    </div>

                    <div
                        class="w-full relative md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">

                        <div class="relative inline-block text-left">
                            <div>
                                <button id="filterDropdownButton" type="button"
                                    class="w-full md:w-auto flex items-center justify-center py-2 px-4 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-primary-700 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
                                    aria-haspopup="true" aria-expanded="true">
                                    Dept
                                    <svg class="-mr-1 ml-1.5 w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                        <path clip-rule="evenodd" fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                                    </svg>
                                </button>
                            </div>

                            <div id="filterDropdown"
                                class="absolute right-0 mt-2 w-56 origin-top-right rounded-lg bg-white shadow-lg ring-1 ring-black ring-opacity-5 overflow-auto max-h-96 hidden"
                                style="z-index: 9999;">
                                <form method="GET" action="{{ url()->current() }}">
                                    <div class="p-4">
                                        <div>

                                            <div class="flex items-center">
                                                <input type="checkbox" name="dept[]" value="" id="dept_"
                                                    class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700">
                                                <label for="dept_"
                                                    class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100"></label>
                                            </div>

                                        </div>

                                        <button type="submit"
                                            class="mt-4 w-full inline-flex items-center justify-center py-2 px-4 text-sm font-medium text-white bg-blue-600 bg-primary-600 rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-4 focus:ring-primary-300">Filter</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            @if (session('success'))
                <div class="alert alert-success ml-4">
                    {{ session('success') }}
                </div>
            @endif
            <div class="overflow-x-auto">
                @if($trainingEvaluations->isEmpty())
                    <p class="text-center text-gray-600">Tidak ada evaluasi yang ditemukan.</p>
                @else

                    <table class="w-full text-sm text-center text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-4 py-4 ">No</th>
                                <th scope="col" class="px-4 py-4">Badge No</th>
                                <th scope="col" class="px-4 py-3">Emp Name</th>
                                <th scope="col" class="px-4 py-3">Dept</th>
                                <th scope="col" class="px-4 py-3">Training Name</th>
                                <th scope="col" class="px-4 py-3">Training Date</th>
                                <th scope="col" class="px-4 py-3">Trainer Name</th>
                                <th scope="col" class="px-4 py-3">Status</th>
                                <th scope="col" class="px-4 py-3">Action</th>

                            </tr>
                        </thead>

                        <?php    $no = 0; ?>
                        @foreach ($trainingEvaluations as $hasil)
                            <tbody class="text-gray-600 dark:text-gray-200 bg-gray-50 dark:bg-gray-700">
                                <tr class=>
                                    <th scope="row" name="id"
                                        class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ ++$no }}
                                    </th>
                                    <td class="px-4 py-3">
                                        {{ $hasil->hasilPeserta->pesertas->badge_no ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3">
                                        {{ $hasil->hasilPeserta->pesertas->employee_name ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3">
                                        {{ $hasil->hasilPeserta->pesertas->dept ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3">
                                        {{ $hasil->hasilPeserta->trainingrecord->training_name ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3">
                                        {{ $hasil->hasilPeserta->trainingrecord->formatted_date_range ?? '-' }}

                                    </td>
                                    <td class="px-4 py-3">
                                        {{ $hasil->hasilPeserta->trainingrecord->trainer_name ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3">
                                        {{ $hasil->status ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3 flex items-center justify-center space-x-4">
                                        @if ($hasil->status != 'Completed' || (Auth::user()->role == 'Super Admin'))
                                            <button type="button" data-modal-target="readProductModal" data-id="{{ $hasil->id }}"
                                                class="trigger-modal items-center justify-center over:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white text-gray-700 dark:text-gray-200">
                                                <svg class="h-8 w-8 text-slate-500" width="24" height="24" viewBox="0 0 24 24"
                                                    stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" />
                                                    <path d="M4 20h4l10.5 -10.5a1.5 1.5 0 0 0 -4 -4l-10.5 10.5v4" />
                                                    <line x1="13.5" y1="6.5" x2="17.5" y2="10.5" />
                                                </svg>
                                            </button>
                                        @endif
                                    </td>

                                </tr>
                            </tbody>
                        @endforeach
                    </table>
                @endif
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
                        Evaluation Form
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
                        <div><label for="category" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">1.
                                What has you learn and how to apply this knowledge at work place?<br>
                                [Apa yang anda pelajari dan bagaimana menerapkan di tempat kerja Anda?]
                            </label>
                            <textarea type="text" name="question_1" id="question_1"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"></textarea>
                        </div>
                        <div><label for="category" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">2.
                                What is the purpose from this course?<br>
                                [Apakah tujuan dari pelatihan ini?]
                            </label>
                            <textarea type="text" name="question_2" id="question_2"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"></textarea>
                        </div>
                        <div><label for="category" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">3.
                                Mention the example of Implementation in your working area ?<br>
                                [sebutkan contoh penerapan di area kerja anda]
                            </label>
                            <textarea type="text" name="question_3" id="question_3"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"></textarea>
                        </div>
                        <div><label for="category" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">4.
                                Mention the content that was important from the topic<br>
                                [Sebutkan konten yang penting dari topik tersebut]
                            </label>
                            <textarea type="text" name="question_4" id="question_4"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"></textarea>
                        </div>
                        <div><label for="category" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">5.
                                Please give suggestion/comment how to improve this course!<br>
                                [Silahkan beri komentar bagaimana training ini dapat di perbaiki!]
                            </label>
                            <textarea type="text" name="question_5" id="question_5"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"></textarea>
                        </div>

                        @if (Auth::user()->role == 'Super Admin')
                            <div>
                                <label for="category"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Status</label>
                                <select id="status" name="status"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                    <option value="Waiting Approval" {{ old('status', $status ?? '') == 'Waiting Approval' ? 'selected' : '' }}>Waiting
                                        Approval
                                    </option>
                                    <option value="Pending" {{ old('status', $status ?? '') == 'Pending' ? 'selected' : '' }}>
                                        Pending
                                    </option>
                                    <option value="Completed" {{ old('status', $status ?? '') == 'Completed' ? 'selected' : '' }}>
                                        Completed
                                    </option>
                                </select>
                            </div>
                        @endif

                    </div>
                    <!-- Modal footer -->

                    <div class="flex items-center justify-end p-4 border-t border-gray-200 rounded-b dark:border-gray-600">

                        <button type="submit" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded">Submit</button>

                        <button data-modal-hide="readProductModal" type="button"
                            class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-blue-300 mt-4 font-medium rounded-lg text-sm px-5 py-2.5 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:bg-gray-600 dark:hover:text-white">
                            Tutup
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <script>
        $(document).ready(function () {
            // Fungsi untuk membuka modal dan mengisi data
            $('.trigger-modal').on('click', function () {
                const id = $(this).data('id');
                window.baseURL = '{{ url('/') }}'
                
                $.ajax({
                    url:  window.baseURL +'/evaluation/' + id + '/edit',
                    type: 'GET',
                    success: function (data) {
                        console.log(data); // Untuk debug

                        // Isi form
                        $('#question_1').val(data.evaluation.question_1);
                        $('#question_2').val(data.evaluation.question_2);
                        $('#question_3').val(data.evaluation.question_3);
                        $('#question_4').val(data.evaluation.question_4);
                        $('#question_5').val(data.evaluation.question_5);
                        $('#status').val(data.evaluation.status);

                        // Atur izin untuk field isian
                        if (data.permissions.can_edit_fields) {
                            $('#commentForm textarea').prop('readonly', false);
                        } else {
                            $('#commentForm textarea').prop('readonly', true);
                        }

                        // Atur izin untuk dropdown status
                        if (data.permissions.can_edit_status) {
                            $('#status').prop('disabled', false);
                        } else {
                            $('#status').prop('disabled', true);
                        }

                        // Atur action form
                        $('#commentForm').attr('action', '/evaluation/update/' + id);

                        // Tampilkan modal menggunakan jQuery
                        $('#readProductModal').show();
                    },
                    error: function () {
                        alert('Gagal mengambil data.');
                    }
                });
            });

            // --- PERBAIKAN TOMBOL CLOSE DI SINI ---
            // Fungsi untuk menutup modal, menargetkan SEMUA tombol close yang benar
            // Termasuk tombol 'x' dan tombol 'Tutup' yang menggunakan atribut data-modal-hide
            $('[data-modal-hide="readProductModal"]').on('click', function () {
                // Sembunyikan modal menggunakan jQuery agar konsisten
                $('#readProductModal').hide();
            });
        });

        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('readProductModal');

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

@endsection