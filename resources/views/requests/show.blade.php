<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Request #{{ $serviceRequest->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">

            @if (session('status'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <dl class="space-y-3">
                    <div>
                        <dt class="font-semibold">Requester</dt>
                        <dd>{{ $serviceRequest->requester_name }}</dd>
                    </div>
                    <div>
                        <dt class="font-semibold">Email</dt>
                        <dd>{{ $serviceRequest->requester_email }}</dd>
                    </div>
                    <div>
                        <dt class="font-semibold">Item</dt>
                        <dd>{{ $serviceRequest->item_name }}</dd>
                    </div>
                    <div>
                        <dt class="font-semibold">Quantity</dt>
                        <dd>{{ $serviceRequest->quantity }}</dd>
                    </div>
                    <div>
                        <dt class="font-semibold">Purpose</dt>
                        <dd>{{ $serviceRequest->purpose }}</dd>
                    </div>
                    <div>
                        <dt class="font-semibold">Status</dt>
                        <dd>{{ $serviceRequest->status }}</dd>
                    </div>
                </dl>

                @can ('updateStatus', $serviceRequest)

                    <hr class="my-6">

                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100 text-red-800 rounded">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST"
                          action="{{ route('requests.updateStatus', $serviceRequest) }}">
                        @csrf
                        @method('PATCH')

                        <label class="block mb-1" for="status">Update Status</label>
                        <select name="status" id="status"
                                class="border-gray-300 rounded mr-2">
                            <option value="pending">pending</option>
                            <option value="approved">approved</option>
                            <option value="rejected">rejected</option>
                        </select>

                        <button type="submit"
                                class="px-4 py-2 bg-gray-800 text-white rounded">
                            Save
                        </button>
                    </form>

                @endcan

                <div class="mt-6">
                    <a href="{{ route('requests.index') }}" class="text-blue-600 underline">
                        Back to list
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>