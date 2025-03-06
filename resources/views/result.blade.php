<x-layout>
    <main class="container mx-auto px-4 py-10 min-h-screen flex flex-col items-center justify-center">
        <div class="bg-gray-800 rounded-lg shadow-xl p-8 max-w-2xl w-full">
            {{-- Success State --}}
            @if (isset($result['status']) && $result['status'] === 'success')
                <div class="text-center">
                    <h1 class="text-4xl font-bold text-blue-500 mb-4">Success!</h1>
                    <div class="text-xl mb-8">
                        <p>Congratulations! You found Queen Al Falcone hiding on <span
                                class="text-blue-400 font-bold">{{ $result['planet_name'] }}</span>!</p>
                    </div>

                    @if (isset($timeTaken))
                        <div class="bg-gray-700 rounded-lg p-4 mb-6">
                            <h2 class="text-lg font-medium text-blue-300 mb-2">Mission Stats</h2>
                            <p>Time taken: <span class="font-bold">{{ $timeTaken }}</span> hours</p>
                        </div>
                    @endif
                </div>
                {{-- Fail State  --}}
            @elseif(isset($result['status']) && $result['status'] === 'false')
                <div class="text-center">
                    <h1 class="text-4xl font-bold text-red-500 mb-4">Mission Failed</h1>
                    <p class="text-xl mb-8">You didn't find Queen Al Falcone. Better luck next time!</p>

                    @if (isset($timeTaken))
                        <div class="bg-gray-700 rounded-lg p-4 mb-6">
                            <h2 class="text-lg font-medium text-red-300 mb-2">Mission Stats</h2>
                            <p>Time wasted: <span class="font-bold">{{ $timeTaken }}</span> hours</p>
                        </div>
                    @endif
                </div>
                <!-- Error -->
            @else
                <div class="text-center">
                    <h1 class="text-4xl font-bold text-yellow-500 mb-4">Mission Error</h1>
                    <p class="text-xl mb-6">{{ $result['error'] ?? 'There was an error processing your mission.' }}
                    </p>
                </div>
            @endif
            {{-- Summary of Choices Portion --}}
            @if (isset($selections) && count($selections) > 0)
                <div class="mt-8">
                    <h2 class="text-lg font-medium text-gray-300 mb-3">Your Choices</h2>
                    <div class="bg-gray-700 rounded-lg p-4">
                        <ul class="divide-y divide-gray-600">
                            @foreach ($selections as $selection)
                                <li class="py-2 flex justify-between">
                                    <span>{{ $selection['planet'] }}</span>
                                    <span class="text-gray-400">{{ $selection['vehicle'] }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="mt-10 flex justify-center space-x-4">
                <a href="{{ route('find-falcone') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded transition">
                    Try Again
                </a>


            </div>
        </div>
    </main>


</x-layout>
