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
                        <a href="{{ route('export.matrix') }}"
                            class="flex items-center justify-center text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800">
                            <svg class="h-4 w-4 mr-2 text-white-500" width="24" height="24" viewBox="0 0 24 24"
                                stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                                stroke-linejoin="round">
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

                    <tbody class="text-gray-600 dark:text-gray-200 bg-gray-50 dark:bg-gray-700">
                        <tr class=>
                            <th scope="row" name="id"
                                class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ ++$no }}
                            </th>
                            <td class="px-4 py-3">


                            </td>
                            <td class="px-4 py-3">


                            </td>
                            <td class="px-4 py-3">


                            </td>
                            <td class="px-4 py-3">

                            </td>
                            @if (auth()->user()->role == 'Super Admin')
                                <td class="px-4 py-3 flex items-center justify-center space-x-4">
                                    <button type="button" data-modal-target="readProductModal"
                                        data-modal-toggle="readProductModal" data-id=""
                                        class="trigger-modal items-center justify-center over:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white text-gray-700 dark:text-gray-200">
                                        <svg class="h-8 w-8 text-slate-500" width="24" height="24"
                                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" />
                                            <path d="M4 20h4l10.5 -10.5a1.5 1.5 0 0 0 -4 -4l-10.5 10.5v4" />
                                            <line x1="13.5" y1="6.5" x2="17.5" y2="10.5" />
                                        </svg>
                                    </button>
                                </td>
                            @endif
                        </tr>
                    </tbody>

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
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4 text-center">Tambah Job Skill</h2>

            <!-- TABEL -->
            <div class="overflow-x-auto max-h-96">
                <table class="w-full text-sm text-gray-500 border">
                    <thead>
                        <tr>
                            <th class="px-4 py-3 border">No</th>
                            <th class="px-4 py-3 border">Skill Code</th>
                            <th class="px-4 py-3 border">Job Skill</th>
                            <th class="px-4 py-3 border">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @php $no = 1; @endphp
                        @foreach ($jobskill as $js)
                            <tr>
                                <td class="px-4 py-3 text-center border">{{ $no++ }}</td>
                                <td class="px-4 py-3 text-center border">{{ $js->skill_code }}</td>
                                <td class="px-4 py-3 text-center border">{{ $js->job_skill }}</td>
                                <td class="px-4 py-3 text-center border">
                                    <!-- FORM DELETE DIPISAH -->
                                    <form action="{{ route('jobs_skill.destroy', $js->id) }}" method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach

                        <form action="{{ route('jobs_skill.store') }}" method="POST">
                            @csrf
                            <tr>
                                <td class="px-4 py-3 text-center border">#</td>
                                <td class="px-4 py-3 text-center border">
                                    <input type="text" name="skill_code" class="w-full border rounded px-2 py-1"
                                        required>
                                </td>
                                <td class="px-4 py-3 text-center border">
                                    <input type="text" name="job_skill" class="w-full border rounded px-2 py-1"
                                        required>
                                </td>
                                <td class="px-4 py-3 border text-center">
                                    <button type="submit"
                                        class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">
                                        Add
                                    </button>
                                </td>
                            </tr>
                        </form>
                    </tbody>
                </table>
                <div class="flex justify-end mt-4">
                    <button type="button" id="closeModalBtn"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>



@endsection

<script>
    document.addEventListener("DOMContentLoaded", function() {
            const modal = document.getElementById("jobSkillModal");
            const openModalBtn = document.getElementById("openModalBtn");
            const closeModalBtn = document.getElementById("closeModalBtn");

            openModalBtn.addEventListener("click", function() {
                modal.classList.remove("hidden");
                modal.classList.add("flex");
            });

            closeModalBtn.addEventListener("click", function() {
                modal.classList.add("hidden");
            });

            // Tutup modal jika klik di luar kontennya
            window.addEventListener("click", function(event) {
                if (event.target === modal) {
                    modal.classList.add("hidden");
                }
            });
        });
</script>
