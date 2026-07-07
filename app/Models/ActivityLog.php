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
        'content.hero_slide_created'    => 'Hero slide added',
        'content.hero_slide_updated'    => 'Hero slide updated',
        'content.hero_slide_deleted'    => 'Hero slide deleted',
        'content.gallery_item_created'  => 'Gallery photo added',
        'content.gallery_item_updated'  => 'Gallery photo updated',
        'content.gallery_item_deleted'  => 'Gallery photo deleted',
        'content.step_created'          => 'How It Works step added',
        'content.step_updated'          => 'How It Works step updated',
        'content.step_deleted'          => 'How It Works step deleted',
        'content.marquee_item_created'  => 'Marquee text added',
        'content.marquee_item_updated'  => 'Marquee text updated',
        'content.marquee_item_deleted'  => 'Marquee text deleted',
        'content.stats_updated'         => 'Homepage stats updated',
        'faq.category_created'  => 'FAQ category created',
        'faq.category_updated'  => 'FAQ category updated',
        'faq.category_deleted'  => 'FAQ category deleted',
        'faq.article_created'   => 'FAQ article created',
        'faq.article_updated'   => 'FAQ article updated',
        'faq.article_deleted'   => 'FAQ article deleted',
        'event.created'         => 'Event created',
        'event.updated'         => 'Event updated',
        'event.deleted'         => 'Event deleted',
        'event.toggled'         => 'Event publish toggled',
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
