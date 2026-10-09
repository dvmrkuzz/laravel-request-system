<?php

namespace App\Http\Controllers;

use App\Models\ServiceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ServiceRequestController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('viewAny', ServiceRequest::class);

        $user = $request->user();

        $query = ServiceRequest::query()->latest();

        if (! $user->isAdministrator()) {
            $query->where('user_id', $user->id);
        }

        $serviceRequests = $query->paginate(10);

        return view('requests.index', compact('serviceRequests'));
    }

    public function create()
    {
        Gate::authorize('create', ServiceRequest::class);

        return view('requests.create');
    }

    public function store(Request $request)
    {
        Gate::authorize('create', ServiceRequest::class);

        $validated = $request->validate([
            'item_name' => ['required', 'string', 'max:150'],
            'quantity'  => ['required', 'integer', 'min:1'],
            'purpose'   => ['required', 'string', 'max:2000'],
        ]);

        $user = $request->user();

        $serviceRequest = new ServiceRequest($validated);
        $serviceRequest->requester_name  = $user->name;
        $serviceRequest->requester_email = $user->email;
        $serviceRequest->user_id = $user->id;
        $serviceRequest->status  = 'pending';
        $serviceRequest->save();

        return redirect()
            ->route('requests.index')
            ->with('status', 'Request submitted successfully.');
    }

    public function show(ServiceRequest $serviceRequest)
    {
        if (Gate::denies('view', $serviceRequest)) {
            throw new NotFoundHttpException();
        }

        return view('requests.show', compact('serviceRequest'));
    }

    public function updateStatus(Request $request, ServiceRequest $serviceRequest)
    {
        Gate::authorize('updateStatus', $serviceRequest);

        $validated = $request->validate([
            'status' => ['required', 'string', 'in:pending,approved,rejected'],
        ]);

        $serviceRequest->status = $validated['status'];
        $serviceRequest->save();

        return redirect()
            ->route('requests.show', $serviceRequest)
            ->with('status', 'Status updated successfully.');
    }
}
