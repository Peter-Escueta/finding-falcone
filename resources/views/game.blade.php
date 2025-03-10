<x-layout>
    <main class="container mx-auto px-4 flex-1">
        <div class="text-center">
            <h1 class="text-5xl pt-15">
                Finding <span class="text-blue-700 font-bold">Falcone</span>
            </h1>
            <h2 class="text-2xl py-5">
                Select a <span class="text-blue-700 font-bold">Planet</span> to search in!
            </h2>
        </div>

        <form method="POST" action="{{ route('find-falcone.search') }}" x-data="gameLogic()">
            @csrf
            <input type="hidden" name="selected_planets" x-model="selectedPlanetsJson">
            <input type="hidden" name="selected_vehicles" x-model="selectedVehiclesJson">

            <div class="text-center mb-6">
                <div class="inline-block bg-gray-800 rounded-lg px-6 py-3 text-lg">
                    Total Travel Time: <span class="text-yellow-400 font-bold" x-text="totalTravelTime"></span> hours
                </div>
            </div>

            <div class="flex justify-evenly">
                <template x-for="(selection, index) in selections" :key="index">
                    <div class="flex flex-col items-center space-y-3" x-data="{ showModal: false }">

                        <button @click="showModal = true" type="button"
                            class="bg-gray-900 border-blue-700 border-2 hover:border-8 transition duration-300 rounded-full w-40 h-40 flex items-center justify-center cursor-pointer">
                            <span x-text="selection.planet?.name || 'SELECT A PLANET'"></span>
                        </button>


                        <div class="flex flex-col items-center bg-gray-800 p-3 rounded-lg w-48"
                            x-show="selection.vehicle">
                            <div>Vehicle: <span class="text-green-400" x-text="selection.vehicle?.name"></span></div>
                            <div>Time: <span class="text-yellow-400" x-text="selection.travelTime"></span> hours</div>
                        </div>

                        <div x-show="showModal"
                            class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50" x-cloak>
                            <div class="bg-gray-800 p-5 rounded-lg w-1/3">
                                <h2 class="text-center text-xl font-bold mb-4">Select a Planet & Vehicle</h2>


                                <section>
                                    <h3 class="font-semibold">Planets</h3>
                                    <ul class="mt-2 space-y-2">
                                        <template x-for="planet in availablePlanets" :key="planet.name">
                                            <li class="p-2 bg-gray-700 hover:bg-blue-600 rounded cursor-pointer"
                                                @click.prevent="selectPlanet(index, planet)">
                                                <span x-text="planet.name"></span>
                                                - <span class="text-yellow-300" x-text="planet.distance"></span> million
                                                km
                                            </li>
                                        </template>
                                    </ul>
                                </section>


                                <section class="mt-4">
                                    <h3 class="font-semibold">Vehicles</h3>
                                    <ul class="mt-2 space-y-2">
                                        <template x-for="vehicle in availableVehicles(selection.planet)"
                                            :key="vehicle.name">
                                            <li class="p-2 bg-gray-700 hover:bg-green-600 rounded cursor-pointer"
                                                @click.prevent="selectVehicle(index, vehicle)">
                                                <div class="flex justify-between">
                                                    <span>
                                                        <span x-text="vehicle.name"></span>
                                                        (Available: <span x-text="vehicle.total_no"></span>)
                                                    </span>
                                                    <span class="text-yellow-300">
                                                        Speed: <span x-text="vehicle.speed"></span> megamiles/hr
                                                    </span>
                                                </div>
                                                <div x-show="selection.planet" class="text-xs text-gray-300 mt-1">
                                                    Travel time: <span
                                                        x-text="calculateTravelTime(selection.planet, vehicle)"></span>
                                                    hours
                                                </div>
                                            </li>
                                        </template>
                                    </ul>
                                </section>
                                <div class="flex justify-center mt-4">
                                    <button @click="showModal = false" type="button"
                                        class="bg-white border-2 cursor-pointer text-black px-3 py-3 font-bold rounded-lg hover:text-white hover:bg-blue-600 transition duration-300 flex items-center justify-center">
                                        <span>Done </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <div class="flex justify-center mt-6">
                <button type="submit"
                    class="bg-white text-black px-10 py-2 font-extrabold rounded hover:bg-gray-200 transition cursor-pointer"
                    :disabled="!isSubmittable">
                    Find Falcone
                </button>
            </div>
        </form>



    </main>
    <footer class="bg-gray-1000 text-white text-center rounded-lg shadow-sm m-4 dark:bg-gray-800">
        <p> Finding Falcone | Status: <span class="text-blue-600"> Active </span> </p>
    </footer>

    <script>
        window.gameData = {
            planets: @json($planets),
            vehicles: @json($vehicles)
        };
    </script>

</x-layout>
