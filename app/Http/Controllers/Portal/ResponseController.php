<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Enquiry;
use App\Models\EnquiryResponse;
use Illuminate\Http\Request;

class ResponseController extends Controller
{
    /** Save or update advisor draft */
    public function saveDraft(Request $request, Enquiry $enquiry)
    {
        $this->authorize('respond', $enquiry);

        $request->validate([
            'content'        => ['required', 'string', 'min:50'],
            'internal_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $existing = $enquiry->responses()->where('is_current', true)->first();

        if ($existing && $existing->review_status === EnquiryResponse::STATUS_DRAFT) {
            // Update existing draft — preserve the original advisor_id
            $existing->update([
                'content'        => $request->content,
                'internal_notes' => $request->internal_notes,
            ]);
            $response = $existing;
        } else {
            // Archive any existing and create new
            $enquiry->responses()->update(['is_current' => false]);
            $version = ($enquiry->responses()->max('version') ?? 0) + 1;

            // Use the assigned advisor's ID if the current user is admin/supervisor
            $advisorId = auth()->id();
            if (!auth()->user()->isAdvisor()) {
                $assigned = $enquiry->assignments()->where('is_active', true)->latest()->first();
                if ($assigned) {
                    $advisorId = $assigned->advisor_id;
                }
            }

            $response = EnquiryResponse::create([
                'enquiry_id'     => $enquiry->id,
                'advisor_id'     => $advisorId,
                'content'        => $request->content,
                'internal_notes' => $request->internal_notes,
                'review_status'  => EnquiryResponse::STATUS_DRAFT,
                'is_current'     => true,
                'version'        => $version,
            ]);
        }

        $enquiry->update(['status' => 'in_progress']);
        ActivityLog::record('response.draft_saved', $enquiry);

        return back()->with('success', 'Draft saved.');
    }

    /** Advisor submits draft for supervisor review */
    public function submit(Request $request, Enquiry $enquiry)
    {
        $this->authorize('respond', $enquiry);

        // Admins and supervisors can submit any current draft, not just their own
        $query = $enquiry->responses()
            ->where('is_current', true)
            ->whereIn('review_status', [EnquiryResponse::STATUS_DRAFT, EnquiryResponse::STATUS_REJECTED]);

        if (auth()->user()->isAdvisor()) {
            $query->where('advisor_id', auth()->id());
        }

        $response = $query->firstOrFail();

        $response->update([
            'review_status' => EnquiryResponse::STATUS_SUBMITTED,
            'submitted_at'  => now(),
            'review_notes'  => null,
        ]);

        $enquiry->update(['status' => 'awaiting_approval']);
        ActivityLog::record('response.submitted', $enquiry);

        return back()->with('success', 'Response submitted for supervisor review.');
    }

    /** Supervisor approves — sends to requester */
    public function approve(Request $request, Enquiry $enquiry)
    {
        $this->authorize('review', $enquiry);

        $response = $enquiry->currentResponse()->firstOrFail();

        if ($response->review_status !== EnquiryResponse::STATUS_SUBMITTED) {
            return back()->with('error', 'This response is not pending review.');
        }

        $response->update([
            'review_status' => EnquiryResponse::STATUS_APPROVED,
            'reviewed_by'   => auth()->id(),
            'reviewed_at'   => now(),
            'sent_at'       => now(),
        ]);

        $enquiry->update([
            'status'       => 'responded',
            'response'     => $response->content,
            'responded_at' => now(),
        ]);

        ActivityLog::record('response.approved', $enquiry, ['reviewer' => auth()->user()->name]);

        // Send response to requester
        if ($enquiry->email) {
            try {
                \Mail::to($enquiry->email)->send(new \App\Mail\Portal\EnquiryResponseMail($enquiry, $response));
            } catch (\Throwable $e) {
                logger()->error('Response dispatch mail failed', ['error' => $e->getMessage()]);
            }
        }

        return back()->with('success', 'Response approved and dispatched to the requester.');
    }

    /** Supervisor rejects — returns to advisor with notes */
    public function reject(Request $request, Enquiry $enquiry)
    {
        $this->authorize('review', $enquiry);

        $request->validate([
            'review_notes' => ['required', 'string', 'min:10', 'max:1000'],
        ], [
            'review_notes.required' => 'Please provide feedback for the advisor.',
            'review_notes.min'      => 'Feedback must be at least 10 characters.',
        ]);

        $response = $enquiry->currentResponse()->firstOrFail();

        if ($response->review_status !== EnquiryResponse::STATUS_SUBMITTED) {
            return back()->with('error', 'This response is not pending review.');
        }

        $response->update([
            'review_status' => EnquiryResponse::STATUS_REJECTED,
            'reviewed_by'   => auth()->id(),
            'reviewed_at'   => now(),
            'review_notes'  => $request->review_notes,
        ]);

        $enquiry->update(['status' => 'in_progress']);
        ActivityLog::record('response.rejected', $enquiry, ['notes' => $request->review_notes]);

        // Notify advisor of rejection
        try {
            $advisor = $response->advisor;
            \Mail::to($advisor->email)->send(new \App\Mail\Portal\ResponseRejectedMail($enquiry, $response, $advisor));
        } catch (\Throwable $e) {
            logger()->error('Rejection mail failed', ['error' => $e->getMessage()]);
        }

        return back()->with('success', 'Response returned to advisor with your feedback.');
    }
}