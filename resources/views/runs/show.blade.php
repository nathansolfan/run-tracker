<x-layouts.app :title="__('Run Details')">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                            {{ __('Run Details') }}
                        </h2>
                        <div class="flex space-x-2">
                            <a href="{{ route('runs.edit', $run) }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 focus:bg-yellow-700 active:bg-yellow-800 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Edit') }}
                            </a>
                            <a href="{{ route('runs.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Back to Runs') }}
                            </a>
                        </div>
                    </div>

                    {{-- @if (session('success'))
                        <div class="mb-4 px-4 py-2 bg-green-100 border border-green-200 text-green-700 rounded-md">
                            {{ session('success') }}
                        </div>
                    @endif --}}

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="bg-gray-100 p-6 rounded-lg">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Run Information</h3>
                                
                                <div class="mb-4">
                                    <p class="text-sm text-gray-500">Date</p>
                                    <p class="font-medium">{{ $run->date->format('F d, Y') }}</p>
                                </div>

                                <div class="mb-4">
                                    <p class="text-sm text-gray-500">Distance</p>
                                    <p class="font-medium">{{ number_format($run->distance, 2) }} miles</p>
                                </div>

                                <div class="mb-4">
                                    <p class="text-sm text-gray-500">Duration</p>
                                    <p class="font-medium">{{ $run->formatted_duration }}</p>
                                </div>

                                <div class="mb-4">
                                    <p class="text-sm text-gray-500">Pace</p>
                                    <p class="font-medium">{{ $run->formatted_pace }}</p>
                                </div>

                                @if ($run->notes)
                                    <div class="mb-4">
                                        <p class="text-sm text-gray-500">Notes</p>
                                        <p class="font-medium">{{ $run->notes }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div>
                            <div class="bg-blue-50 p-6 rounded-lg">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Run Statistics</h3>
                                
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-white p-4 rounded shadow">
                                        <p class="text-sm text-gray-500">Total Distance</p>
                                        <p class="font-medium text-2xl">{{ number_format($run->distance, 2) }}</p>
                                        <p class="text-xs text-gray-400">miles</p>
                                    </div>
                                    
                                    <div class="bg-white p-4 rounded shadow">
                                        <p class="text-sm text-gray-500">Average Pace</p>
                                        <p class="font-medium text-2xl">{{ explode(' ', $run->formatted_pace)[0] }}</p>
                                        <p class="text-xs text-gray-400">min/mile</p>
                                    </div>
                                    
                                    <div class="bg-white p-4 rounded shadow">
                                        <p class="text-sm text-gray-500">Total Time</p>
                                        <p class="font-medium text-2xl">{{ $run->formatted_duration }}</p>
                                        <p class="text-xs text-gray-400">hh:mm:ss</p>
                                    </div>
                                    
                                    <div class="bg-white p-4 rounded shadow">
                                        <p class="text-sm text-gray-500">Calories Burned</p>
                                        <p class="font-medium text-2xl">{{ floor($run->distance * 100) }}</p>
                                        <p class="text-xs text-gray-400">estimated</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>