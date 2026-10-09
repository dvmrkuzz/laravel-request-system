<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            New Request
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 text-red-800 rounded">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('requests.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label class="block mb-1" for="item_name">Item Name</label>
                        <input type="text" name="item_name" id="item_name"
                               value="{{ old('item_name') }}" maxlength="150"
                               class="w-full border-gray-300 rounded">
                    </div>

                    <div class="mb-4">
                        <label class="block mb-1" for="quantity">Quantity</label>
                        <input type="number" name="quantity" id="quantity"
                               value="{{ old('quantity') }}" min="1"
                               class="w-full border-gray-300 rounded">
                    </div>

                    <div class="mb-4">
                        <label class="block mb-1" for="purpose">Purpose</label>
                        <textarea name="purpose" id="purpose" rows="4" maxlength="2000"
                                  class="w-full border-gray-300 rounded">{{ old('purpose') }}</textarea>
                    </div>

                    <button type="submit"
                            class="px-4 py-2 bg-gray-800 text-white rounded">
                        Submit Request
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>