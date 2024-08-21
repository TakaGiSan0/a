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
                        @endauth</a>
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
                        <a href="">Home</a>
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

            <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
    </div>

</body>

</html>
