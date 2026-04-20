<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'department', 'phone', 'bio',
        'is_active', 'invited_by', 'invite_accepted_at',
        'invite_token', 'invite_token_expires_at', 'last_login_at',
    ];

    protected $hidden = ['password', 'remember_token', 'invite_token'];

    protected $casts = [
        'email_verified_at'       => 'datetime',
        'invite_accepted_at'      => 'datetime',
        'invite_token_expires_at' => 'datetime',
        'last_login_at'           => 'datetime',
        'is_active'               => 'boolean',
    ];

    // ── Role constants ─────────────────────────────────────────────────────────
    const ROLE_ADMIN      = 'admin';
    const ROLE_SUPERVISOR = 'supervisor';
    const ROLE_ADVISOR    = 'advisor';

    const ROLE_LABELS = [
        'admin'      => 'Administrator',
        'supervisor' => 'Faculty Supervisor',
        'advisor'    => 'Student Advisor',
    ];

    // ── Role helpers ───────────────────────────────────────────────────────────
    public function isAdmin(): bool      { return $this->role === self::ROLE_ADMIN; }
    public function isSupervisor(): bool { return $this->role === self::ROLE_SUPERVISOR; }
    public function isAdvisor(): bool    { return $this->role === self::ROLE_ADVISOR; }
    public function canApprove(): bool   { return in_array($this->role, [self::ROLE_ADMIN, self::ROLE_SUPERVISOR]); }

    public function getRoleLabelAttribute(): string
    {
        return self::ROLE_LABELS[$this->role] ?? ucfirst($this->role);
    }

    public function hasAcceptedInvite(): bool
    {
        return $this->invite_accepted_at !== null;
    }

    // ── Invite token ───────────────────────────────────────────────────────────
    public function generateInviteToken(): string
    {
        $token = Str::random(64);
        $this->update([
            'invite_token'             => $token,
            'invite_token_expires_at'  => now()->addDays(7),
        ]);
        return $token;
    }

    public function hasValidInviteToken(string $token): bool
    {
        return $this->invite_token === $token
            && $this->invite_token_expires_at
            && $this->invite_token_expires_at->isFuture();
    }

    public function acceptInvite(string $password): void
    {
        $this->update([
            'password'            => bcrypt($password),
            'invite_accepted_at'  => now(),
            'email_verified_at'   => now(),
            'invite_token'        => null,
            'invite_token_expires_at' => null,
        ]);
    }

    // ── Relationships ──────────────────────────────────────────────────────────
    public function invitedBy()
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    public function invitedUsers()
    {
        return $this->hasMany(User::class, 'invited_by');
    }

    public function assignments()
    {
        return $this->hasMany(EnquiryAssignment::class, 'advisor_id');
    }

    public function activeAssignments()
    {
        return $this->hasMany(EnquiryAssignment::class, 'advisor_id')->where('is_active', true);
    }

    public function responses()
    {
        return $this->hasMany(EnquiryResponse::class, 'advisor_id');
    }

    public function reviewedResponses()
    {
        return $this->hasMany(EnquiryResponse::class, 'reviewed_by');
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    // ── Scopes ─────────────────────────────────────────────────────────────────
    public function scopeActive($q)      { return $q->where('is_active', true); }
    public function scopeAdvisors($q)    { return $q->where('role', self::ROLE_ADVISOR); }
    public function scopeSupervisors($q) { return $q->where('role', self::ROLE_SUPERVISOR); }
    public function scopeAdmins($q)      { return $q->where('role', self::ROLE_ADMIN); }

    // ── Computed ───────────────────────────────────────────────────────────────
    public function getAvatarUrlAttribute(): string
    {
        $initials = collect(explode(' ', $this->name))
            ->map(fn($w) => strtoupper($w[0]))->take(2)->implode('');
        return 'https://ui-avatars.com/api/?name=' . urlencode($initials) . '&background=711500&color=C9A84C&bold=true&size=80';
    }

    public function getPendingEnquiriesCountAttribute(): int
    {
        if ($this->isAdvisor()) {
            return EnquiryAssignment::where('advisor_id', $this->id)
                ->where('is_active', true)
                ->whereHas('enquiry', fn($q) => $q->whereNotIn('status', ['responded', 'closed']))
                ->count();
        }
        return 0;
    }

    public function getPendingReviewsCountAttribute(): int
    {
        if ($this->canApprove()) {
            return EnquiryResponse::where('review_status', 'submitted')->count();
        }
        return 0;
    }
}
