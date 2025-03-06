<header class="flex bg-gray-900 text-white justify-between py-4 px-5 items-center">
    <span class="font-bold text-lg">Finding <span class="text-blue-700">Falcone</span></span>
    <div class="container text-center" x-data="{ elapsed: 0 }" x-init="window.gameTimer = setInterval(() => elapsed++, 1000);
    
    // Listen for the reset event
    window.addEventListener('reset-game', () => {
        console.log('Reset timer triggered');
        elapsed = 0;
    });">
        <h2>Elapsed Time</h2>
        <h1
            x-text="String(Math.floor(elapsed / 3600)).padStart(2, '0') + ':' + 
                String(Math.floor((elapsed % 3600) / 60)).padStart(2, '0') + ':' + 
                String(elapsed % 60).padStart(2, '0')">
        </h1>
    </div>
    <div x-data>

        <button @click="$dispatch('reset-game')" type="button"
            class="bg-white text-black px-10 py-2 font-extrabold rounded hover:bg-gray-200 transition">Reset</button>
    </div>

</header>
