<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'description', 'location',
        'event_date', 'event_end_date', 'requires_registration',
        'max_attendees', 'is_published',
    ];

    protected $casts = [
        'event_date'           => 'datetime',
        'event_end_date'       => 'datetime',
        'requires_registration'=> 'boolean',
        'is_published'         => 'boolean',
    ];

    public function registrations(): HasMany
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('event_date', '>=', now())->orderBy('event_date');
    }

    public function scopePast($query)
    {
        return $query->where('event_date', '<', now())->orderByDesc('event_date');
    }

    public function isFull(): bool
    {
        if (!$this->max_attendees) return false;
        return $this->registrations()->count() >= $this->max_attendees;
    }

    public function spotsLeft(): ?int
    {
        if (!$this->max_attendees) return null;
        return max(0, $this->max_attendees - $this->registrations()->count());
    }
}
