<header class="flex w-full items-center justify-between border-b-2 border-gray-800 bg-gray-800 p-2">
    <!-- logo -->
    <div class="flex items-center space-x-2">
        <a type="button" href="">
            <svg class="h-8 w-8 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
        </a>
    </div>

    <!-- button profile -->
    <div>
        <button type="button" @click="profileOpen = !profileOpen" @click.outside="profileOpen = false"
            class="h-9 w-9 overflow-hidden rounded-full">
            <img src="https://plchldr.co/i/40x40?bg=111111" alt="plchldr.co" />
        </button>

    </div>
</header>

<div class="flex">
