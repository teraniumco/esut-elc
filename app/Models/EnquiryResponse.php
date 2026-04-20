<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnquiryResponse extends Model
{
    protected $fillable = [
        'enquiry_id', 'advisor_id', 'content', 'internal_notes',
        'review_status', 'reviewed_by', 'review_notes',
        'submitted_at', 'reviewed_at', 'sent_at',
        'is_current', 'version',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'reviewed_at'  => 'datetime',
        'sent_at'      => 'datetime',
        'is_current'   => 'boolean',
    ];

    const STATUS_DRAFT     = 'draft';
    const STATUS_SUBMITTED = 'submitted';
    const STATUS_APPROVED  = 'approved';
    const STATUS_REJECTED  = 'rejected';

    const STATUS_LABELS = [
        'draft'     => 'Draft',
        'submitted' => 'Awaiting Review',
        'approved'  => 'Approved',
        'rejected'  => 'Returned for Revision',
    ];

    const STATUS_COLORS = [
        'draft'     => 'gray',
        'submitted' => 'amber',
        'approved'  => 'green',
        'rejected'  => 'red',
    ];

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->review_status] ?? ucfirst($this->review_status);
    }

    public function getStatusColorAttribute(): string
    {
        return self::STATUS_COLORS[$this->review_status] ?? 'gray';
    }

    // ── Relationships ──────────────────────────────────────────────────────────
    public function enquiry()    { return $this->belongsTo(Enquiry::class); }
    public function advisor()    { return $this->belongsTo(User::class, 'advisor_id'); }
    public function reviewer()   { return $this->belongsTo(User::class, 'reviewed_by'); }

    // ── Scopes ─────────────────────────────────────────────────────────────────
    public function scopeCurrent($q)   { return $q->where('is_current', true); }
    public function scopeSubmitted($q) { return $q->where('review_status', self::STATUS_SUBMITTED); }
    public function scopeApproved($q)  { return $q->where('review_status', self::STATUS_APPROVED); }
}
