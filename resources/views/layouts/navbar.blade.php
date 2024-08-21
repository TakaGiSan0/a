<nav class="bg-blue-600 text-white shadow-md fixed w-full z-10">
    <div class="container mx-auto px-4 py-4 flex justify-between items-center">
        <div class="text-lg font-semibold">
            <a href="">Admin Dashboard</a>
        </div>
        <div class="hidden md:flex items-center space-x-4">
            <a href="#" class="px-4 py-2 hover:bg-blue-500 rounded">Profile</a>
            <a href="#" class="px-4 py-2 hover:bg-blue-500 rounded">Logout</a>
        </div>
        <button @click="open = !open" class="md:hidden focus:outline-none">
            <svg class="w-6 h-6 text-white" fill="none" stroke-linecap="round" stroke-linejoin="round"
                stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                <path d="M4 6h16M4 12h16m-7 6h7"></path>
            </svg>
        </button>
    </div>
</nav>