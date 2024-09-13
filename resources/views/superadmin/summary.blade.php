@extends('layouts.app')

@section('title', 'Dashboard')

@section('sidebar')
    @include('superadmin.sidebar.sidebar')
@endsection

@section('content')
    <div class="container mx-auto">
        <!-- Dashboard Header -->
        <!-- Start block -->
        <section class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden antialiased">
            <div class="mx-auto max-w-screen-xl px-4 lg:px-12 py-3">
                <!-- Start coding here -->

                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-lg">
                    <form action="">
                        <div class="relative mb-10 w-full flex  items-center justify-between rounded-md">
                            <svg class="absolute left-2 block h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8" class=""></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65" class=""></line>
                            </svg>
                            <input type="text" name="search" id="search"
                                class="h-12 w-full cursor-text rounded-md border border-gray-100 bg-gray-100 py-4 pr-40 pl-12 shadow-sm outline-none focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                placeholder="Training Name" />
                        </div>
                    </form>
                    <form id="filterForm" method="get" class="">

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-4">
                            <div class="flex flex-col">
                                <label for="manufacturer" class="text-sm font-medium text-stone-600">Dept</label>

                                <select name="manufacturer" id="dept"
                                    class="mt-2 block w-full rounded-md border border-gray-100 bg-gray-100 px-2 py-2 shadow-sm outline-none focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <option>N/A</option>
                                    <option>N/A</option>
                                    <option>N/A</option>
                                </select>
                            </div>

                            <div class="flex flex-col">
                                <label for="manufacturer" class="text-sm font-medium text-stone-600">Station</label>

                                <select name="station" id="station"
                                    class="mt-2 block w-full rounded-md border border-gray-100 bg-gray-100 px-2 py-2 shadow-sm outline-none focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    @foreach ($training_records as $rc)
                                        <option value="{{ $rc->station }}">{{ $rc->station }}</option>
                                    @endforeach

                                </select>
                            </div>

                            <div class="flex flex-col">
                                <label for="date" class="text-sm font-medium text-stone-600">Training Date</label>
                                <input type="date" id="tanggal" name="tanggal"
                                    class="mt-2 block w-full cursor-pointer rounded-md border border-gray-100 bg-gray-100 px-2 py-2 shadow-sm outline-none focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" />
                            </div>

                            <div class="flex flex-col">
                                <label for="status" class="text-sm font-medium text-stone-600">Training Category</label>

                                <select name="training_category" id="training_category"
                                    class="mt-2 block w-full cursor-pointer rounded-md border border-gray-100 bg-gray-100 px-2 py-2 shadow-sm outline-none focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    @foreach ($training_records as $training_category)
                                        <option value="{{ $training_category->id }}">{{ $training_category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Cari</button>
                    </form>
                </div>

                <div class="overflow-x-auto mt-4">
                    @php
                        $uniqueRecords = $training_records->unique('doc_ref');
                    @endphp
                    <?php $no = 0;
                    $n = 0; ?>
                    @foreach ($uniqueRecords as $rc)
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-4 py-4">No</th>
                                    <th scope="col" class="px-4 py-4">Doc. Ref</th>
                                    <th scope="col" class="px-4 py-3">Training Category</th>
                                    <th scope="col" class="px-4 py-3">Training Name</th>
                                    <th scope="col" class="px-4 py-3">Dept</th>
                                    <th scope="col" class="px-4 py-3">Station</th>
                                    <th scope="col" class="px-4 py-3">Trainer Name</th>
                                    <th scope="col" class="px-4 py-3">Training Date</th>
                                    <th scope="col" class="px-4 py-3">Event Number</th>
                                    <th scope="col" class="px-4 py-3">Action</th>

                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b dark:border-gray-700">
                                    <td scope="row" class="px-4 py-3 ">
                                        {{ ++$no }}</td>
                                    <td class="px-4 py-3">{{ $rc->doc_ref }}</td>
                                    <td class="px-4 py-3">{{ $rc->trainingCategory->name ?? 'N/A' }}</td>
                                    <td class="px-4 py-3">{{ $rc->training_name }}</td>
                                    <td class="px-4 py-3">{{ $rc->peserta->dept ?? 'N/A' }}</td>
                                    <td class="px-4 py-3">{{ $rc->station }}</td>
                                    <td class="px-4 py-3">{{ $rc->trainer_name ?? 'N/A' }}</td>
                                    <td class="px-4 py-3">{{ $rc->training_date ?? 'N/A' }}</td>
                                    <td class="px-4 py-3">TR-0{{ $rc->id ?? 'N/A' }}</td>
                                    <td class="px-4 py-3 flex items-center justify-">
                                        <button type="button" data-modal-target="updateProductModal"
                                            data-modal-toggle="updateProductModal"
                                            class="flex w-full items-center py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white text-gray-700 dark:text-gray-200">
                                            <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg"
                                                viewbox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path
                                                    d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" />
                                            </svg><a href="{{ url('generator', $rc->id) }}">

                                            </a>
                                        </button>

                                        <button type="button" data-modal-target="readProductModal"
                                            data-modal-toggle="readProductModal" onclick="openModal({{ $rc->id }})"
                                            class="flex w-full items-center justify-center py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white text-gray-700 dark:text-gray-200">
                                            <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg"
                                                viewbox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" />
                                            </svg>
                                            
                                        </button>

                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    @endforeach
                </div>
            </div>
    </div>


    <!-- Read modal -->
    <div id="readProductModal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-2xl max-h-full">
            <!-- Modal content -->
            <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
                <!-- Modal header -->
                <div class="flex justify-between items-center pb-4 mb-4 rounded-t border-b sm:mb-5 dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Form</h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-target="createProductModal" data-modal-toggle="createProductModal">
                        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewbox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div id="modalBody">
                    <!-- Konten akan diisi secara dinamis melalui JavaScript -->
                </div>
            </div>
        </div>
    </div>
    <!-- Delete modal -->
    <script>
        let abortController;

        document.getElementById('filterForm').addEventListener('submit', function(e) {
            // Ambil semua input dan select dalam form
            const inputs = this.querySelectorAll('input, select');
        });

        inputs.forEach(input => {
            // Jika value kosong atau default, hapus attribute name sehingga tidak akan dikirim
            if (!input.value || input.value === 'N/A') {
                input.removeAttribute('name');
            }
        });


        function openModal(id) {
            // Abort any ongoing requests
            if (abortController) {
                abortController.abort();
            }

            // Create a new AbortController for the new request
            abortController = new AbortController();

            // Hide the modal before opening a new one
            hideModal();

            fetch(`/superadmin/summary/${id}`, {
                    signal: abortController.signal,
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }

                    return response.json();
                })
                .then(data => {
                    const trainingList = data.map(record => `
                    <div>
                        <h3>Training Name: ${record.training_name}</h3>
                        <p>Doc Ref: ${record.doc_ref}</p>
                        <p>License: ${record.license}</p>
                        <p>Job Skill: ${record.job_skill}</p>
                        <p>Trainer Name: ${record.trainer_name}</p>
                        <p>Rev: ${record.rev}</p>
                        <p>Station: ${record.station}</p>
                        <p>Skill Code: ${record.skill_code}</p>
                        <p>Training Date: ${record.training_date}</p>
                        ${record.status === 'Pending' ? '<p class="text-yellow-600">This training is pending. Please complete the requirements.</p>' : ''}
                        <h4>Peserta:</h4>
                        <ul>
                            ${record.peserta.map(peserta => `
                                                        <li>
                                                            ${peserta.employee_name} (Badge No: ${peserta.badge_no}, Dept: ${peserta.dept}, Position: ${peserta.position})
                                                            <ul>
                                                                <li>Theory Result: ${peserta.pivot.theory_result || 'N/A'}</li>
                                                                <li>Practical Result: ${peserta.pivot.practical_result || 'N/A'}</li>
                                                                <li>Level : ${peserta.pivot.level || 'N/A'}</li>
                                                                <li>Final Judgement: ${peserta.pivot.final_judgement || 'N/A'}</li>
                                                            </ul>
                                                        </li>
                                                    `).join('')}
                        </ul>
                    </div>
                `).join('');

                    document.getElementById('modalBody').innerHTML = trainingList;
                    setTimeout(showModal, 100);
                })
                .catch(error => console.error('Error fetching data:', error));
        }

        function showModal() {
            document.getElementById('modalBody').style.display = 'block';
        }

        function hideModal() {
            document.getElementById('modalBody').style.display = 'none';
        }
    </script>

@endsection
