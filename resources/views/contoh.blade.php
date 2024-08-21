@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="bg-gray-100 dark:bg-gray-800 transition-colors duration-300">
        <div class="container mx-auto p-4">
            <form id="autoSaveForm" class="space-y-4" action="#" method="POST">
                <div class="grid gap-4 mb-4 sm:grid-cols-2">
                    <!-- Input fields lainnya -->

                    <!-- Tempat untuk input Training Name -->
                    <div id="trainingNameContainer">
                        <div class="training-name-input">
                            <label for="trainingName1" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Training Name</label>
                            <input type="text" name="trainingName[]" id="trainingName1"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                required="">
                        </div>
                    </div>

                    <!-- Tombol Add untuk menambah input Training Name -->
                    <button type="button" id="addTrainingName"
                        class="text-white inline-flex justify-center items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        <svg class="mr-1 -ml-1 w-6 h-6" fill="currentColor" viewbox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                clip-rule="evenodd" />
                        </svg>
                        Add Training Name
                    </button>
                </div>
                <button type="submit"
                    class="text-white inline-flex justify-center items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Submit
                </button>
            </form>
        </div>
    </div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('autoSaveForm');
        const inputs = form.querySelectorAll('input, textarea, select');
        const trainingNameContainer = document.getElementById('trainingNameContainer');
        const addTrainingNameButton = document.getElementById('addTrainingName');
        let trainingNameCount = 1; // Mulai dari 1 untuk input pertama

        // Fungsi untuk menambah input Training Name baru
        addTrainingNameButton.addEventListener('click', () => {
            trainingNameCount++;
            const newInputDiv = document.createElement('div');
            newInputDiv.classList.add('training-name-input');
            newInputDiv.innerHTML = `
                <label for="trainingName${trainingNameCount}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Training Name</label>
                <input type="text" name="trainingName[]" id="trainingName${trainingNameCount}"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    required="">
            `;
            trainingNameContainer.appendChild(newInputDiv);
        });

        // Muat data yang disimpan dari Local Storage
        inputs.forEach(input => {
            const savedValue = localStorage.getItem(input.name);
            if (savedValue) {
                input.value = savedValue;
            }

            // Simpan data ke Local Storage setiap kali ada perubahan
            input.addEventListener('input', () => {
                localStorage.setItem(input.name, input.value);
            });
        });

        // Hapus data di Local Storage ketika form disubmit
        form.addEventListener('submit', () => {
            inputs.forEach(input => {
                localStorage.removeItem(input.name);
            });
        });
    });
</script>
@endsection
