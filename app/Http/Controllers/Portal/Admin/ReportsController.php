<?php

namespace App\Http\Controllers\Portal\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use App\Models\EnquiryResponse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', '30'); // days

        $from = now()->subDays((int) $period);

        // Overall totals
        $totals = [
            'all_time_enquiries' => Enquiry::count(),
            'responded'          => Enquiry::where('status', 'responded')->count(),
            'period_enquiries'   => Enquiry::where('created_at', '>=', $from)->count(),
            'period_responded'   => Enquiry::where('status', 'responded')->where('responded_at', '>=', $from)->count(),
            'avg_response_hours' => $this->avgResponseTime(),
        ];

        // By category
        $byCategory = Enquiry::select('matter_category', DB::raw('COUNT(*) as count'))
            ->where('created_at', '>=', $from)
            ->groupBy('matter_category')
            ->orderByDesc('count')
            ->get()
            ->map(fn($r) => ['label' => Enquiry::MATTER_CATEGORIES[$r->matter_category] ?? $r->matter_category, 'count' => $r->count]);

        // By status
        $byStatus = Enquiry::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get()
            ->map(fn($r) => ['label' => Enquiry::STATUSES[$r->status] ?? $r->status, 'count' => $r->count, 'status' => $r->status]);

        // By month (last 6 months)
        $byMonth = Enquiry::select(
            DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
            DB::raw('COUNT(*) as count')
        )->where('created_at', '>=', now()->subMonths(6))
         ->groupBy('month')->orderBy('month')->get();

        // Advisor performance
        $advisorStats = User::advisors()->active()
            ->withCount(['assignments as total_assigned', 'responses as total_responses'])
            ->withCount(['responses as approved_responses' => fn($q) => $q->where('review_status', 'approved')])
            ->orderByDesc('total_assigned')
            ->get();

        return view('portal.admin.reports.index', compact('totals', 'byCategory', 'byStatus', 'byMonth', 'advisorStats', 'period'));
    }

    private function avgResponseTime(): ?string
    {
        $avg = Enquiry::whereNotNull('responded_at')
            ->select(DB::raw('AVG(TIMESTAMPDIFF(HOUR, created_at, responded_at)) as avg_hours'))
            ->value('avg_hours');

        if (!$avg) return null;

        if ($avg < 24) return round($avg) . 'h';
        return round($avg / 24, 1) . ' days';
    }
}
