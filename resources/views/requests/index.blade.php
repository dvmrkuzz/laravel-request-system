<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ Auth::user()->isAdministrator() ? 'All Requests' : 'My Requests' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('status'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                    {{ session('status') }}
                </div>
            @endif

            @can('create', App\Models\ServiceRequest::class)
                <a href="{{ route('requests.create') }}"
                   class="inline-block mb-4 px-4 py-2 bg-gray-800 text-white rounded">
                    New Request
                </a>
            @endcan

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b">
                            <th class="py-2">ID</th>
                            <th class="py-2">Requester</th>
                            <th class="py-2">Item</th>
                            <th class="py-2">Qty</th>
                            <th class="py-2">Status</th>
                            <th class="py-2"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($serviceRequests as $serviceRequest)
                            <tr class="border-b">
                                <td class="py-2">{{ $serviceRequest->id }}</td>
                                <td class="py-2">{{ $serviceRequest->requester_name }}</td>
                                <td class="py-2">{{ $serviceRequest->item_name }}</td>
                                <td class="py-2">{{ $serviceRequest->quantity }}</td>
                                <td class="py-2">{{ $serviceRequest->status }}</td>
                                <td class="py-2">
                                    <a href="{{ route('requests.show', $serviceRequest) }}"
                                       class="text-blue-600 underline">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-4 text-gray-500">
                                    No requests found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $serviceRequests->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>