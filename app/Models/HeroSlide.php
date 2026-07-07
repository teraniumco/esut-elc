<?php

namespace App\Models;

use App\Models\Concerns\HasFlexibleImage;
use Illuminate\Database\Eloquent\Model;

class HeroSlide extends Model
{
    use HasFlexibleImage;

    protected $table = 'home_hero_slides';

    protected $fillable = [
        'heading', 'subtitle',
        'primary_cta_label', 'primary_cta_url',
        'secondary_cta_label', 'secondary_cta_url',
        'image_path', 'sort_order', 'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function scopeActive($q)
    {
        return $q->where('is_active', true)->orderBy('sort_order');
    }
}
