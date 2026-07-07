<?php

namespace App\Models\Concerns;

use Illuminate\Support\Facades\Storage;

/**
 * Shared accessor for models that store an image as either:
 *  - an uploaded file path (relative to the 'public' disk)
 *  - a pasted absolute URL (http:// or https://)
 *  - a pasted relative asset path (e.g. assets/img/...)
 */
trait HasFlexibleImage
{
    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image_path) {
            return null;
        }

        // Full URL pasted by admin
        if (preg_match('#^https?://#i', $this->image_path)) {
            return $this->image_path;
        }

        // Relative asset path pasted by admin (e.g. "assets/img/hero/x.jpg")
        if (str_starts_with($this->image_path, 'assets/') || str_starts_with($this->image_path, 'storage/')) {
            return asset($this->image_path);
        }

        // Otherwise assume it was uploaded to the 'public' disk
        return Storage::disk('public')->url($this->image_path);
    }
}
