<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreServiceRequestRequest;
use App\Models\ServiceRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServiceRequestController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $requests = $user->isAdministrator()
            ? ServiceRequest::query()->latest()->get()
            : $user->serviceRequests()->latest()->get();

        return view('requests.index', [
            'requests' => $requests,
        ]);
    }

    public function create(Request $request): View
    {
        abort_if($request->user()->isAdministrator(), 403);

        return view('requests.create');
    }

    public function store(StoreServiceRequestRequest $request): RedirectResponse
    {
        abort_if($request->user()->isAdministrator(), 403);

        $validated = $request->validated();

        $user = $request->user();

        $user->serviceRequests()->create([
            'requester_name' => $user->name,
            'requester_email' => $user->email,
            'item_name' => $validated['item_name'],
            'quantity' => $validated['quantity'],
            'purpose' => $validated['purpose'],
            'status' => 'pending',
        ]);

        return redirect()
            ->route('requests.index')
            ->with('status', 'Request submitted.');
    }

    public function show(Request $request, ServiceRequest $serviceRequest): View
    {
        $this->authorizeView($request, $serviceRequest);

        return view('requests.show', [
            'serviceRequest' => $serviceRequest,
        ]);
    }

    public function updateStatus(Request $request, ServiceRequest $serviceRequest): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,approved,rejected'],
        ]);

        $serviceRequest->status = $validated['status'];
        $serviceRequest->save();

        return redirect()
            ->route('requests.show', $serviceRequest)
            ->with('status', 'Request status updated.');
    }

    private function authorizeView(Request $request, ServiceRequest $serviceRequest): void
    {
        $user = $request->user();

        if ($user->isAdministrator()) {
            return;
        }

        if ((int) $serviceRequest->user_id !== (int) $user->id) {
            abort(403);
        }
    }
}
