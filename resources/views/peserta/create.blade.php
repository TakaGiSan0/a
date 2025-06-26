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
                <form action="{{ route('peserta.store') }}" method="POST"
                    class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 dark:bg-gray-700">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2 dark:text-white" for="badge_no">
                            Badge No
                        </label>
                        <input
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            id="badge_no" name="badge_no" type="text" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-white text-sm font-bold mb-2" for="employee_name">
                            Employee Name
                        </label>
                        <input
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            id="employee_name" name="employee_name"
                            type="text" required>
                    </div>
                    <div class="relative mb-6">
                        <label class="block text-gray-700 dark:text-white text-sm font-bold mb-2" for="status">
                            Dept
                        </label>
                        <select
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            id="dept" name="dept">
                            <option value="ENG">ENG</option>
                            <option value="FACI">FACI</option>
                            <option value="FIN">FIN</option>
                            <option value="HRD">HRD</option>
                            <option value="IT">IT</option>
                            <option value="LOG">LOG</option>
                            <option value="MAG">MAG</option>
                            <option value="MOL1">MOL1</option>
                            <option value="MOL2">MOL2</option>
                            <option value="PLAN">PLAN</option>
                            <option value="PROD">PROD</option>
                            <option value="PSP">PSP</option>
                            <option value="PURC">PURC</option>
                            <option value="QC">QC</option>
                            <option value="TOOL">TOOL</option>
                        </select>

                        
                    </div>
                    <div class="mb-6">
                        <label class="block text-gray-700 dark:text-white text-sm font-bold mb-2" for="position">
                            Position
                        </label>
                        <input
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                        id="position" name="position" type="text" required>
                    </div>
                    <div class="mb-6">
                        <label class="block text-gray-700 dark:text-white text-sm font-bold mb-2" for="position">
                            Join Date
                        </label>
                        <input
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                          id="join_date" name="join_date"
                            type="date" required>
                    </div>
                    <div class="relative mb-6">
                        <label class="block text-gray-700 dark:text-white text-sm font-bold mb-2" for="status">
                            Status
                        </label>
                        <select
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            id="status" name="status">
                            <option value="Active">Active</option>
                            <option value="Non Active">Non Active</option>
                        </select>

                        
                    </div>


                    <div class="mb-6">
                        <label class="block text-gray-700 dark:text-white text-sm font-bold mb-2" for="position">
                            Category Level
                        </label>
                        <input
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            id="category_level" name="category_level" type="text" required>
                    </div>
                    <div class="relative mb-6">
                        <label class="block text-gray-700 dark:text-white text-sm font-bold mb-2" for="gender">
                            Gender
                        </label>
                        <select
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            id="gender" name="gender">
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>

                        
                    </div>

                    <div class="flex items-center justify-between">
                        <button
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mr-4"
                            type="submit">
                            Add
                        </button>
                        <a href="{{ route('dashboard.peserta') }}"
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline ml-4">
                            Cancel
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</body>