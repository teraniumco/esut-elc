<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class HomeStat extends Model
{
    protected $table = 'home_stats';

    protected $fillable = ['stat_key', 'label', 'suffix', 'manual_value', 'is_auto', 'sort_order'];

    protected $casts = ['is_auto' => 'boolean'];

    public function scopeOrdered($q)
    {
        return $q->orderBy('sort_order');
    }

    /**
     * The value actually rendered on the homepage: either the live
     * computed figure (is_auto = true) or the admin's manual override.
     */
    public function getDisplayValueAttribute(): string
    {
        if (!$this->is_auto) {
            return $this->manual_value ?: '0';
        }

        return (string) $this->computeAutoValue();
    }

    /**
     * Live computation per known stat key. Unknown keys fall back to
     * the manual value (or 0) since there's no auto source for them.
     */
    protected function computeAutoValue(): string
    {
        return match ($this->stat_key) {
            'cases_handled' => number_format(
                Enquiry::whereIn('status', ['responded', 'closed'])->count()
            ),
            'student_advisors' => (string) (
                TeamMember::active()->students()->count()
            ),
            'years_serving' => (string) (now()->year - 2015),
            'avg_response' => $this->computeAvgResponseHours(),
            default => $this->manual_value ?: '0',
        };
    }

    protected function computeAvgResponseHours(): string
    {
        $avg = Enquiry::whereNotNull('responded_at')
            ->select(DB::raw('AVG(TIMESTAMPDIFF(HOUR, created_at, responded_at)) as avg_hours'))
            ->value('avg_hours');

        if (!$avg) {
            return $this->manual_value ?: '48';
        }

        return $avg < 24 ? (string) round($avg) : round($avg / 24, 1) . 'd';
    }
}
