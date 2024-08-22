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

    <div class="container mx-auto">
        <!-- component -->
        <header>
            <nav x-data="{ open: false }"
                class="flex h-auto w-auto bg-white shadow-lg rounded-lg justify-between
        md:h-16">
                <div class="flex w-full justify-between ">
                    <div :class="open ? 'hidden' : 'flex'"
                        class="flex px-6 w-1/2 items-center font-semibold
            md:w-1/5 md:px-1 md:flex md:items-center md:justify-center"
                        x-transition:enter="transition ease-out duration-300">
                        <a href="">
                            @auth
                                {{ Auth::user()->name }}
                            @endauth
                        </a>
                    </div>

                    <div x-show="open" x-transition:enter="transition ease-in-out duration-300"
                        class="flex flex-col w-full h-auto
            md:hidden">
                        <div class="flex flex-col items-center justify-center gap-2">
                            <a href="">Home</a>
                            <a href="">About Us</a>
                            <a href="">Products</a>
                            <a href="">Contact</a>
                            <button>Login</button>
                            <button>Sign Up</button>
                        </div>
                    </div>
                    <div class="hidden w-3/5 items-center justify-evenly font-semibold
            md:flex">
                        <a href="{{ route('superadmin.create') }}">Home</a>
                        <a href="">About Us</a>
                        <a href="">Products</a>
                        <a href="">Contact</a>
                    </div>
                    <div class="hidden w-1/5 items-center justify-evenly font-semibold
            md:flex">
                        <button>Login</button>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit">Logout</button>
                        </form>
                    </div>
                    <button
                        class="text-gray-500 w-10 h-10 relative focus:outline-none bg-white
                            md:hidden
                          "
                        @click="open = !open">
                        <span class="sr-only">Open main menu</span>
                        <div class="block w-5 absolute left-1/2 top-1/2   transform  -translate-x-1/2 -translate-y-1/2">
                            <span aria-hidden="true"
                                class="block absolute h-0.5 w-5 bg-current transform transition duration-500 ease-in-out"
                                :class="{ 'rotate-45': open, ' -translate-y-1.5': !open }"></span>
                            <span aria-hidden="true"
                                class="block absolute  h-0.5 w-5 bg-current   transform transition duration-500 ease-in-out"
                                :class="{ 'opacity-0': open }"></span>
                            <span aria-hidden="true"
                                class="block absolute  h-0.5 w-5 bg-current transform  transition duration-500 ease-in-out"
                                :class="{ '-rotate-45': open, ' translate-y-1.5': !open }"></span>
                        </div>
                    </button>
                </div>
            </nav>
        </header>



        <div class="relative overflow-x-auto">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            No
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Doc Ref
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Training Name
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Trainer Name
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Rev
                        </th>
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
                            Practical Result
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Level
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Final Judgement
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Category
                        </th>
                    </tr>
                </thead>
                <?php $no = 0; ?>
                @foreach ($training_records as $rc)
                <tbody>
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <th scope="row"
                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ ++$no }}
                        </th>
                            <td class="px-6 py-4">
                                {{ $rc->doc_ref }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $rc->training_name }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $rc->trainer_name }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $rc->rev }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $rc->peserta->badge_no ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $rc->peserta->employee_name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $rc->peserta->dept ?? 'N/A' }}

                            </td>
                            <td class="px-6 py-4">
                                {{ $rc->peserta->position ?? 'N/A' }}

                            </td>
                            <td class="px-6 py-4">
                                {{ $rc->theory->name ?? 'N/A' }}

                            </td>
                            <td class="px-6 py-4">
                                {{ $rc->practical->name ?? 'N/A' }}

                            </td>
                            <td class="px-6 py-4">
                                {{ $rc->level->level ?? 'N/A' }}

                            </td>

                            <td class="px-6 py-4">
                                {{ $rc->final_judgement->name ?? 'N/A' }}

                            </td>
                            <td class="px-6 py-4">
                                {{ $rc->trainingCategory->name ?? 'N/A' }}

                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center mb-4">
                                    <input id="license-checkbox"
                                           type="checkbox"
                                           name="license"
                                           value="1"
                                           disabled
                                           class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                           @if($rc->license) checked @endif>
                                    <label for="license-checkbox"
                                           class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">License/Certification</label>
                                </div>
                            </td>
                        </tr>

                </tbody>
                @endforeach
            </table>
        </div>

        <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
    </div>

</body>

</html>
