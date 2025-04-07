<x-layouts.app :title="__('Track Run')">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-6">
                        {{ __('Track Run') }}
                    </h2>
                    
                    <div class="bg-gray-100 p-6 rounded-lg mb-6">
                        <div class="grid grid-cols-3 gap-4 mb-8">
                            <div class="text-center">
                                <p class="text-sm font-medium text-gray-600">Time</p>
                                <p id="time-display" class="text-3xl font-bold">00:00:00</p>
                            </div>
                            <div class="text-center">
                                <p class="text-sm font-medium text-gray-600">Distance</p>
                                <p class="text-3xl font-bold"><span id="distance-display">0.00</span> <span class="text-sm">mi</span></p>
                            </div>
                            <div class="text-center">
                                <p class="text-sm font-medium text-gray-600">Pace</p>
                                <p id="pace-display" class="text-3xl font-bold">--:--</p>
                            </div>
                        </div>
                        
                        <div class="flex justify-center">
                            <button id="start-tracking" class="px-6 py-2 bg-green-600 text-white rounded-md mr-3">Start Run</button>
                            <button id="stop-tracking" class="px-6 py-2 bg-red-600 text-white rounded-md" disabled>End Run</button>
                        </div>
                    </div>
                    
                    <div class="text-center">
                        <p class="text-sm text-gray-500 mb-1">Using GPS simulator for development</p>
                        <a href="{{ route('runs.index') }}" class="text-blue-600 hover:underline">Back to runs</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('js/gps-simulator.js') }}"></script>
    <script src="{{ asset('js/run-tracker.js') }}"></script>
</x-layouts.app>