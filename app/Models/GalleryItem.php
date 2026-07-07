<?php

namespace App\Models;

use App\Models\Concerns\HasFlexibleImage;
use Illuminate\Database\Eloquent\Model;

class GalleryItem extends Model
{
    use HasFlexibleImage;

    protected $table = 'home_gallery_items';

    protected $fillable = ['image_path', 'caption', 'height', 'sort_order', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function scopeActive($q)
    {
        return $q->where('is_active', true)->orderBy('sort_order');
    }
}
