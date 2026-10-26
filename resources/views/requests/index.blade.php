<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ auth()->user()->isAdministrator() ? __('All Requests') : __('My Requests') }}
            </h2>
            @unless (auth()->user()->isAdministrator())
                <a href="{{ route('requests.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                    {{ __('New request') }}
                </a>
            @endunless
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 text-sm text-green-700">{{ session('status') }}</div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 overflow-x-auto">
                    @if ($requests->isEmpty())
                        <p>{{ __('No requests yet.') }}</p>
                    @else
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="text-left border-b">
                                    <th class="py-2 pr-4">ID</th>
                                    <th class="py-2 pr-4">Owner</th>
                                    <th class="py-2 pr-4">Item</th>
                                    <th class="py-2 pr-4">Quantity</th>
                                    <th class="py-2 pr-4">Status</th>
                                    <th class="py-2 pr-4"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($requests as $serviceRequest)
                                    <tr class="border-b">
                                        <td class="py-2 pr-4">{{ $serviceRequest->id }}</td>
                                        <td class="py-2 pr-4">{{ $serviceRequest->requester_name }}</td>
                                        <td class="py-2 pr-4">{{ $serviceRequest->item_name }}</td>
                                        <td class="py-2 pr-4">{{ $serviceRequest->quantity }}</td>
                                        <td class="py-2 pr-4 capitalize">{{ $serviceRequest->status }}</td>
                                        <td class="py-2 pr-4">
                                            <a class="text-indigo-600 underline" href="{{ route('requests.show', $serviceRequest) }}">{{ __('View') }}</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
