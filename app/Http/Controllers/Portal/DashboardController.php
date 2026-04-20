<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use App\Models\EnquiryResponse;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return $this->adminDashboard();
        } elseif ($user->isSupervisor()) {
            return $this->supervisorDashboard();
        } else {
            return $this->advisorDashboard();
        }
    }

    private function adminDashboard()
    {
        $stats = [
            'total'             => Enquiry::count(),
            'received'          => Enquiry::where('status', 'received')->count(),
            'unassigned'        => Enquiry::unassigned()->count(),
            'in_progress'       => Enquiry::whereIn('status', ['under_review', 'in_progress'])->count(),
            'awaiting_approval' => Enquiry::where('status', 'awaiting_approval')->count(),
            'responded'         => Enquiry::where('status', 'responded')->count(),
            'urgent'            => Enquiry::where('urgency', 'urgent')->whereNotIn('status', ['responded', 'closed'])->count(),
            'total_advisors'    => User::active()->advisors()->count(),
            'total_supervisors' => User::active()->supervisors()->count(),
        ];

        $recentEnquiries   = Enquiry::with(['activeAssignment.advisor'])->latest()->take(8)->get();
        $pendingAssignment = Enquiry::unassigned()->with('activityLogs')->oldest()->take(5)->get();
        $awaitingReview    = Enquiry::awaitingReview()->with(['activeAssignment.advisor', 'currentResponse.advisor'])->latest()->take(5)->get();
        $recentActivity    = ActivityLog::with('user')->latest('created_at')->take(10)->get();

        return view('portal.dashboard', compact('stats', 'recentEnquiries', 'pendingAssignment', 'awaitingReview', 'recentActivity'));
    }

    private function supervisorDashboard()
    {
        $stats = [
            'awaiting_approval' => Enquiry::where('status', 'awaiting_approval')->count(),
            'total'             => Enquiry::count(),
            'responded'         => Enquiry::where('status', 'responded')->count(),
            'pending_responses' => EnquiryResponse::where('review_status', 'submitted')->count(),
        ];

        $reviewQueue    = Enquiry::awaitingReview()->with(['activeAssignment.advisor', 'currentResponse'])->oldest()->take(10)->get();
        $recentActivity = ActivityLog::with('user')->whereIn('action', ['response.submitted', 'response.approved', 'response.rejected'])->latest('created_at')->take(8)->get();

        return view('portal.dashboard', compact('stats', 'reviewQueue', 'recentActivity'));
    }

    private function advisorDashboard()
    {
        $user = auth()->user();

        $myEnquiries = Enquiry::forAdvisor($user->id)
            ->with(['currentResponse'])
            ->withCount(['responses'])
            ->orderByRaw("FIELD(status,'in_progress','awaiting_approval','under_review','responded','closed')")
            ->get();

        $stats = [
            'assigned'          => $myEnquiries->count(),
            'in_progress'       => $myEnquiries->whereIn('status', ['under_review', 'in_progress'])->count(),
            'awaiting_approval' => $myEnquiries->where('status', 'awaiting_approval')->count(),
            'responded'         => $myEnquiries->where('status', 'responded')->count(),
        ];

        $urgent  = $myEnquiries->where('urgency', 'urgent')->whereNotIn('status', ['responded', 'closed']);
        $recentActivity = ActivityLog::with('user')
            ->whereHas('subject', fn($q) => $q->whereIn('id', $myEnquiries->pluck('id')))
            ->latest('created_at')->take(6)->get();

        return view('portal.dashboard', compact('stats', 'myEnquiries', 'urgent', 'recentActivity'));
    }
}
