<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Submit a request') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form method="POST" action="{{ route('requests.store') }}" class="p-6 space-y-4">
                    @csrf

                    <div>
                        <x-input-label for="item_name" :value="__('Item name')" />
                        <x-text-input id="item_name" name="item_name" type="text" class="block mt-1 w-full" :value="old('item_name')" required />
                        <x-input-error :messages="$errors->get('item_name')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="quantity" :value="__('Quantity')" />
                        <x-text-input id="quantity" name="quantity" type="number" min="1" class="block mt-1 w-full" :value="old('quantity')" required />
                        <x-input-error :messages="$errors->get('quantity')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="purpose" :value="__('Purpose')" />
                        <textarea id="purpose" name="purpose" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="4" required>{{ old('purpose') }}</textarea>
                        <x-input-error :messages="$errors->get('purpose')" class="mt-2" />
                    </div>

                    <x-primary-button>{{ __('Submit request') }}</x-primary-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
