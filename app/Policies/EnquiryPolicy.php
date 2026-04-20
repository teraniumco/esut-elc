<?php

namespace App\Policies;

use App\Models\Enquiry;
use App\Models\User;

class EnquiryPolicy
{
    /** Admins and supervisors can do everything */
    public function before(User $user, string $ability): bool|null
    {
        if ($user->isAdmin()) return true;
        return null;
    }

    /** View enquiry in portal */
    public function view(User $user, Enquiry $enquiry): bool
    {
        if ($user->isSupervisor()) return true;
        // Advisor can only see enquiries assigned to them
        return $enquiry->assignments()->where('advisor_id', $user->id)->where('is_active', true)->exists();
    }

    /** Only admin can assign */
    public function assign(User $user, Enquiry $enquiry): bool
    {
        return $user->isAdmin();
    }

    /** Advisor can respond if assigned and enquiry is open */
    public function respond(User $user, Enquiry $enquiry): bool
    {
        if (!$enquiry->isOpen()) return false;
        if ($user->isSupervisor()) return true; // Supervisor can also edit
        return $enquiry->assignments()->where('advisor_id', $user->id)->where('is_active', true)->exists();
    }

    /** Only supervisors / admins can approve or reject */
    public function review(User $user, Enquiry $enquiry): bool
    {
        return $user->canApprove();
    }

    /** Only admin can close */
    public function close(User $user, Enquiry $enquiry): bool
    {
        return $user->isAdmin();
    }
}
