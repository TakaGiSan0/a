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

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-center text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-4 py-3">No</th>
                            <th scope="col" class="px-4 py-3">Name</th>
                            <th scope="col" class="px-4 py-3">Badge No</th>
                            <th scope="col" class="px-4 py-3">Dept</th>
                            <th scope="col" class="px-4 py-3">Description</th>
                            @if (auth()->user()->role == 'Super Admin')
                                <th scope="col" class="px-4 py-3">Action</th>
                            @endif
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
                                    {{ Str::words($r->description ?? 'Tidak ditemukan', 10, '...') }}
                                </td>
                                @if (auth()->user()->role == 'Super Admin')
                                    <td class="relative px-4 py-3 text-center">
                                        <!-- Trigger & Dropdown wrapper -->
                                        <div class="inline-block text-left">
                                            <!-- Button -->
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
                                                class="dropdown-menu hidden absolute top-0 right-full ml-2 bg-white border rounded shadow-md z-50 w-32">

                                                <!-- Button Edit -->
                                                <button
                                                    class="w-full flex items-center gap-2 text-left px-4 py-2 hover:bg-gray-100">
                                                    <svg class="h-4 w-4 text-blue-500" fill="none" stroke="currentColor"
                                                        stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path d="M12 20h9" />
                                                        <path d="M16.5 3.5a2.121 2.121 0 013 3L7 19l-4 1 1-4 12.5-12.5z" />
                                                    </svg>
                                                    Edit
                                                </button>


                                                <!-- Button Hapus -->
                                                <button
                                                    class="w-full flex items-center gap-2 text-left px-4 py-2 text-red-600 hover:bg-red-100">
                                                    <svg class="h-4 w-4 text-red-600" fill="none" stroke="currentColor"
                                                        stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <polyline points="3 6 5 6 21 6" />
                                                        <path
                                                            d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6m5 0V4a2 2 0 0 1 2-2h0a2 2 0 0 1 2 2v2" />
                                                        <line x1="10" y1="11" x2="10" y2="17" />
                                                        <line x1="14" y1="11" x2="14" y2="17" />
                                                    </svg>
                                                    Hapus
                                                </button>
                                            </div>


                                        </div>
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
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Badge No</label>
                            <input type="text" name="badge_no" value="{{ auth()->user()->pesertaLogin->badge_no }}" readonly
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Department</label>
                            <input type="text" name="dept" value="{{ auth()->user()->pesertaLogin->dept }}" readonly
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm" rows="3"></textarea>
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



@endsection

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const modal = document.getElementById("jobSkillModal");
        const openModalBtn = document.getElementById("openModalBtn");
        const closeModalBtn = document.getElementById("closeModalBtn");

        openModalBtn.addEventListener("click", function () {
            modal.classList.remove("hidden");
            modal.classList.add("flex");
        });

        closeModalBtn.addEventListener("click", function () {
            modal.classList.add("hidden");
        });

        // Tutup modal jika klik di luar kontennya
        window.addEventListener("click", function (event) {
            if (event.target === modal) {
                modal.classList.add("hidden");
            }
        });
    });

    function toggleDropdown(event, btn) {
        event.stopPropagation(); // cegah event bubbling
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