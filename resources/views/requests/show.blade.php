<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Request') }} #{{ $serviceRequest->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status'))
                <div class="text-sm text-green-700">{{ session('status') }}</div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <dl class="p-6 grid grid-cols-1 gap-4 text-sm text-gray-900">
                    <div>
                        <dt class="font-medium text-gray-500">Owner</dt>
                        <dd>{{ $serviceRequest->requester_name }} ({{ $serviceRequest->requester_email }})</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-500">Item</dt>
                        <dd>{{ $serviceRequest->item_name }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-500">Quantity</dt>
                        <dd>{{ $serviceRequest->quantity }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-500">Purpose</dt>
                        <dd>{{ $serviceRequest->purpose }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-500">Status</dt>
                        <dd class="capitalize">{{ $serviceRequest->status }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-500">Submitted</dt>
                        <dd>{{ $serviceRequest->created_at?->format('Y-m-d H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-500">Last updated</dt>
                        <dd>{{ $serviceRequest->updated_at?->format('Y-m-d H:i') }}</dd>
                    </div>
                </dl>
            </div>

            <a href="{{ route('requests.index') }}" class="text-indigo-600 underline text-sm">{{ __('Back to requests') }}</a>

            @if (auth()->user()->isAdministrator())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <form method="POST" action="{{ route('requests.status', $serviceRequest) }}" class="p-6 space-y-4">
                        @csrf
                        @method('PATCH')

                        <div>
                            <x-input-label for="status" :value="__('Update status')" />
                            <select id="status" name="status" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                @foreach (['pending', 'approved', 'rejected'] as $status)
                                    <option value="{{ $status }}" @selected($serviceRequest->status === $status)>{{ $status }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>

                        <x-primary-button>{{ __('Save status') }}</x-primary-button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
