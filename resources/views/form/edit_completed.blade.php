<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    @vite('resources/css/app.css')

</head>

<body>

    <div class="bg-gray-100 dark:bg-gray-800 transition-colors duration-300">
        <div class="container mx-auto p-4">
            <form id="autoSaveForm" class="space-y-4" action="{{ route('dashboard.update', $trainingRecord->id) }}"
                method="POST">
                @csrf
                @method('PUT')

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="grid gap-4 mb-4 sm:grid-cols-2">
                    <div>
                        <label for="doc_ref" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Doc.
                            Ref</label>
                        <input type="text" id="doc_ref" name="doc_ref" value="{{ $trainingRecord->doc_ref }}"
                            required
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            required="">
                    </div>
                    <div>
                        <label for="rev"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Rev</label>
                        <input type="text" name="rev" id="rev" value="{{ $trainingRecord->rev }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            required="">
                    </div>
                    <div id="trainingNameContainer">
                        <div class="training-name-input">
                            <label for="training_name"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Job Skill
                            </label>
                            <input type="text" id="job_skill" name="job_skill"
                                value="{{ $trainingRecord->job_skill }}" required
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                required="">
                        </div>
                    </div>

                    <div><label for="station"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Station</label>
                        <input type="text" name="station" id="station" value="{{ $trainingRecord->station }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            required="">
                    </div>
                    <div class="sm:col-span-2"><label for="description"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Training Name</label>
                        <input type="text" name="training_name" id="training_name"
                            value="{{ $trainingRecord->training_name }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:pPlaceholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            required="">
                    </div>
                    <div><label for="skill_code"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Skill
                            Code</label>
                        <input type="text" name="skill_code" id="skill_code"
                            value="{{ $trainingRecord->skill_code }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    </div>
                    <div><label for="category"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Trainer
                            Name</label>
                        <input type="text" name="trainer_name" id="trainer_name"
                            value="{{ $trainingRecord->trainer_name }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="John Doe">
                    </div>
                    <div><label for="category"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Training
                            Date</label>
                        <input type="date" name="training_date" id="training_date"
                            value="{{ $trainingRecord->training_date }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="17 Desember 2021">
                    </div>
                    <div><label for="category"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Training
                            Category</label>
                        <select id="category" name="category_id" id="category_id_1"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ $trainingRecord->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }} <!-- Sesuaikan dengan nama kolom yang diinginkan -->
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div id="participants-container">

                    <table class="w-full text-sm text-center text-gray-500 dark:text-gray-400 mb-5">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <td class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        Badge No
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Employee Name
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Dept
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Position
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Theory Result
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Practise Result
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Level
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Final Judgement
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        license
                                    </th>
                                </tr>
                        </thead>
                        @foreach ($participants as $index => $participant)
                            <tbody>
                                <td scope="col" class="px-1">
                                    <input type="text" name="participants[{{ $index }}][badge_no]"
                                        value="{{ $participant['badge_no'] }}" id="badge_no_1"
                                        class="bg-gray-50 badge_no_input border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                </td>
                                <td scope="col" class="px-1">
                                    <input type="text" name="participants[{{ $index }}][employee_name]"
                                        id="employee_name_1" value="{{ $participant['employee_name'] }}"
                                        class="bg-gray-50 employee_name_input border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        placeholder="John Doe" readonly>
                                </td>
                                <td scope="col" class="px-1">
                                    <input type="text" name="participants[{{ $index }}][dept]"
                                        id="dept" value="{{ $participant['dept'] }}"
                                        class="bg-gray-50 dept_input border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        placeholder="John Doe" readonly>
                                </td>
                                <td scope="col" class="px-1">
                                    <input type="text" name="participants[{{ $index }}][position]"
                                        id="position" value="{{ $participant['position'] }}"
                                        class="bg-gray-50 position_input border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        placeholder="John Doe" readonly>
                                </td>
                                <input type="hidden" name="participants[0][peserta_id]" class="peserta_id_input">
                                <td scope="col" class="px-1">
                                    <select id="category" name="participants[{{ $index }}][theory_result]"
                                        value="{{ $participant->pivot->theory_result ?? '' }}"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                        <option value="Pass" @selected($participant->pivot->theory_result == 'Pass')>Pass</option>
                                        <option value="Fail" @selected($participant->pivot->theory_result == 'Fail')>Fail</option>
                                        <option value="N/A" @selected($participant->pivot->theory_result == 'N/A')>N/A</option>
                                    </select>
                                </td>
                                <td scope="col" class="px-1">
                                    <select id="category" name="participants[{{ $index }}][practical_result]"
                                        value="{{ old($participant->pivot->theory_result ?? '') }}"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                        <option value="Pass" @selected($participant->pivot->theory_result == 'Pass')>Pass</option>
                                        <option value="Fail" @selected($participant->pivot->theory_result == 'Fail')>Fail</option>
                                        <option value="N/A" @selected($participant->pivot->theory_result == 'N/A')>N/A</option>

                                    </select>
                                </td>
                                <td scope="col" class="px-1">
                                    <select id="category" name="participants[{{ $index }}][level] "
                                        value="{{ $participant->pivot->theory_result ?? '' }}"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                        <option name="Level 1" value="Level 1" @selected($participant->pivot->level == 'Level 1')>Level 1
                                        </option>
                                        <option name="Level 2" value="Level 2" @selected($participant->pivot->level == 'Level 2')>Level 2
                                        </option>
                                        <option name="Level 3" value="Level 3" @selected($participant->pivot->level == 'Level 3')>Level 3
                                        </option>
                                        <option name="Level 4" value="Level 4" @selected($participant->pivot->level == 'Level 4')>Level 4
                                        </option>
                                        <option name="N/A" value="N/A" @selected($participant->pivot->level == 'N/A')>N/A
                                        </option>
                                    </select>
                                </td>
                                <td scope="col" class="px-1">
                                    <select id="category" name="participants[{{ $index }}][final_judgement]"
                                        value="{{ $participant->pivot->theory_result ?? '' }}"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                        <option name="Attend " value="Attend" @selected($participant->pivot->final_judgement == 'Attend ')>Attend
                                        </option>
                                        <option name="Competence" value="Competence" @selected($participant->pivot->final_judgement == 'Competence')>
                                            Competence</option>
                                        <option name="N/A" value="N/A" @selected($participant->pivot->final_judgement == 'N/A')>N/A
                                        </option>

                                    </select>
                                </td>
                                <td scope="col" class="px-1">
                                    <input type="hidden" name="participants[{{ $index }}][license]"
                                        value="0">
                                    <input id="license-checkbox-{{ $index }}"
                                        name="participants[{{ $index }}][license]" type="checkbox"
                                        value="1"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                        {{ old("participants.$index.license", $participant->pivot->license ?? 0) == 1 ? 'checked' : '' }}>
                                </td>
                                <input type="hidden" name="participant_count" value="1">
                            </tbody>
                        @endforeach
                    </table>
                </div>
                <div class="participant-row" id="participant_1">

                </div>
                <div class="flex items-center justify-center p-6 m-5">
                    <button type="submit"
                        class="text-white inline-flex justify-center items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 mx-3">
                        <svg class="mr-1 -ml-1 w-6 h-6" fill="currentColor" viewbox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                clip-rule="evenodd" />
                        </svg>
                        Submit

                    </button>
                    <button type="button" id="add-participant"
                        class="text-white inline-flex justify-center items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 mx-3">
                        <svg class="mr-1 -ml-1 w-6 h-6" fill="currentColor" viewbox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                clip-rule="evenodd" />
                        </svg>
                        Tambahkan Peserta
                    </button>
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    <button type="submit" name="save_as_draft" value="1"
                        class="text-white inline-flex justify-center items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 mx-3">
                        <svg class="mr-1 -ml-1 w-6 h-6" fill="currentColor" viewbox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                clip-rule="evenodd" />
                        </svg>
                        Draft</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            // Mencegah form submit saat Enter ditekan di dalam form
            $('form').on('keypress', function(e) {
                if (e.which == 13) {
                    e.preventDefault(); // Mencegah form submit
                }
            });

            // Menggunakan delegated event listener untuk input badge_no dinamis
            $(document).on('keypress', '.badge_no_input', function(e) {
                if (e.which == 13) {
                    e.preventDefault(); // Mencegah submit form

                    let badgeNo = $(this).val(); // Ambil nilai badge_no

                    $.ajax({
                        url: '/participants/' + badgeNo, // URL untuk mengambil data peserta
                        type: 'GET',
                        success: function(data) {
                            // Temukan parent row dari input yang di-trigger
                            let parentRow = $(e.target).closest('.participant-row');

                            // Isi field-field form dengan data dari respons AJAX
                            parentRow.find('.employee_name_input').val(data.employee_name);
                            parentRow.find('.position_input').val(data.position);
                            parentRow.find('.dept_input').val(data.dept);
                            // Mengisi nilai peserta_id
                        },
                        error: function() {
                            alert('Participant not found'); // Jika peserta tidak ditemukan
                        }
                    });
                }
            })
        });

        document.getElementById('add-participant').addEventListener('click', function() {
            const container = document.getElementById('participants-container');
            const index = container.children.length;
            const newRow = document.createElement('div');
            newRow.classList.add('participant-row');


            newRow.innerHTML = `
            <table class="w-full text-sm text-center text-gray-500 dark:text-gray-400 mb-5">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <td class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    Badge No
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Employee Name
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Dept
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Position
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Theory Result
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Practise Result
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Level
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Final Judgement
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    license
                                </th>
                            </tr>
                        <td scope="col" class="px-1">
                            <input type="text" name="participants[${index}][badge_no]" id="badge_no_${index}"
                                class="bg-gray-50 badge_no_input border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        </td>
                        <td scope="col" class="px-1">
                            <input type="text" name="participants[${index}][employee_name]" id="employee_name_${index}"
                                class="bg-gray-50 border employee_name_input border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                readonly>
                        </td>
                        <td scope="col" class="px-1">
                            <input type="text" name="participants[${index}][dept]" id="dept_${index}"
                                class="bg-gray-50 dept_input border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" readonly>
                        </td>
                        <td scope="col" class="px-1">
                            <input type="text" name="participants[${index}][position]" id="position_${index}"
                                class="bg-gray-50 border position_input border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                readonly>
                        </td>
                        <input type="hidden" name="participants[0][peserta_id]" class="peserta_id_input">
                        <td scope="col" class="px-1">
                            <select id="category" name="participants[${index}][theory_result]"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                <option name="Pass" value="Pass">Pass</option>
                                <option name="Fail" value="Fail">Fail</option>
                                <option name="N/A" value="N/A">N/A</option>

                            </select>
                        </td>
                        <td scope="col" class="px-1">
                            <select id="category" name="participants[${index}][practical_result]"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                <option name="Pass" value="Pass">Pass</option>
                                <option name="Fail" value="Fail">Fail</option>
                                <option name="N/A" value="N/A">N/A</option>

                            </select>
                        </td>
                        <td scope="col" class="px-1">
                            <select id="category" name="participants[${index}][level]"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                <option name="Level 1" value="Level 1">Level 1</option>
                                <option name="Level 2" value="Level 2">Level 2</option>
                                <option name="Level 3" value="Level 3">Level 3</option>
                                <option name="Level 4" value="Level 4">Level 4</option>
                                <option name="N/A" value="N/A">N/A</option>
                            </select>
                        </td>
                        <td scope="col" class="px-1">
                            <select id="final_judgement" name="participants[${index}][final_judgement]"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                <option name="Attend " value="Attend ">Attend </option>
                                <option name="Competence" value="Competence">Competence</option>
                                <option name="N/A" value="N/A">N/A</option>

                            </select>
                        </td>
                        <td scope="col" class="px-1">
                            <input type="hidden" name="participants[${index}][license]" value="0">
                            <input id="license-checkbox-${index}" name="participants[${index}][license]" type="checkbox"
                                value="1"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                {{ old('license', 0) ? 'checked' : '' }}>

                        </td>
                        <input type="hidden" name="participant_count" value="1">
                    </thead>
                </table>        
                
    `;
            console.log(`Adding participant with index ${index}`);
            container.appendChild(newRow);
            hiddenInput.value = checkbox.checked ? "1" : "0";
        });
    </script>

</body>




</html>