<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    public $timestamps = false;

    protected $fillable = ['user_id', 'action', 'subject_type', 'subject_id', 'payload', 'ip_address', 'created_at'];

    protected $casts = [
        'payload'    => 'array',
        'created_at' => 'datetime',
    ];

    public function user()    { return $this->belongsTo(User::class); }
    public function subject() { return $this->morphTo(); }

    /**
     * Convenience static method to record an action.
     */
    public static function record(string $action, $subject = null, array $payload = [], ?User $user = null): static
    {
        return static::create([
            'user_id'      => ($user ?? auth()->user())?->id,
            'action'       => $action,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id'   => $subject?->id,
            'payload'      => $payload ?: null,
            'ip_address'   => request()->ip(),
            'created_at'   => now(),
        ]);
    }

    // ── Action label helper ────────────────────────────────────────────────────
    const ACTION_LABELS = [
        'enquiry.received'          => 'Enquiry received',
        'enquiry.assigned'          => 'Enquiry assigned to advisor',
        'enquiry.reassigned'        => 'Enquiry reassigned',
        'response.draft_saved'      => 'Draft saved',
        'response.submitted'        => 'Response submitted for review',
        'response.approved'         => 'Response approved & sent',
        'response.rejected'         => 'Response returned for revision',
        'user.invited'              => 'User invited',
        'user.invite_accepted'      => 'Invite accepted',
        'user.deactivated'          => 'User deactivated',
        'user.reactivated'          => 'User reactivated',
        'enquiry.status_changed'    => 'Enquiry status changed',
    ];

    public function getActionLabelAttribute(): string
    {
        return self::ACTION_LABELS[$this->action] ?? $this->action;
    }

    public function scopeForSubject($q, $subject)
    {
        return $q->where('subject_type', get_class($subject))->where('subject_id', $subject->id);
    }
}
