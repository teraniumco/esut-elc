<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TeamMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'role', 'level', 'photo_path', 'bio',
        'email', 'type', 'sort_order', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    public function scopeLecturers($query)
    {
        return $query->where('type', 'lecturer');
    }

    public function scopeStudents($query)
    {
        return $query->where('type', 'student');
    }

    public function getPhotoUrlAttribute(): string
    {
        return $this->photo_path
            ? asset('storage/' . $this->photo_path)
            : asset('assets/img/no-user-image.png');
    }
}
