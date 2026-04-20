<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FaqArticle extends Model
{
    use HasFactory;

    protected $fillable = [
        'faq_category_id',
        'title',
        'slug',
        'content',
        'excerpt',
        'is_published',
        'helpful_yes',
        'helpful_no',
        'views',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(FaqCategory::class, 'faq_category_id');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function getHelpfulPercentageAttribute(): int
    {
        $total = $this->helpful_yes + $this->helpful_no;
        if ($total === 0) return 0;
        return (int) round(($this->helpful_yes / $total) * 100);
    }

    public function incrementViews(): void
    {
        $this->increment('views');
    }
}
