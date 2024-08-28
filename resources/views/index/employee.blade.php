@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="container mx-auto">
        <!-- Dashboard Header -->
        <!-- Start block -->
        <section class="bg-gray-50 dark:bg-gray-900 p-3 sm:p-5 antialiased">
            <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
                <!-- Start coding here -->
                <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
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
                                        placeholder="Search" required="" value="{{ request('search') }}" name="badge_no">
                                </div>
                            </form>
                        </div>
                        <div
                            class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">

                            <div class="flex items-center space-x-3 w-full md:w-auto">

                                <form method="GET" action="{{ route('superadmin.employee') }}" class="flex justify-center items-center mx-3">
                                    <select name="dept"
                                        class="w-full md:w-auto flex items-center justify-center py-2 px-4 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                                        <option value="">Semua Departemen</option>
                                        @foreach ($uniqueDepts as $dept)
                                            <option value="{{ $dept }}"
                                                {{ request('dept') == $dept ? 'selected' : '' }}>{{ $dept }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="submit">Filter</button>
                                </form>

                                <div id="filterDropdown"
                                    class="z-10 hidden w-56 p-3 bg-white rounded-lg shadow dark:bg-gray-700">
                                    <ul class="space-y-2 text-sm" aria-labelledby="filterDropdownButton">
                                        <li class="flex items-center">
                                            <input id="apple" type="checkbox" value=""
                                                class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                            <label for="apple"
                                                class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">N/A</label>
                                        </li>
                                        <li class="flex items-center">
                                            <input id="apple" type="checkbox" value=""
                                                class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                                            <label for="apple"
                                                class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">N/A</label>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <?php $no = 0; ?>
                        @foreach ($training_records as $rc)
                            <table class="w-full text-sm text-center text-gray-500 dark:text-gray-400">
                                <thead
                                    class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-4 py-4">No</th>
                                        <th scope="col" class="px-4 py-4">Badge No</th>
                                        <th scope="col" class="px-4 py-3">Emp Name</th>
                                        <th scope="col" class="px-4 py-3">Dept</th>
                                        <th scope="col" class="px-4 py-3">Position</th>
                                        <th scope="col" class="px-4 py-3">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="border-b dark:border-gray-700">
                                        <th scope="row"
                                            class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            {{ ++$no }}</th>
                                        <td class="px-4 py-3">
                                            {{ $rc->peserta->badge_no ?? 'N/A' }}
                                        </td>
                                        <td class="px-4 py-3">
                                            {{ $rc->peserta->employee_name ?? 'N/A' }}

                                        </td>
                                        <td class="px-4 py-3">
                                            {{ $rc->peserta->dept ?? 'N/A' }}

                                        </td>
                                        <td class="px-4 py-3">
                                            {{ $rc->peserta->position ?? 'N/A' }}

                                        </td>
                                        <td class="px-4 py-3 flex items-center justify-center">
                                            <button type="button" data-modal-target="readProductModal"
                                                data-modal-toggle="readProductModal"
                                                onclick="openModal({{ $rc->id }})"
                                                class="flex w-full items-center justify-center py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white text-gray-700 dark:text-gray-200">
                                                <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg"
                                                    viewbox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" />
                                                </svg>
                                                Preview
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                        @endforeach
                        </table>
                        <div class="mt-4">
                            {{ $training_records->links() }}
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
        <div class="relative p-4 w-full max-w-xl max-h-full">
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
                    <div class="grid gap-4 mb-4 sm:grid-cols-2">
                        <div><label for="category"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Emp Name:</label>
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
        function openModal(recordId) {
            controller = new AbortController();

            fetch(`/superadmin/employee/${recordId}`, {
                    signal: controller.signal
                })
                .then(response => response.json())
                .then(data => {
                    // Update detail karyawan di modal
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
                            name: 'Internal Training'
                        },
                        {
                            id: 3,
                            name: 'External Training'
                        },
                        {
                            id: 4,
                            name: 'Project Training'
                        }
                    ];

                    let trainingRecordsContent = '';
                    const groupedRecords = data.grouped_records;

                    categories.forEach(category => {
                        const category_id = category.id;
                        const records = groupedRecords[category_id] ||
                    []; // Ambil data atau set sebagai array kosong jika tidak ada data

                        trainingRecordsContent += `<p>Category Name: ${category.name}</p>
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 mb-7">
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
                            // Jika ada data, tampilkan
                            records.forEach(training => {
                                trainingRecordsContent += `
                          <tr class="border-b dark:border-gray-700">
                                <th scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    ${training.training_name ?? '-'}
                                </th>
                                <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    ${training.trainer_name ?? '-'}
                                </td>
                                <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    ${training.training_date ?? '-'}
                                </td>
                                <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    ${training.level?.level ?? 'N/A'}
                                </td>
                                <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    ${training.final_judgement?.name ?? 'N/A'}
                                </td>
                            </tr>`;
                            });
                        } else {
                            // Jika tidak ada data, tampilkan pesan atau baris kosong
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

                    document.getElementById('trainingCategories').innerHTML = trainingRecordsContent;

                    // Tampilkan modal
                    document.getElementById('modalBody').style.display = 'block';
                })
                .catch(error => {
                    if (error.name === 'AbortError') {
                        console.log('Fetch aborted');
                    } else {
                        console.error('Fetch error:', error);
                    }
                });
        }
    </script>



@endsection
