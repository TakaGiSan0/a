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
    <div class="flex-1 p-4 bg-gray-100 dark:bg-gray-400">
        <div class="overflow-x-auto shadow-md ">
    <div class="w-full max-w-xs mx-auto">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('peserta.update', $peserta->id) }}" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 dark:bg-gray-700">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2 dark:text-white" for="badge_no">
                    Badge No
                </label>
                <input
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                    name="badge_no" id="badge_no" type="text" value="{{ old('badge_no', $peserta->badge_no) }}">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2 dark:text-white" for="employee_name">
                    Employee Name
                </label>
                <input
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                    id="employee_name" type="text" name="employee_name" value="{{ old('employee_name', $peserta->employee_name) }}">
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 dark:text-white text-sm font-bold mb-2" for="status">
                    Dept
                </label>
                <select
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                    id="dept" name="dept">
                    <option value="ENG">ENG</option>
                            <option value="FACI" {{ old('dept', $peserta->dept) == 'FACI' ? 'selected' : '' }}>FACI</option>
                            <option value="FIN" {{ old('dept', $peserta->dept) == 'FIN' ? 'selected' : '' }}>FIN</option>
                            <option value="HRD" {{ old('dept', $peserta->dept) == 'HRD' ? 'selected' : '' }}>HRD</option>
                            <option value="IT" {{ old('dept', $peserta->dept) == 'IT' ? 'selected' : '' }}>IT</option>
                            <option value="LOG" {{ old('dept', $peserta->dept) == 'LOG' ? 'selected' : '' }}>LOG</option>
                            <option value="MAG" {{ old('dept', $peserta->dept) == 'MAG' ? 'selected' : '' }}>MAG</option>
                            <option value="MOL1" {{ old('dept', $peserta->dept) == 'MOL1' ? 'selected' : '' }}>MOL1</option>
                            <option value="MOL2" {{ old('dept', $peserta->dept) == 'MOL2' ? 'selected' : '' }}>MOL2</option>
                            <option value="PLAN" {{ old('dept', $peserta->dept) == 'PLAN' ? 'selected' : '' }}>PLAN</option>
                            <option value="PROD" {{ old('dept', $peserta->dept) == 'PROD' ? 'selected' : '' }}>PROD</option>
                            <option value="PSP" {{ old('dept', $peserta->dept) == 'PSP' ? 'selected' : '' }}>PSP</option>
                            <option value="PURC" {{ old('dept', $peserta->dept) == 'PURC' ? 'selected' : '' }}>PURC</option>
                            <option value="QC" {{ old('dept', $peserta->dept) == 'QC' ? 'selected' : '' }}>QC</option>
                            <option value="TOOL" {{ old('dept', $peserta->dept) == 'TOOL' ? 'selected' : '' }}>TOOL</option>
                    
                </select>
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 dark:text-white text-sm font-bold mb-2" for="position">
                    Position
                </label>
                <input
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                    id="position" type="text" name="position" value="{{ old('position', $peserta->position) }}">
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 dark:text-white text-sm font-bold mb-2" >
                    Join Date
                </label>
                <input
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                    id="join_date" name="join_date" type="date" value="{{ old('join_date', $peserta->join_date) }}">
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 dark:text-white text-sm font-bold mb-2" for="status">
                    Status
                </label>
                <select
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                    id="status" name="status">
                    <option value="Active" {{ old('status', $peserta->status) == 'Active' ? 'selected' : '' }}>Active</option>
                    <option value="Female" {{ old('status', $peserta->status) == 'Non Active' ? 'selected' : '' }}>Non Active</option>
                </select>
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold dark:text-white mb-2" for="position">
                    Category Level
                </label>
                <input
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                    id="category_level" name="category_level" type="text" value="{{ old('category_level', $peserta->category_level) }}">
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 dark:text-white text-sm font-bold mb-2" for="gender">
                    Gender
                </label>
                <select
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                    id="gender" name="gender">
                    <option value="Male" {{ old('gender', $peserta->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ old('gender', $peserta->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                </select>
            </div>
            <div class="flex items-center justify-center">
                <button
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mr-4"
                    type="submit">
                    Edit
                </button>
                <a href="{{ route('dashboard.peserta') }}"
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline ml-4">
                    Cancel
                </a>
                
            </div>
        </form>
        
    </div>
</body>
