<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-3">
                    <p>{{ __("You're logged in!") }}</p>
                    <p>
                        {{ auth()->user()->isAdministrator()
                            ? __('You can review every submitted request and update its status.')
                            : __('You can submit a request and track only the requests you own.') }}
                    </p>
                    <p>
                        <a href="{{ route('requests.index') }}" class="text-indigo-600 underline">
                            {{ __('Open service requests') }}
                        </a>
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-500">{{ __('Total') }}</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $totalCount }}</p>
                </div>
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-500">{{ __('Pending') }}</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $pendingCount }}</p>
                </div>
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-500">{{ __('Approved') }}</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $approvedCount }}</p>
                </div>
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-500">{{ __('Rejected') }}</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $rejectedCount }}</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
