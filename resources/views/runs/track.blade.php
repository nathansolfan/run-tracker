<!-- Add these in the head section or before your other scripts -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<x-layouts.app :title="__('Track Run')">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-6">
                        {{ __('Track Run') }}
                    </h2>
                    
                    <div class="bg-gray-100 p-6 rounded-lg mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                            <div class="text-center">
                                <p class="text-sm font-medium text-gray-600">Time</p>
                                <p id="time-display" class="text-3xl font-bold">00:00:00</p>
                            </div>
                            <div class="text-center">
                                <p class="text-sm font-medium text-gray-600">Distance</p>
                                <p class="text-3xl font-bold"><span id="distance-display">0.00</span> <span class="text-sm">mi</span></p>
                            </div>
                            {{-- <div class="text-center">
                                <p class="text-sm font-medium text-gray-600">Pace</p>
                                <p id="pace-display" class="text-3xl font-bold">--:--</p>
                            </div> --}}
                        </div>
                        
                        <div class="flex justify-center">
                            <button id="start-tracking" class="px-6 py-2 bg-green-600 text-white rounded-md mr-3">Start Run</button>
                            <button id="stop-tracking" class="px-6 py-2 bg-red-600 text-white rounded-md" disabled>End Run</button>
                        </div>

                        <div class="bg-blue-50 p-6 rounded-lg mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Route Map</h3>
                            <div id="route-map" class="h-124 w-full rounded-lg"></div>
                            
                            <div class="mt-4">
                                <label class="block font-medium text-sm text-gray-700 mb-2">Route Type</label>
                                <select id="route-type" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="random">Random Movement</option>
                                    <option value="path">Follow Waypoints</option>
                                </select>
                            </div>

                            <div class="mt-4 flex items-center">
                                <span class="mr-3 text-sm font-medium text-gray-700">Use Real GPS</span>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" id="use-real-gps" class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-blue-600 peer-checked:after:translate-x-full after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                                </label>
                            </div>
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
    {{-- <script src="{{ asset('js/gps-simulator.js') }}"></script>
    <script src="{{ asset('js/run-tracker.js') }}"></script> --}}
{{--     
    <!-- Add this debugging script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded');
            
            // Check if the map div exists
            const mapElement = document.getElementById('route-map');
            console.log('Map element exists:', !!mapElement);
            
            // Check if Leaflet is loaded
            console.log('Leaflet loaded:', typeof L !== 'undefined');
            
            // Try to initialize a basic map
            if (typeof L !== 'undefined' && mapElement) {
                try {
                    const debugMap = L.map('route-map').setView([40.7128, -74.0060], 13);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                    }).addTo(debugMap);
                    console.log('Debug map initialized successfully');
                } catch (e) {
                    console.error('Error initializing map:', e);
                }
            }
        });
    </script> --}}
</x-layouts.app>