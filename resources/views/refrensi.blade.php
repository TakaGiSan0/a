<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User Example</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 dark:bg-gray-800">
    <div class="container mx-auto p-8">
        <h1 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">Add Users</h1>
        <form id="userForm">
            <div id="userContainer" class="space-y-4">
                <!-- User Input Template -->
                <div class="user-input grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="username1" class="block text-sm font-medium text-gray-900 dark:text-white">Username</label>
                        <input type="text" name="username[]" id="username1"
                            class="mt-1 block w-full bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Enter username" required>
                    </div>
                    <div>
                        <label for="email1" class="block text-sm font-medium text-gray-900 dark:text-white">Email</label>
                        <input type="email" name="email[]" id="email1"
                            class="mt-1 block w-full bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Enter email" required>
                    </div>
                </div>
            </div>
            <button type="button" id="addUserBtn"
                class="mt-4 bg-blue-600 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-500 dark:hover:bg-blue-600 dark:focus:ring-blue-800">
                Add User
            </button>
            <button type="submit"
                class="mt-4 bg-green-600 text-white px-4 py-2 rounded-lg shadow hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-green-300 dark:bg-green-500 dark:hover:bg-green-600 dark:focus:ring-green-800">
                Submit
            </button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const userContainer = document.getElementById('userContainer');
            const addUserBtn = document.getElementById('addUserBtn');
            let userCount = 1; // Mulai dari 1 untuk input pertama

            // Fungsi untuk menambah input User baru
            addUserBtn.addEventListener('click', () => {
                userCount++;
                const newUserDiv = document.createElement('div');
                newUserDiv.classList.add('user-input', 'grid', 'grid-cols-1', 'sm:grid-cols-2', 'gap-4', 'mt-4');
                newUserDiv.innerHTML = `
                    <div>
                        <label for="username${userCount}" class="block text-sm font-medium text-gray-900 dark:text-white">Username</label>
                        <input type="text" name="username[]" id="username${userCount}"
                            class="mt-1 block w-full bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Enter username" required>
                    </div>
                    <div>
                        <label for="email${userCount}" class="block text-sm font-medium text-gray-900 dark:text-white">Email</label>
                        <input type="email" name="email[]" id="email${userCount}"
                            class="mt-1 block w-full bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Enter email" required>
                    </div>
                `;
                userContainer.appendChild(newUserDiv);
            });
        });
    </script>
</body>

</html>
