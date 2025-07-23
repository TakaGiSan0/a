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


    <section class="relative shadow-md dark:shadow-none sm:rounded-lg overflow-hidden antialiased">

        <!-- Start coding here -->
        <div class="bg-white dark:bg-gray-800 relative shadow-lg sm:rounded-xl overflow-hidden border border-gray-200 ">
            <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                <div class="w-full md:w-1/2">
                    <form class="flex items-center" method="GET" action="{{ route('dashboard.index') }}">
                        <label for="simple-search" class="sr-only">Search</label>
                        <div class="relative w-full">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor"
                                    viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input type="text" id="simple-search" name="search" id="search"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Training Name" value="{{ request('search') }}">
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
                                <option value="" {{ request('year') == null ? 'selected' : '' }}>All Year</option>
                                @foreach ($years as $year)
                                    <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                                        {{ $year }}
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
                            class="open-modal-import flex items-center justify-center text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800"
                            type="button">

                            <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewbox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path clip-rule="evenodd" fill-rule="evenodd"
                                    d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                            </svg>Import Excel
                        </button>
                    </div>
                    <div
                        class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                        <button
                            class="open-modal-export flex items-center justify-center text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800"
                            type="button">

                            <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewbox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path clip-rule="evenodd" fill-rule="evenodd"
                                    d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                            </svg>Export Excel
                        </button>
                    </div>
                
                    <div
                        class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                     
                        <button id="openModalBtnJS"
                            class="flex items-center justify-center text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                            <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewbox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path clip-rule="evenodd" fill-rule="evenodd"
                                    d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                            </svg>
                            Job Skill
                        </button>
                    </div>
                    <div
                        class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                     
                        <button id="openModalBtnPC"
                            class="flex items-center justify-center text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                            <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewbox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path clip-rule="evenodd" fill-rule="evenodd"
                                    d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                            </svg>
                            Product Code
                        </button>
                    </div>
                @endif
                <div
                    class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                    <a href="{{ route('dashboard.create') }}"
                        class="flex items-center justify-center text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                        <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewbox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path clip-rule="evenodd" fill-rule="evenodd"
                                d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                        </svg>
                        New Event
                    </a>
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
                        <tbody class="text-gray-600 dark:text-gray-200 bg-gray-50 dark:bg-gray-700">
                            <tr class=>
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
                                <td class="px-4 py-3 text-center">{{ $rc->formatted_date_range }}
                                </td>

                                <!-- Nama Trainer -->
                                <td class="px-4 py-3 text-center">{{ $rc->trainer_name }}</td>

                                <td class="px-4 py-3 text-center"> {{ $rc->latestComment->approval ?? '-' }}</td>

                                <!-- Status -->
                                <td class="px-4 py-3 text-center">{{ $rc->status }}</td>

                                <!-- Edit dan Delete Actions -->
                                <td class="relative px-4 py-3 text-center">
                                    <!-- Trigger & Dropdown wrapper -->
                                    <div class="inline-block text-left">
                                        <!-- Button -->
                                        @if (auth()->user()->role === 'Super Admin' || (auth()->user()->id === $rc->user_id && in_array($rc->status, ['Pending', 'Waiting Approval'])) )
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
                                        @endif
                                        <div
                                            class="dropdown-menu hidden absolute top-0 right-full ml-2 bg-white border rounded shadow-md z-50 w-32 dark:bg-gray-800 dark:text-gray-400">

                                            <!-- Button Edit -->

                                            <a href="{{ route('dashboard.edit', $rc->id) }}"
                                                class="w-full flex items-center gap-2 text-left px-4 py-2 hover:bg-gray-100">
                                                <svg class="h-4 w-4 text-blue-500" fill="none" stroke="currentColor"
                                                    stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path d="M12 20h9" />
                                                    <path d="M16.5 3.5a2.121 2.121 0 013 3L7 19l-4 1 1-4 12.5-12.5z" />
                                                </svg>
                                                Edit
                                            </a>


                                            <!-- Button Download -->

                                            <button type="button" data-modal-target="readProductModal"
                                                data-modal-toggle="readProductModal" data-id="{{ $rc->id }}" id="trigger-modal"
                                                class="w-full flex items-center gap-2 text-left px-4 py-2 hover:bg-gray-100 dark:bg-gray-800 dark:text-gray-400">
                                                <svg class="w-4 h-4 flex-shrink-0 text-slate-500"
                                                    xmlns="http://www.w3.org/2000/svg" viewbox="0 0 24 24" fill="currentColor">
                                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" />
                                                </svg>
                                                View
                                            </button>


                                            @if (Auth::user()->role == 'Super Admin')
                                                <form action="{{ route('dashboard.destroy', $rc->id) }}" method="POST"
                                                    onsubmit="return confirm('Are you sure you want to delete this Training?');">
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
        </div>

        <div class="mt-4 ">
            {{ $training_records->appends(['year' => request('year'),'search' => request('search')])->links() }}
        </div>
    </section>

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
                            <th class="px-4 py-3 border">Status</th>
                            <th class="px-4 py-3 border">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 dark:text-gray-400">
                        @php $no = 1; @endphp
                        @foreach ($jobskill as $js)
                            <tr>
                                <td class="px-4 py-3 text-center border">{{ $no++ }}</td>
                                <td class="px-4 py-3 text-center border">{{ $js->skill_code }}</td>
                                <td class="px-4 py-3 text-center border">{{ $js->job_skill }}</td>
                                <td class="px-4 py-3 text-center border">{{ $js->status }}</td>
                                <td class="px-4 py-3 text-center border">
                                    <!-- FORM DELETE DIPISAH -->
                                  
                                <form action="{{ route('jobs_skill.update', $js->id) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to change the visibility of this product?');">
                                    @csrf
                                    <input type="hidden" name="status" value="{{ $js->status === 'Active' ? 'Non Active' : 'Active' }}">

                                    <button type="submit" 
                                        class="px-3 py-1 {{ $js->status === 'Active' ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-green-500 hover:bg-green-600' }} text-white rounded">
                                        {{ $js->status === 'Active' ? 'Hide' : 'Show' }}
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
                                    <input type="text" name="skill_code" class="w-full border rounded px-2 py-1" required>
                                </td>
                                <td class="px-4 py-3 text-center border">
                                    <input type="text" name="job_skill" class="w-full border rounded px-2 py-1" required>
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

    <div id="ProductSkillModal" class="hidden fixed inset-0  items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 max-w-2xl w-full">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4 text-center">Tambah Product Skill</h2>

            <!-- TABEL -->
            <div class="overflow-x-auto max-h-96 dark:bg-gray-800">
                <table class="w-full text-sm text-gray-500 border dark:bg-gray-800">
                    <thead>
                        <tr>
                            <th class="px-4 py-3 border">No</th>
                            <th class="px-4 py-3 border">Product Code</th>
                            <th class="px-4 py-3 border">Product Name</th>
                            <th class="px-4 py-3 border">Status</th>
                            <th class="px-4 py-3 border">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 dark:text-gray-400">
                        @php $no = 1; @endphp
                        @foreach ($product_code as $pc)
                            <tr>
                                <td class="px-4 py-3 text-center border">{{ $no++ }}</td>
                                <td class="px-4 py-3 text-center border">{{ $pc->product_code }}</td>
                                <td class="px-4 py-3 text-center border">{{ $pc->product_name }}</td>
                                <td class="px-4 py-3 text-center border">{{ $pc->status }}</td>

                                <td class="px-4 py-3 text-center border">
                                <form action="{{ route('product_code.update', $pc->id) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to change the visibility of this product?');">
                                    @csrf
                                    <input type="hidden" name="status" value="{{ $pc->status === 'Active' ? 'Non Active' : 'Active' }}">

                                    <button type="submit" 
                                        class="px-3 py-1 {{ $pc->status === 'Active' ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-green-500 hover:bg-green-600' }} text-white rounded">
                                        {{ $pc->status === 'Active' ? 'Hide' : 'Show' }}
                                    </button>
                                </form>
                                </td>
                            </tr>
                        @endforeach

                        <form action="{{ route('product_code.store') }}" method="POST">
                            @csrf
                            <tr>
                                <td class="px-4 py-3 text-center border">#</td>
                                <td class="px-4 py-3 text-center border">
                                    <input type="text" name="product_code" class="w-full border rounded px-2 py-1" required>
                                </td>
                                <td class="px-4 py-3 text-center border">
                                    <input type="text" name="product_name" class="w-full border rounded px-2 py-1" required>
                                </td>
                                <td class="px-4 py-3 text-center border">
                                    
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

    <div id="uploadModal"
        class="hidden fixed inset-0 p-4 justify-center items-center w-full h-full z-50 overflow-auto font-[sans-serif]">

        <!-- Overlay (background hitam) -->
        <div class="fixed inset-0 bg-black bg-opacity-50"></div>
        <!-- Konten Modal -->
        <div class="w-full max-w-lg bg-white shadow-lg rounded-lg p-6 relative z-10 dark:bg-gray-700 dark:text-[#f1f1f1]">
            <div class="flex items-center pb-3 border-b border-gray-200">
                <div class="flex-1">
                    <h3 class="text-gray-800 text-xl font-bold dark:text-white">Import Excel</h3>
                    <p class="text-gray-600 text-xs mt-1 dark:text-white">Upload file to this project</p>
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

                    <input
                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                        id="file_input" type="file" name="file" accept=".xlsx,.xls">
                </div>

                <div class="border-t border-gray-200 pt-6 flex justify-between gap-4 mt-6">
                    <button type="button"
                        class="w-full px-4 py-2 rounded-lg text-gray-800 text-sm border-none outline-none tracking-wide bg-gray-200 hover:bg-gray-300 active:bg-gray-200 close-modal">Cancel</button>
                    <button type="submit"
                        class="w-full px-4 py-2 rounded-lg text-white text-sm border-none outline-none tracking-wide bg-blue-600 hover:bg-blue-700 active:bg-blue-600">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <div id="uploadModalexport"
        class="hidden fixed inset-0 p-4 justify-center items-center w-full h-full z-50 overflow-auto font-[sans-serif]">

        <!-- Overlay (background hitam) -->
        <div class="fixed inset-0 bg-black bg-opacity-50"></div>
        <!-- Konten Modal -->
        <div class="w-full max-w-lg bg-white shadow-lg rounded-lg p-6 relative z-10 dark:bg-gray-700 dark:text-[#f1f1f1]">
            <div class="flex items-center pb-3 border-b border-gray-200">
                <div class="flex-1">
                    <h3 class="text-gray-800 text-xl font-bold dark:text-white">Export Excel</h3>
                    <p class="text-gray-600 text-xs mt-1 dark:text-white">Tahun Export</p>
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
            <form action="{{ route('export.training') }}" method="GET" class="mt-4 flex items-center gap-4">
                <label for="export_year" class="text-sm font-medium text-gray-700 dark:text-gray-300">Pilih Tahun:</label>

                <select id="year" name="year"
                    class="mt-2 block w-full rounded-md border border-gray-300 bg-gray-50 px-3 py-2 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600  text-black dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option value="all"
                        class="dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        All Year</option>

                    @foreach ($years as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach

                </select>

                <button type="submit"
                    class="px-4 py-2 rounded-lg text-white text-sm bg-green-600 hover:bg-green-700 active:bg-green-600">
                    Export
                </button>
            </form>
        </div>
    </div>

    <div id="readProductModal" aria-hidden="false"
        class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full bg-black bg-opacity-50">
        <div class="relative w-full max-w-2xl max-h-full mx-auto">
            <!-- Modal content -->
            <div class="bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Detail Event
                    </h3>
                    <button type="button" data-modal-hide="readProductModal"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white">
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
                        <div class="flex">
                            <svg class="h-8 w-8 text-slate-500" width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" />
                                <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                                <line x1="9" y1="9" x2="10" y2="9" />
                                <line x1="9" y1="13" x2="15" y2="13" />
                                <line x1="9" y1="17" x2="15" y2="17" />
                            </svg>
                            <a id="modal-attachment" href="#" target="_blank" class="text-blue-500 underline">
                                View PDF
                            </a>
                        </div>
                        <div class="mt-4">
                            <h3 id="requestor-name" class="text-sm font-semibold text-gray-900 dark:text-white mb-2">
                                History Approval - Requestor =
                            </h3>
                            <table class="min-w-full text-sm text-left text-gray-500 dark:text-gray-400 border">
                                <thead
                                    class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-2 py-1 border">No</th>
                                        <th scope="col" class="px-2 py-1 border">Approval</th>
                                        <th scope="col" class="px-2 py-1 border">Comment</th>
                                        <th scope="col" class="px-2 py-1 border">Date</th>
                                    </tr>
                                </thead>
                                <tbody id="modal-history-body">
                                    {{-- Baris history akan diisi lewat JavaScript --}}
                                </tbody>
                            </table>
                        </div>
                        @csrf
                        @method('PUT')

                        <p class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" id="modal-content">
                        </p>
                        <p class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" id="modal-content">
                            Komentar
                        </p>
                        @php
                            $isSuperAdmin = auth()->user()->role === 'Super Admin'; //
                        @endphp

                        <textarea name="comment" id="comment" class="w-full h-24 px-3 py-2 border border-gray-300 rounded-md text-black focus:outline-none focus:ring bg-white
                                                                dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500
                                                                focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600
                                                            @if (!$isSuperAdmin) text-gray-400 @endif" @if (!$isSuperAdmin)
                                                            readonly @endif
                            placeholder="{{ !$isSuperAdmin ? 'Tunggu komentar dari super admin' : '' }}" required>{{ old('comment', $comment ?? '') }}</textarea>
                        @if (Auth::user()->role == 'Super Admin')
                            <div>
                                <label for="category" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Approval
                                </label>
                                <select id="approval" name="approval"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                    <option value="Pending" {{ old('approval', $approval ?? '') == 'Pending' ? 'selected' : '' }}>
                                        Pending
                                    </option>
                                    <option value="Approved" {{ old('approval', $approval ?? '') == 'Approved' ? 'selected' : '' }}>Approved
                                    </option>
                                    <option value="Reject" {{ old('approval', $approval ?? '') == 'Reject' ? 'selected' : '' }}>
                                        Reject
                                    </option>
                                </select>
                            </div>
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
                        @if (Auth::user()->role == 'Super Admin')
                            <button type="submit" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded">Update</button>
                        @endif
                        <button data-modal-hide="readProductModal" type="button"
                            class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:bg-gray-600 dark:hover:text-white">
                            Tutup
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('uploadModal');
            const openModalButtons = document.querySelectorAll(
                '.open-modal-import'); // Sesuaikan tombol untuk membuka modal
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

        document.addEventListener('DOMContentLoaded', function () {
            const modalexport = document.getElementById('uploadModalexport');
            const openModalexportButtons = document.querySelectorAll(
                '.open-modal-export'); // Sesuaikan tombol untuk membuka modal
            const closeModalButtons = document.querySelectorAll('.close-modal');
            // Fungsi untuk membuka modal
            openModalexportButtons.forEach(button => {
                button.addEventListener('click', () => {
                    modalexport.classList.remove('hidden');
                    modalexport.classList.add('flex');
                });
            });
            // Fungsi untuk menutup modal
            closeModalButtons.forEach(button => {
                button.addEventListener('click', () => {
                    modalexport.classList.add('hidden');
                });
            });
        });

        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('readProductModal');
            const commentForm = modal.querySelector('#commentForm');
            const commentField = modal.querySelector('#comment');
            const approvalField = modal.querySelector('#approval');
            const statusField = modal.querySelector('#status');
            const attachmentFrame = modal.querySelector('#modal-attachment');
            const userRole = @json(auth()->user()->role);
            const editButtons = document.querySelectorAll('#trigger-modal');
            const requestorNameElement = document.getElementById('requestor-name');



            editButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const recordId = button.getAttribute('data-id');
                     window.baseURL = '{{ url('/') }}'
                    fetch(window.baseURL +`/training-record/${recordId}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.message) {
                                alert(data.message); // Jika ada pesan error
                                return;
                            }
                            // Set form action
                            commentForm.action = window.baseURL +`/training-record/${recordId}/comment`;

                            // Set field values
                            if (data.comment) {
                                commentField.value = data.comment;
                            } else if (userRole !== 'Super Admin') {
                                commentField.value = "Tunggu komentar dari Super Admin";
                            } else {
                                commentField.value = "";
                            }

                            if (userRole === 'Super Admin') {
                                approvalField.value = data.approval;
                                statusField.value = data.status;
                            }
                           

                            // Set attachment (PDF)
                            if (data.attachment) {
                                attachmentFrame.href = data.attachment;
                            } else {
                                attachmentFrame.href = '';
                                alert('Attachment tidak tersedia.');
                            }
                            requestorNameElement.textContent =
                                `History Approval - Requestor = ${data.requestor_name}`;

                            // Tampilkan history ke tabel
                            renderHistoryTable(data.history);

                            // Tampilkan modal
                            modal.classList.remove('hidden');
                            modal.classList.add('flex');
                        })
                        .catch(error => {
                            console.error('Error fetching data:', error);
                            alert('Terjadi kesalahan saat mengambil data.');
                        });

                });
            });


            function renderHistoryTable(historyData) {
                const tbody = document.getElementById('modal-history-body');
                tbody.innerHTML = ''; // Kosongkan isinya dulu

                if (!historyData || historyData.length === 0) {
                    const emptyRow = document.createElement('tr');
                    emptyRow.innerHTML =
                        `<td colspan="3" class="p-2 text-center text-gray-500">Tidak ada riwayat komentar atau approval</td>`;
                    tbody.appendChild(emptyRow);
                    return;
                }

                historyData.forEach((entry, index) => {
                    const tr = document.createElement('tr');

                    // Format updated_at menggunakan toLocaleString
                    const formattedDate = new Date(entry.updated_at).toLocaleString('id-ID', {
                        day: '2-digit',
                        month: 'short', // singkatan bulan
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });

                    tr.innerHTML = `
                                    <td class="p-2 text-center">${index + 1}</td>
                                    <td class="p-2">${entry.approval || '-'}</td>
                                    <td class="p-2">${entry.comment || '-'}</td>
                                    <td class="p-2">${formattedDate}</td>
                                `;
                    tbody.appendChild(tr);
                });
            }


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

        document.addEventListener("DOMContentLoaded", function () {
            const modal = document.getElementById("jobSkillModal");
            const openModalBtnJS = document.getElementById("openModalBtnJS");
            const closeModalBtn = document.getElementById("closeModalBtn");

            openModalBtnJS.addEventListener("click", function () {
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

        document.addEventListener("DOMContentLoaded", function () {
            const modal = document.getElementById("ProductSkillModal");
            const openModalBtnPC = document.getElementById("openModalBtnPC");
            const closeModalBtn = document.getElementById("closeModalBtn");

            openModalBtnPC.addEventListener("click", function () {
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
@endsection