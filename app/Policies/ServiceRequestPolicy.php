<?php

namespace App\Policies;

use App\Models\ServiceRequest;
use App\Models\User;

class ServiceRequestPolicy {
    public function viewAny(User $user): bool {
        return true;
    }

    public function view(User $user, ServiceRequest $serviceRequest): bool {
        return $user->isAdministrator() || $serviceRequest->user_id === $user->id;
    }

    public function create(User $user): bool {
        return ! $user->isAdministrator();
    }

    public function updateStatus(User $user, ServiceRequest $serviceRequest): bool {
        return $user->isAdministrator();
    }
}