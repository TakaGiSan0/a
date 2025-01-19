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
                                        placeholder="Badge No/Employee Name" value="{{ request('searchQuery') }}" name="searchQuery">
                                </div>
                            </form>
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
                                                @foreach ($uniqueDepts as $dept)
                                                    <div class="flex items-center">
                                                        <input type="checkbox" name="dept[]" value="{{ $dept }}"
                                                            id="dept_{{ $dept }}"
                                                            {{ in_array($dept, $deptFilter) ? 'checked' : '' }}
                                                            class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700">
                                                        <label for="dept_{{ $dept }}"
                                                            class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $dept }}</label>
                                                    </div>
                                                @endforeach
                                            </div>

                                            <button type="submit"
                                                class="mt-4 w-full inline-flex items-center justify-center py-2 px-4 text-sm font-medium text-white bg-blue-600 bg-primary-600 rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-4 focus:ring-primary-300">Filter</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
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
                        <div><label for="category"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Emp
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


    <script>
        function BukaModal(id) {
            const controller = new AbortController();

            // Tampilkan modal setelah delay kecil
            function showModal() {
                document.getElementById('modalBody').style.display = 'block';
            }

            // Fungsi untuk menutup modal
            function hideModal() {
                document.getElementById('modalBody').style.display = 'none';
            }

            // Tutup modal sebelum membuka yang baru
            hideModal();

            fetch(`/employee/${id}`, {
                    signal: controller.signal
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Data diterima:', data); // Debugging: lihat data yang diterima

                    // Update detail karyawan di modal
                    document.getElementById('id').innerText = data.peserta.id ?? 'N/A';
                    document.getElementById('employeeName').innerText = data.peserta.employee_name ?? 'N/A';
                    document.getElementById('dept').innerText = data.peserta.dept ?? 'N/A';
                    document.getElementById('badgeNo').innerText = data.peserta.badge_no ?? 'N/A';
                    document.getElementById('position').innerText = data.peserta.position ?? 'N/A';

                    // Data kategori yang sudah didefinisikan
                    const categories = [{
                            id: 1,
                            name: 'New Employee Induction (NEO)'
                        },
                        {
                            id: 2,
                            name: 'Project Training'
                        },
                        {
                            id: 3,
                            name: 'Internal Training'
                        },
                        {
                            id: 4,
                            name: 'External Training'
                        }
                    ];

                    let trainingRecordsContent = '';
                    const groupedRecords = data.grouped_records || {};

                    let hasTraining = false; // Flag untuk mengecek apakah ada training records

                    categories.forEach(category => {
                        const category_id = category.id;
                        const records = groupedRecords[category_id] ||
                    []; // Ambil data atau set sebagai array kosong jika tidak ada data

                        if (records.length > 0) {
                            hasTraining = true; // Set flag jika ada training
                        }

                        trainingRecordsContent += `
                        <p class="text-lg font-bold text-left mb-4">${category.name}</p>
                        <table class="w-full text-sm text-center text-gray-500 dark:text-gray-400 mb-7">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-4 py-4">Training Name</th>
                                    <th scope="col" class="px-4 py-3">Trainer Name</th>
                                    <th scope="col" class="px-4 py-3">Training Date</th>
                                    <th scope="col" class="px-4 py-3">Level</th>
                                    <th scope="col" class="px-4 py-3">Final Judgement</th>
                                </tr>
                            </thead>
                            <tbody>`;

                        if (records.length > 0) {
                            records.forEach(training => {
                                trainingRecordsContent += `
                                <tr class="border-b dark:border-gray-700">
                                    <th scope="row" class="px-4 py-3 font-medium text-gray-900 text-center whitespace-normal break-words dark:text-white overflow-hidden max-w-xs">
                                    ${training.training_name ?? '-'}
                                    </th>
                                    <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap text-center dark:text-white">
                                        ${training.trainer_name ?? '-'}
                                    </td>
                                    <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap text-center dark:text-white">
                                        ${training.training_date ?? '-'}
                                    </td>
                                    <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap text-center dark:text-white">
                                        ${training.pivot.level ?? 'N/A'}
                                    </td>
                                    <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap text-center dark:text-white">
                                        ${training.pivot.final_judgement ?? 'N/A'}
                                    </td>
                                </tr>`;
                            });
                        } else {
                            trainingRecordsContent += `
                            <tr>
                                <td colspan="5" class="px-4 py-3 text-center text-gray-500 dark:text-gray-400">
                                    No training records available for this category.
                                </td>
                            </tr>`;
                        }

                        trainingRecordsContent += `
                            </tbody>
                        </table>`;
                    });

                    // Jika tidak ada training records sama sekali, tampilkan pesan khusus
                    if (!hasTraining) {
                        trainingRecordsContent = `
                        <p class="text-center text-gray-500">This employee has not participated in any training.</p>`;
                    }

                    document.getElementById('trainingCategories').innerHTML = trainingRecordsContent;

                    // Tampilkan modal setelah delay kecil
                    setTimeout(showModal, 100); // Delay 0,5 detik
                })
                .catch(error => {
                    if (error.name === 'AbortError') {
                        console.log('Fetch aborted');
                    } else {
                        console.error('Fetch error:', error);
                    }
                });
        }

        document.getElementById('filterDropdownButton').addEventListener('click', function() {
            const dropdown = document.getElementById('filterDropdown');
            dropdown.classList.toggle('hidden'); // Toggle visibility
        });

        // Optional: Close the dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('filterDropdown');
            const button = document.getElementById('filterDropdownButton');
            if (!button.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.classList.add('hidden');
            }
        });
    </script>



@endsection
