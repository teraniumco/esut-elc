<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Enquiry;
use App\Models\EnquiryAssignment;
use App\Models\EnquiryResponse;
use App\Models\User;
use Illuminate\Http\Request;

class EnquiryController extends Controller
{
    /** Enquiry inbox — filtered by role */
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = Enquiry::with(['activeAssignment.advisor', 'currentResponse'])
            ->latest();

        // Advisors only see their own assigned enquiries
        if ($user->isAdvisor()) {
            $query->forAdvisor($user->id);
        }

        // Filters
        if ($request->filled('status'))   $query->where('status', $request->status);
        if ($request->filled('urgency'))  $query->where('urgency', $request->urgency);
        if ($request->filled('category')) $query->where('matter_category', $request->category);
        if ($request->filled('search'))   $query->where(fn($q) =>
            $q->where('reference_code', 'like', "%{$request->search}%")
              ->orWhere('full_name',      'like', "%{$request->search}%")
              ->orWhere('description',    'like', "%{$request->search}%")
        );

        $enquiries = $query->paginate(15)->withQueryString();

        $advisors   = User::active()->advisors()->orderBy('name')->get();
        $statusCounts = Enquiry::selectRaw('status, COUNT(*) as count')
            ->when($user->isAdvisor(), fn($q) => $q->forAdvisor($user->id))
            ->groupBy('status')->pluck('count', 'status');

        return view('portal.enquiries.index', compact('enquiries', 'advisors', 'statusCounts'));
    }

    /** Show single enquiry */
    public function show(Enquiry $enquiry)
    {
        $this->authorize('view', $enquiry);

        $enquiry->load(['activeAssignment.advisor', 'assignments.advisor', 'responses.advisor', 'responses.reviewer', 'activityLogs.user']);

        $advisors       = User::active()->advisors()->orderBy('name')->get();
        $currentResponse = $enquiry->responses->where('is_current', true)->first();
        $responseHistory = $enquiry->responses->where('is_current', false);

        return view('portal.enquiries.show', compact('enquiry', 'advisors', 'currentResponse', 'responseHistory'));
    }

    /** Assign enquiry to advisor (Admin only) */
    public function assign(Request $request, Enquiry $enquiry)
    {
        $this->authorize('assign', $enquiry);

        $request->validate([
            'advisor_id'       => ['required', 'exists:users,id'],
            'assignment_notes' => ['nullable', 'string', 'max:500'],
        ]);

        $advisor = User::findOrFail($request->advisor_id);

        // Deactivate previous assignment
        $enquiry->assignments()->where('is_active', true)->update(['is_active' => false]);

        // Create new assignment
        EnquiryAssignment::create([
            'enquiry_id'       => $enquiry->id,
            'advisor_id'       => $advisor->id,
            'assigned_by'      => auth()->id(),
            'assignment_notes' => $request->assignment_notes,
            'assigned_at'      => now(),
            'is_active'        => true,
        ]);

        // Update enquiry status
        $wasReceived = $enquiry->status === 'received';
        $enquiry->update(['status' => 'under_review']);

        ActivityLog::record(
            $wasReceived ? 'enquiry.assigned' : 'enquiry.reassigned',
            $enquiry,
            ['advisor' => $advisor->name, 'notes' => $request->assignment_notes]
        );

        // Notify advisor by email
        try {
            \Mail::to($advisor->email)->queue(new \App\Mail\Portal\EnquiryAssignedMail($enquiry, $advisor));
        } catch (\Throwable $e) {
            // Non-fatal — log and continue
            logger()->error('Assignment mail failed', ['error' => $e->getMessage()]);
        }

        return back()->with('success', "Enquiry assigned to {$advisor->name}.");
    }

    /** Update enquiry status (Admin only) */
    public function updateStatus(Request $request, Enquiry $enquiry)
    {
        $this->authorize('assign', $enquiry);

        $request->validate([
            'status' => ['required', 'in:' . implode(',', array_keys(Enquiry::STATUSES))],
        ]);

        $old = $enquiry->status;
        $enquiry->update(['status' => $request->status]);

        ActivityLog::record('enquiry.status_changed', $enquiry, ['from' => $old, 'to' => $request->status]);

        return back()->with('success', 'Enquiry status updated.');
    }

    /** Add internal note (Admin/Supervisor) */
    public function addNote(Request $request, Enquiry $enquiry)
    {
        $this->authorize('review', $enquiry);

        $request->validate(['note' => ['required', 'string', 'max:1000']]);

        $existing = $enquiry->internal_notes ? $enquiry->internal_notes . "\n\n" : '';
        $note     = "[" . auth()->user()->name . " — " . now()->format('d M Y H:i') . "]\n" . $request->note;
        $enquiry->update(['internal_notes' => $existing . $note]);

        ActivityLog::record('enquiry.note_added', $enquiry);

        return back()->with('success', 'Note added.');
    }
}
