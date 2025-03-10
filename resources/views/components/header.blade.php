<header class="flex bg-gray-900 text-white justify-between py-4 px-5 pt-5 items-center">
    <a href="{{ route('find-falcone') }}">
        <span class="font-bold text-lg">Finding <span class="text-blue-700">Falcone</span></span>
    </a>
    <nav class="absolute left-1/2 transform -translate-x-1/2 flex space-x-10">
        <a href="{{ route('find-falcone.story') }}"
            class="hover:underline text-gray-200 {{ request()->routeIs('find-falcone.story') ? 'font-bold' : '' }}">
            Story
        </a>
        <a href="{{ route('find-falcone.instructions') }}"
            class="hover:underline text-gray-200 {{ request()->routeIs('find-falcone.instructions') ? 'font-bold' : '' }}">
            Instructions
        </a>
    </nav>
    </div>
    @if (request()->routeIs('find-falcone'))
        <div class="flex items-center gap-8">
            <div class="text-center px-4 py-2 bg-gray-800 rounded-lg shadow-sm">
                <h2 class="text-sm font-semibold">Elapsed Time</h2>
                <h1 class="text-lg font-bold" x-data="{ elapsed: 0 }" x-init="window.gameTimer = setInterval(() => elapsed++, 1000);
                window.addEventListener('reset-game', () => { elapsed = 0; });"
                    x-text="String(Math.floor(elapsed / 3600)).padStart(2, '0') + ':' + 
                        String(Math.floor((elapsed % 3600) / 60)).padStart(2, '0') + ':' + 
                        String(elapsed % 60).padStart(2, '0')">
                </h1>
            </div>
            <div x-data>
                <button @click="$dispatch('reset-game')" type="button"
                    class="bg-white text-black px-6 py-2 font-bold rounded hover:bg-gray-200 transition">
                    Reset
                </button>
            </div>
    @endif
</header>
