<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnquiryAssignment extends Model
{
    protected $fillable = [
        'enquiry_id', 'advisor_id', 'assigned_by',
        'assignment_notes', 'assigned_at', 'is_active',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'is_active'   => 'boolean',
    ];

    public function enquiry()
    {
        return $this->belongsTo(Enquiry::class);
    }

    public function advisor()
    {
        return $this->belongsTo(User::class, 'advisor_id');
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }
}
