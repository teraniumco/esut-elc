@extends('portal.layout')
@section('title', $enquiry->reference_code)
@section('page-title', $enquiry->reference_code)
@section('page-subtitle', $enquiry->category_label . ' · ' . $enquiry->status_label)

@push('styles')
<style>
.timeline-dot{width:8px;height:8px;border-radius:50%;background:var(--border);flex-shrink:0;margin-top:5px}
.timeline-dot.done{background:var(--crimson)}
.timeline-dot.current{background:var(--gold);box-shadow:0 0 0 3px rgba(201,168,76,0.25)}
</style>
@endpush

@section('content')
@php $user = auth()->user(); @endphp

<div class="flex items-center gap-3 mb-5">
    <a href="{{ route('portal.enquiries.index') }}" class="btn-ghost btn-sm">← Inbox</a>
    <span class="status-badge status-{{ $enquiry->status }} text-xs">{{ $enquiry->status_label }}</span>
    @if($enquiry->urgency === 'urgent') <span class="text-xs px-2.5 py-1 rounded-full bg-red-100 text-red-700 font-semibold">⚡ Urgent</span> @endif
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

    {{-- ═══ LEFT: Details + workflow ══════════════════════════════════ --}}
    <div class="xl:col-span-2 space-y-5">

        {{-- Enquiry details --}}
        <div class="portal-card">
            <h3 class="font-semibold text-sm mb-4" style="color:var(--text)">Enquiry Details</h3>
            <div class="grid grid-cols-2 gap-y-3 gap-x-6 text-sm mb-4">
                @foreach([
                    ['Reference', $enquiry->reference_code, true],
                    ['From', $enquiry->display_name, false],
                    ['Email', $enquiry->email ?? '—', false],
                    ['Phone', $enquiry->phone ?? '—', false],
                    ['Category', $enquiry->category_label, false],
                    ['Urgency', ucfirst($enquiry->urgency), false],
                    ['Submitted', $enquiry->created_at->format('d M Y, H:i'), false],
                    ['Responded', $enquiry->responded_at ? $enquiry->responded_at->format('d M Y, H:i') : 'Not yet', false],
                ] as [$label, $val, $mono])
                <div>
                    <div class="text-xs font-semibold mb-0.5" style="color:var(--text-light)">{{ $label }}</div>
                    <div class="{{ $mono ? 'font-mono font-bold' : '' }} text-sm" style="{{ $mono ? 'color:var(--crimson)' : 'color:var(--text)' }}">{{ $val }}</div>
                </div>
                @endforeach
            </div>
            <div class="border-t pt-4" style="border-color:var(--border)">
                <div class="text-xs font-semibold mb-2" style="color:var(--text-light)">Description</div>
                <div class="text-sm leading-relaxed whitespace-pre-line rounded-xl p-4" style="color:var(--text);background:var(--off-white)">{{ $enquiry->description }}</div>
            </div>
            @if($enquiry->attachment_name)
            <div class="mt-3 flex items-center gap-2 text-xs" style="color:var(--text-mid)">
                <svg class="w-4 h-4" style="color:var(--crimson)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                Attachment: {{ $enquiry->attachment_name }}
            </div>
            @endif
        </div>

        {{-- Assign panel (admin only) --}}
        @can('assign', $enquiry)
        <div class="portal-card" x-data="{ open: {{ $enquiry->status === 'received' ? 'true' : 'false' }} }">
            <button @click="open = !open" class="flex items-center justify-between w-full text-left">
                <h3 class="font-semibold text-sm" style="color:var(--text)">
                    {{ $enquiry->activeAssignment ? '↻ Reassign Enquiry' : '+ Assign to Advisor' }}
                </h3>
                <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform" style="color:var(--text-light)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            @if($enquiry->activeAssignment)
            <div class="mt-3 flex items-center gap-2 text-sm" style="color:var(--text-mid)">
                <img src="{{ $enquiry->activeAssignment->advisor->avatar_url }}" class="w-6 h-6 rounded-full">
                Currently: <strong style="color:var(--text)">{{ $enquiry->activeAssignment->advisor->name }}</strong>
                <span class="text-xs" style="color:var(--text-light)">since {{ $enquiry->activeAssignment->assigned_at->format('d M') }}</span>
            </div>
            @endif
            <div x-show="open" x-transition class="mt-4">
                <form method="POST" action="{{ route('portal.enquiries.assign', $enquiry) }}">
                    @csrf
                    <div class="space-y-3">
                        <div>
                            <label class="form-label">Assign to Advisor</label>
                            <select name="advisor_id" class="form-input" required>
                                <option value="">— Select advisor —</option>
                                @foreach($advisors as $advisor)
                                <option value="{{ $advisor->id }}" {{ optional($enquiry->activeAssignment)->advisor_id == $advisor->id ? 'selected' : '' }}>
                                    {{ $advisor->name }}
                                    ({{ $advisor->active_assignments_count ?? $advisor->activeAssignments()->count() }} active)
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Assignment Notes <span class="font-normal" style="color:var(--text-light)">(optional)</span></label>
                            <textarea name="assignment_notes" class="form-input" rows="2" placeholder="Context or instructions for the advisor…"></textarea>
                        </div>
                        <button type="submit" class="btn-crimson btn-sm">Assign</button>
                    </div>
                </form>
            </div>
        </div>
        @endcan

        {{-- Response draft (advisor + supervisor) --}}
        @can('respond', $enquiry)
        <div class="portal-card">
            <h3 class="font-semibold text-sm mb-4" style="color:var(--text)">
                @if($currentResponse)
                    @if($currentResponse->review_status === 'rejected') ↩ Revise Response @else 📝 Current Response Draft @endif
                @else 📝 Draft Response @endif
            </h3>

            {{-- Show rejection notes if rejected --}}
            @if($currentResponse?->review_status === 'rejected')
            <div class="mb-4 p-4 rounded-xl border" style="background:#fff8e1;border-color:#fde68a">
                <div class="text-xs font-bold uppercase tracking-wider mb-1" style="color:#92400e">Supervisor Feedback</div>
                <div class="text-sm leading-relaxed" style="color:#78350f">{{ $currentResponse->review_notes }}</div>
                <div class="text-xs mt-2" style="color:#92400e">Reviewed by {{ $currentResponse->reviewer->name }} · {{ $currentResponse->reviewed_at->format('d M Y H:i') }}</div>
            </div>
            @endif

            <form method="POST" action="{{ route('portal.responses.draft', $enquiry) }}" x-data="{ submitting: false }">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="form-label">Legal Response</label>
                        <textarea name="content" class="form-input" rows="12" placeholder="Draft your legal response here. Be thorough, clear, and reference relevant Nigerian law where applicable…" required>{{ old('content', $currentResponse?->content) }}</textarea>
                        <div class="form-hint">Minimum 50 characters. Reference relevant statutes, case law, or constitutional provisions where applicable.</div>
                    </div>
                    <div>
                        <label class="form-label">Internal Notes <span class="font-normal" style="color:var(--text-light)">(not sent to requester)</span></label>
                        <textarea name="internal_notes" class="form-input" rows="3" placeholder="Notes for the supervisor, research citations, doubts…">{{ old('internal_notes', $currentResponse?->internal_notes) }}</textarea>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <button type="submit" class="btn-ghost btn-sm">💾 Save Draft</button>
                        @if($currentResponse && in_array($currentResponse->review_status, ['draft','rejected']))
                        <button type="button" class="btn-crimson btn-sm"
                            onclick="document.getElementById('submit-form').submit()">
                            ✓ Submit for Review
                        </button>
                        @endif
                    </div>
                </div>
            </form>

            {{-- Hidden submit form --}}
            @if($currentResponse && in_array($currentResponse->review_status, ['draft','rejected']))
            <form id="submit-form" method="POST" action="{{ route('portal.responses.submit', $enquiry) }}" class="hidden">@csrf</form>
            @endif
        </div>
        @endcan

        {{-- Approve / Reject panel (supervisor / admin) --}}
        @can('review', $enquiry)
        @if($currentResponse && $currentResponse->review_status === 'submitted')
        <div class="portal-card border-2" style="border-color:var(--gold)">
            <h3 class="font-semibold text-sm mb-3" style="color:var(--text)">⚖ Review Response</h3>
            <div class="p-4 rounded-xl mb-4 text-sm leading-relaxed whitespace-pre-line" style="background:var(--off-white);color:var(--text)">{{ $currentResponse->content }}</div>
            <div class="text-xs mb-4" style="color:var(--text-light)">
                Drafted by <strong>{{ $currentResponse->advisor->name }}</strong> · submitted {{ $currentResponse->submitted_at->diffForHumans() }}
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Approve --}}
                <form method="POST" action="{{ route('portal.responses.approve', $enquiry) }}">
                    @csrf
                    <button type="submit" class="btn-gold w-full justify-center py-3" onclick="return confirm('Approve and send this response to the requester?')">
                        ✓ Approve & Send to Requester
                    </button>
                </form>

                {{-- Reject --}}
                <div x-data="{ open: false }">
                    <button @click="open = !open" class="btn-danger w-full justify-center py-3">↩ Return for Revision</button>
                    <div x-show="open" x-transition class="mt-3">
                        <form method="POST" action="{{ route('portal.responses.reject', $enquiry) }}">
                            @csrf
                            <textarea name="review_notes" class="form-input mb-2" rows="4" placeholder="Explain clearly what needs to be changed or improved…" required></textarea>
                            <button type="submit" class="btn-danger btn-sm w-full justify-center">Send Feedback to Advisor</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @endcan

        {{-- Internal notes --}}
        @can('review', $enquiry)
        <div class="portal-card" x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center justify-between w-full text-left">
                <h3 class="font-semibold text-sm" style="color:var(--text)">Internal Notes</h3>
                <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform" style="color:var(--text-light)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div x-show="open" x-transition class="mt-4 space-y-3">
                @if($enquiry->internal_notes)
                <div class="p-3 rounded-lg text-xs whitespace-pre-line" style="background:var(--off-white);color:var(--text-mid)">{{ $enquiry->internal_notes }}</div>
                @endif
                <form method="POST" action="{{ route('portal.enquiries.note', $enquiry) }}">
                    @csrf
                    <textarea name="note" class="form-input mb-2" rows="3" placeholder="Add a note…"></textarea>
                    <button type="submit" class="btn-ghost btn-sm">Add Note</button>
                </form>
            </div>
        </div>
        @endcan

        {{-- Response history --}}
        @if($responseHistory->isNotEmpty())
        <div class="portal-card">
            <h3 class="font-semibold text-sm mb-4" style="color:var(--text)">Response History</h3>
            <div class="space-y-3">
                @foreach($responseHistory as $resp)
                <div class="p-3 rounded-xl border text-xs" style="border-color:var(--border)">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="font-semibold">v{{ $resp->version }}</span>
                        <span class="status-badge" style="background:#f9fafb;color:#6b7280">{{ $resp->status_label }}</span>
                        <span style="color:var(--text-light)">by {{ $resp->advisor->name }} · {{ $resp->created_at->format('d M Y') }}</span>
                    </div>
                    <p class="leading-relaxed line-clamp-3" style="color:var(--text-mid)">{{ Str::limit($resp->content, 200) }}</p>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    {{-- ═══ RIGHT: Sidebar info ══════════════════════════════════════ --}}
    <div class="space-y-5">

        {{-- Status + admin override --}}
        @can('assign', $enquiry)
        <div class="portal-card">
            <h3 class="font-semibold text-sm mb-3" style="color:var(--text)">Status Override</h3>
            <form method="POST" action="{{ route('portal.enquiries.status', $enquiry) }}" class="flex gap-2">
                @csrf
                <select name="status" class="form-input flex-1 text-xs">
                    @foreach(\App\Models\Enquiry::STATUSES as $k => $v)
                    <option value="{{ $k }}" {{ $enquiry->status === $k ? 'selected' : '' }}>{{ $v }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn-ghost btn-sm">Update</button>
            </form>
        </div>
        @endcan

        {{-- Assignment info --}}
        <div class="portal-card">
            <h3 class="font-semibold text-sm mb-3" style="color:var(--text)">Assignment</h3>
            @if($enquiry->activeAssignment)
            <div class="flex items-center gap-3">
                <img src="{{ $enquiry->activeAssignment->advisor->avatar_url }}" class="w-10 h-10 rounded-full">
                <div>
                    <div class="text-sm font-semibold" style="color:var(--text)">{{ $enquiry->activeAssignment->advisor->name }}</div>
                    <div class="text-xs" style="color:var(--text-light)">{{ $enquiry->activeAssignment->advisor->role_label }}</div>
                    <div class="text-xs" style="color:var(--text-light)">Assigned {{ $enquiry->activeAssignment->assigned_at->format('d M Y') }}</div>
                </div>
            </div>
            @if($enquiry->activeAssignment->assignment_notes)
            <div class="mt-3 p-3 rounded-lg text-xs leading-relaxed" style="background:var(--off-white);color:var(--text-mid)">{{ $enquiry->activeAssignment->assignment_notes }}</div>
            @endif
            @else
            <p class="text-xs" style="color:var(--text-light)">Not yet assigned.</p>
            @endif
        </div>

        {{-- Current response status --}}
        @if($currentResponse)
        <div class="portal-card">
            <h3 class="font-semibold text-sm mb-3" style="color:var(--text)">Response Status</h3>
            <div class="space-y-2 text-xs">
                <div class="flex items-center justify-between">
                    <span style="color:var(--text-mid)">Status</span>
                    <span class="status-badge" style="background:{{ $currentResponse->review_status === 'approved' ? '#f0fdf4' : ($currentResponse->review_status === 'rejected' ? '#fef2f2' : '#fff7ed') }};color:{{ $currentResponse->review_status === 'approved' ? '#15803d' : ($currentResponse->review_status === 'rejected' ? '#dc2626' : '#c2410c') }}">
                        {{ $currentResponse->status_label }}
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <span style="color:var(--text-mid)">Version</span>
                    <span style="color:var(--text)">v{{ $currentResponse->version }}</span>
                </div>
                @if($currentResponse->submitted_at)
                <div class="flex items-center justify-between">
                    <span style="color:var(--text-mid)">Submitted</span>
                    <span style="color:var(--text)">{{ $currentResponse->submitted_at->format('d M H:i') }}</span>
                </div>
                @endif
                @if($currentResponse->reviewer)
                <div class="flex items-center justify-between">
                    <span style="color:var(--text-mid)">Reviewer</span>
                    <span style="color:var(--text)">{{ $currentResponse->reviewer->name }}</span>
                </div>
                @endif
            </div>
        </div>
        @endif

        {{-- Activity log --}}
        <div class="portal-card">
            <h3 class="font-semibold text-sm mb-3" style="color:var(--text)">Activity Log</h3>
            <div class="space-y-3 max-h-72 overflow-y-auto">
                @forelse($enquiry->activityLogs as $log)
                <div class="flex gap-2.5">
                    <div class="timeline-dot {{ in_array($log->action, ['response.approved','enquiry.assigned']) ? 'done' : (in_array($log->action, ['response.submitted']) ? 'current' : '') }} mt-1.5"></div>
                    <div>
                        <div class="text-xs font-medium" style="color:var(--text)">{{ $log->action_label }}</div>
                        @if($log->user)<div class="text-xs" style="color:var(--text-light)">{{ $log->user->name }}</div>@endif
                        <div class="text-xs" style="color:var(--text-light)">{{ $log->created_at->diffForHumans() }}</div>
                    </div>
                </div>
                @empty
                <p class="text-xs" style="color:var(--text-light)">No activity recorded yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
