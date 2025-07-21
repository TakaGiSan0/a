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

                </div>
                <div
                    class="w-full relative md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                </div>
                @if (auth()->user()->role == 'Super Admin')
                    <div
                        class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                        <a href="{{ route('export.training-request') }}"
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
                <div
                    class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                    <!-- Tombol untuk membuka modal -->
                    <button id="openModalBtn"
                        class="flex items-center justify-center text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                        <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewbox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path clip-rule="evenodd" fill-rule="evenodd"
                                d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                        </svg>
                        New Request
                    </button>
                </div>
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
                            <th scope="col" class="px-4 py-3">No</th>
                            <th scope="col" class="px-4 py-3">Name</th>
                            <th scope="col" class="px-4 py-3">Badge No</th>
                            <th scope="col" class="px-4 py-3">Dept</th>
                            <th scope="col" class="px-4 py-3">Description</th>
                            <th scope="col" class="px-4 py-3">Action</th>
                        </tr>
                    </thead>
                    <?php $no = 0; ?>
                    @foreach ($request as $r)
                        <tbody class="text-gray-600 dark:text-gray-200 bg-gray-50 dark:bg-gray-700">
                            <tr class=>
                                <th scope="row" name="id"
                                    class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ ++$no }}
                                </th>
                                <td class="px-4 py-3">
                                    {{ $r->peserta->employee_name ?? 'Tidak ditemukan' }}
                                </td>
                                <td class="px-4 py-3">
                                    {{ $r->peserta->badge_no ?? 'Tidak ditemukan' }}
                                </td>
                                <td class="px-4 py-3">
                                    {{ $r->peserta->dept ?? 'Tidak ditemukan' }}
                                </td>
                                <td class="px-4 py-3">

                                    {{ Str::words($r->description ?? '-', 5, '...') }}

                                </td>
                                <td class="relative px-4 py-3 text-center">


                                    <div class="inline-block text-left">
                                        <button onclick="toggleDropdown(event, this)"
                                            class="hover:bg-gray-100 dark:hover:bg-gray-600 rounded-full p-2">
                                            <!-- SVG icon -->
                                            <svg class="h-6 w-6 text-gray-500" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <circle cx="12" cy="12" r="1" />
                                                <circle cx="12" cy="5" r="1" />
                                                <circle cx="12" cy="19" r="1" />
                                            </svg>
                                        </button>

                                        <div
                                            class="dropdown-menu hidden absolute top-0 right-full ml-2 bg-white border rounded shadow-md z-50 w-32 dark:bg-gray-800 dark:text-gray-400">

                                            <button type="button" data-modal-target="viewModal" data-modal-toggle="viewModal"
                                                data-id="{{ $r->id }}" id="openViewDetailModalBtn"
                                                class="openViewDetailModalBtn w-full flex items-center gap-2 text-left px-4 py-2 hover:bg-gray-100">
                                                <svg class="w-4 h-4 flex-shrink-0 text-slate-500"
                                                    xmlns="http://www.w3.org/2000/svg" viewbox="0 0 24 24" fill="currentColor">
                                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" />
                                                </svg>
                                                View
                                            </button>
                                            @if (auth()->user()->id === $r->user_id_login)
                                                <form action="{{ route('training-request.destroy', $r->id) }}" method="POST"
                                                    onsubmit="return confirm('Are you sure you want to delete this request?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <!-- Button Hapus -->
                                                    <button
                                                        class="w-full flex items-center gap-2 text-left px-4 py-2 text-red-600 hover:bg-red-100 dark:bg-gray-800 dark:text-gray-400">
                                                        <svg class="h-4 w-4 text-red-600" fill="none" viewBox="0 0 24 24"
                                                            stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                        Delete
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </td>
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
    <div id="jobSkillModal" class="hidden fixed inset-0  items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 max-w-2xl w-full">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4 text-center"> Training Request</h2>
            <div class="overflow-x-auto max-h-96">
                <form action="{{ route('training-request.store') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" name="employee_name"
                                value="{{ auth()->user()->pesertaLogin->employee_name }}" readonly
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm dark:text-gray-200 dark:bg-gray-700">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Badge No</label>
                            <input type="text" name="badge_no" value="{{ auth()->user()->pesertaLogin->badge_no }}" readonly
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm dark:text-gray-200 dark:bg-gray-700">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Department</label>
                            <input type="text" name="dept" value="{{ auth()->user()->pesertaLogin->dept }}" readonly
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm dark:text-gray-200 dark:bg-gray-700">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm dark:text-gray-200 dark:bg-gray-700" rows="3"
                                required></textarea>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-2 mt-4">
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 active:bg-blue-600 text-white rounded-lg">
                            Submit
                        </button>
                        <button type="button" id="closeModalBtn"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                            Close
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="viewModal" class="hidden fixed inset-0  items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 max-w-2xl w-full">
            <div class="flex items-center pb-3 border-b border-gray-200">
                <div class="flex-1">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4 text-center">Training Request</h2>
                </div>

                <!-- Tombol Close -->
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="w-5 h-5 ml-2 cursor-pointer shrink-0 fill-gray-400 hover:fill-red-500 closeViewModalBtn"
                    id="closeViewModalBtn" data-modal-hide="viewModal" viewBox="0 0 24 24">
                    <path d="M6 6L18 18M6 18L18 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                </svg>
            </div>

            <div class="overflow-x-auto max-h-96">
                <div class="space-y-4 viewModalContent" id="viewModalContent">

                </div>


            </div>

        </div>
    </div>



@endsection

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Referensi ke modal job skill (jika masih digunakan)
        const jobSkillModal = document.getElementById("jobSkillModal");
        const openJobSkillModalBtn = document.getElementById("openModalBtn");
        const closeJobSkillModalBtn = document.getElementById("closeModalBtn");

        // Referensi ke modal view detail (baru)
        const viewModal = document.getElementById("viewModal");
        const openViewDetailModalBtns = document.querySelectorAll(".openViewDetailModalBtn");
        const closeViewModalBtn = document.getElementById("closeViewModalBtn");
        const viewModalContentArea = viewModal.querySelector('#viewModalContent');


        // --- Job Skill Modal Functionality (jika masih relevan) ---
        if (openJobSkillModalBtn && jobSkillModal) {
            openJobSkillModalBtn.addEventListener("click", function () {
                jobSkillModal.classList.remove("hidden");
                jobSkillModal.classList.add("flex");
            });
        }

        if (closeJobSkillModalBtn && jobSkillModal) {
            closeJobSkillModalBtn.addEventListener("click", function () {
                jobSkillModal.classList.add("hidden");
                jobSkillModal.classList.remove("flex"); // Pastikan flex juga dihapus
            });
        }

        if (jobSkillModal) {
            window.addEventListener("click", function (event) {
                if (event.target === jobSkillModal) {
                    jobSkillModal.classList.add("hidden");
                    jobSkillModal.classList.remove("flex");
                }
            });
        }

        // --- View Detail Modal Functionality ---

        // Melampirkan event listener ke setiap tombol 'View'
        openViewDetailModalBtns.forEach(btn => {
            btn.addEventListener("click", async function () {
                viewModal.classList.remove("hidden");
                viewModal.classList.add("flex");

                const requestId = this.dataset.id; // Ambil ID dari atribut data-id tombol yang diklik
                if (requestId) {
                    await fetchAndDisplayTrainingRequest(requestId);
                }
            });
        });

        // Event listener untuk menutup modal detail jika diklik di luar kontennya
        if (viewModal) {
            window.addEventListener("click", function (event) {
                if (event.target === viewModal) {
                    viewModal.classList.add("hidden");
                    viewModal.classList.remove("flex");
                }
            });
        }

        // --- Fungsi untuk mengambil dan menampilkan data ---
        async function fetchAndDisplayTrainingRequest(id) {
            if (!viewModalContentArea) {
                console.error("Elemen dengan ID 'viewModalContent' tidak ditemukan di dalam viewModal.");
                return;
            }

            viewModalContentArea.innerHTML = '<p class="text-center text-gray-500 dark:text-gray-400">Memuat detail...</p>'; // Pesan loading

            try {
                window.baseURL = '{{ url('/') }}'
                const response = await fetch(window.baseURL + `/training-request/show/${id}`);
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                const data = await response.json();

                // Bangun HTML untuk menampilkan data (termasuk deskripsi)
                let htmlContent = `
                <div class="space-y-3">
                    
                    <div>
                            <label class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" name="employee_name"
                                readonly class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm dark:text-gray-200 dark:bg-gray-700" value='${data.peserta ? data.peserta.employee_name : 'N/A'}'>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Badge No</label>
                            <input type="text" name="badge_no"
                                readonly class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm dark:text-gray-200 dark:bg-gray-700" value='${data.peserta ? data.peserta.badge_no : 'N/A'}'>
                        </div>
                    <div>
                            <label class="block text-sm font-medium text-gray-700">Department</label>
                            <input type="text" name="dept"
                                readonly class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm dark:text-gray-200 dark:bg-gray-700" value='${data.peserta ? data.peserta.dept : 'N/A'}'>
                        </div>
                     <div>
                            <label class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm dark:text-gray-200 dark:bg-gray-700" rows="3" readonly>${data.description || '-'}</textarea>
                        </div>
                    
                </div>
            `;

                viewModalContentArea.innerHTML = htmlContent;

            } catch (error) {
                console.error("Gagal mengambil data pelatihan:", error);
                viewModalContentArea.innerHTML = '<p class="text-red-500 text-center">Gagal memuat detail. Silakan coba lagi.</p>';
            }
        }
    });
    function toggleDropdown(event, btn) {
        event.stopPropagation();
        const dropdown = btn.nextElementSibling;
        const allDropdowns = document.querySelectorAll('.dropdown-menu');

        allDropdowns.forEach(d => {
            if (d !== dropdown) d.classList.add('hidden');
        });

        dropdown.classList.toggle('hidden');
    }

    // Tutup dropdown saat klik di luar
    document.addEventListener('click', function (e) {
        if (!e.target.closest('.dropdown-menu') && !e.target.closest('button')) {
            document.querySelectorAll('.dropdown-menu').forEach(d => d.classList.add('hidden'));
        }
    });


</script>