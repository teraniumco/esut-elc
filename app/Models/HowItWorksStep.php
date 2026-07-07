<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HowItWorksStep extends Model
{
    protected $table = 'home_how_it_works_steps';

    protected $fillable = ['title', 'description', 'icon_key', 'sort_order', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    /**
     * Fixed registry of safe icon paths the admin can choose from
     * (avoids accepting raw SVG markup from a form).
     */
    const ICONS = [
        'document' => [
            'label' => 'Document',
            'path'  => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z',
        ],
        'review' => [
            'label' => 'Review / Magnify',
            'path'  => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
        ],
        'track' => [
            'label' => 'Track / Approve',
            'path'  => 'M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4',
        ],
        'shield' => [
            'label' => 'Shield / Trust',
            'path'  => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
        ],
        'clock' => [
            'label' => 'Clock / Time',
            'path'  => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
        ],
        'chat' => [
            'label' => 'Chat / Message',
            'path'  => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z',
        ],
        'mail' => [
            'label' => 'Mail / Send',
            'path'  => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
        ],
        'scale' => [
            'label' => 'Scale of Justice',
            'path'  => 'M12 3v18m-7-6l4-9 4 9m-8 0a4 4 0 008 0M21 15l-4-9-4 9m8 0a4 4 0 01-8 0',
        ],
        'search' => [
            'label' => 'Search',
            'path'  => 'M21 21l-4.35-4.35M11 18a7 7 0 100-14 7 7 0 000 14z',
        ],
    ];

    public function getIconPathAttribute(): string
    {
        return self::ICONS[$this->icon_key]['path'] ?? self::ICONS['document']['path'];
    }

    public function scopeActive($q)
    {
        return $q->where('is_active', true)->orderBy('sort_order');
    }
}
