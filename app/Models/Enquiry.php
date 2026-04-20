<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Enquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_code',
        'full_name',
        'email',
        'phone',
        'is_anonymous',
        'matter_category',
        'description',
        'urgency',
        'attachment_path',
        'attachment_name',
        'status',
        'internal_notes',
        'response',
        'responded_at',
        'ip_address',
    ];

    protected $casts = [
        'is_anonymous'  => 'boolean',
        'responded_at'  => 'datetime',
    ];

    // -----------------------------------------------------------------------
    // Constants
    // -----------------------------------------------------------------------

    public const MATTER_CATEGORIES = [
        'police_rights'       => 'Police & Your Rights',
        'student_rights'      => 'Student Rights',
        'sexual_harassment'   => 'Sexual Harassment',
        'employment'          => 'Employment / Labour',
        'land_property'       => 'Land & Property',
        'family_law'          => 'Family Law',
        'criminal_law'        => 'Criminal Law',
        'tenancy'             => 'Tenancy / Housing',
        'consumer_rights'     => 'Consumer Rights',
        'general'             => 'General Legal Enquiry',
    ];

    public const STATUSES = [
        'received'          => 'Received',
        'under_review'      => 'Under Review',
        'in_progress'       => 'In Progress',
        'awaiting_approval' => 'Awaiting Approval',
        'responded'         => 'Responded',
        'closed'            => 'Closed',
    ];

    public const STATUS_COLORS = [
        'received'          => 'blue',
        'under_review'      => 'yellow',
        'in_progress'       => 'indigo',
        'awaiting_approval' => 'orange',
        'responded'         => 'green',
        'closed'            => 'gray',
    ];

    // -----------------------------------------------------------------------
    // Accessors
    // -----------------------------------------------------------------------

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst($this->status);
    }

    public function getCategoryLabelAttribute(): string
    {
        return self::MATTER_CATEGORIES[$this->matter_category] ?? ucfirst($this->matter_category);
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->is_anonymous ? 'Anonymous' : ($this->full_name ?? 'Unknown');
    }

    // -----------------------------------------------------------------------
    // Scopes
    // -----------------------------------------------------------------------

    public function scopePublished($query)
    {
        return $query->whereNotIn('status', ['closed']);
    }

    public function scopeUrgent($query)
    {
        return $query->where('urgency', 'urgent');
    }

    // -----------------------------------------------------------------------
    // Helpers
    // -----------------------------------------------------------------------

    /**
     * Generate a unique reference code in the format ELC-YYYY-NNNNN
     */
    public static function generateReferenceCode(): string
    {
        $year = now()->year;

        do {
            $number   = str_pad(random_int(1, 99999), 5, '0', STR_PAD_LEFT);
            $code     = "ELC-{$year}-{$number}";
            $exists   = static::where('reference_code', $code)->exists();
        } while ($exists);

        return $code;
    }

    /**
     * Check if the enquiry can still receive a response (not closed)
     */
    public function isOpen(): bool
    {
        return !in_array($this->status, ['responded', 'closed']);
    }

    // -----------------------------------------------------------------------
    // Phase 2 Relationships
    // -----------------------------------------------------------------------

    public function assignments()
    {
        return $this->hasMany(EnquiryAssignment::class);
    }

    public function activeAssignment()
    {
        return $this->hasOne(EnquiryAssignment::class)->where('is_active', true)->with('advisor');
    }

    public function responses()
    {
        return $this->hasMany(EnquiryResponse::class)->orderByDesc('version');
    }

    public function currentResponse()
    {
        return $this->hasOne(EnquiryResponse::class)->where('is_current', true)->with(['advisor', 'reviewer']);
    }

    public function activityLogs()
    {
        return $this->morphMany(ActivityLog::class, 'subject')->orderByDesc('created_at');
    }

    // -----------------------------------------------------------------------
    // Phase 2 Helpers
    // -----------------------------------------------------------------------

    public function getAssignedAdvisorAttribute(): ?User
    {
        return $this->activeAssignment?->advisor;
    }

    public function isAssigned(): bool
    {
        return $this->assignments()->where('is_active', true)->exists();
    }

    public function getStatusColorClassAttribute(): string
    {
        return match($this->status) {
            'received'          => 'bg-blue-100 text-blue-700',
            'under_review'      => 'bg-yellow-100 text-yellow-700',
            'in_progress'       => 'bg-indigo-100 text-indigo-700',
            'awaiting_approval' => 'bg-orange-100 text-orange-700',
            'responded'         => 'bg-green-100 text-green-700',
            'closed'            => 'bg-gray-100 text-gray-600',
            default             => 'bg-gray-100 text-gray-600',
        };
    }

    // Portal-level scopes
    public function scopeUnassigned($q)       { return $q->where('status', 'received')->whereDoesntHave('assignments', fn($a) => $a->where('is_active', true)); }
    public function scopeAwaitingReview($q)   { return $q->where('status', 'awaiting_approval'); }
    public function scopeForAdvisor($q, $id)  { return $q->whereHas('assignments', fn($a) => $a->where('advisor_id', $id)->where('is_active', true)); }
}
