<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EventRegistration extends Model
{
    use HasFactory;

    protected $fillable = ['event_id', 'name', 'email', 'phone', 'affiliation'];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
