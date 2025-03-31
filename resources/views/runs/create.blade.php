<x-layouts.app :title="__('Log New Run')">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-6">
                        {{ __('Log New Run') }}
                    </h2>
                    
                    <form method="POST" action="{{ route('runs.store') }}">
                        @csrf
                        <div class="mb-4">
                            <x-input-label for="date" :value="__('Date')" />
                            <x-text-input id="date" type="date" name="date" :value="old('date', date('Y-m-d'))" class="block mt-1 w-full" required />
                            <x-input-error :messages="$errors->get('date')" class="mt-2" />
                        </div>
                        <div class="mb-4">
                            <x-input-label for="distance" :value="__('Distance (miles)')" />
                            <x-text-input id="distance" type="number" name="distance" :value="old('distance')" class="block mt-1 w-full" step="0.01" min="0.01" required />
                            <x-input-error :messages="$errors->get('distance')" class="mt-2" />
                        </div>
                        <div class="mb-4">
                            <x-input-label for="duration" :value="__('Duration (HH:MM:SS or MM:SS)')" />
                            <x-text-input id="duration" type="text" name="duration" :value="old('duration')" class="block mt-1 w-full" placeholder="Example: 30:45 or 1:15:30" required />
                            <x-input-error :messages="$errors->get('duration')" class="mt-2" />
                        </div>
                        <div class="mb-4">
                            <x-input-label for="notes" :value="__('Notes (optional)')" />
                            <textarea id="notes" name="notes" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('notes') }}</textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('runs.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-2">
                                {{ __('Cancel') }}
                            </a>
                            <x-primary-button>
                                {{ __('Save Run') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
