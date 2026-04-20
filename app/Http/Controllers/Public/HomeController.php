<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\FaqCategory;
use App\Models\TeamMember;
use App\Models\Event;

class HomeController extends Controller
{
    public function index()
    {
        $faqCategories = FaqCategory::active()
            ->with(['publishedArticles' => fn($q) => $q->take(3)])
            ->take(6)
            ->get();

        $upcomingEvents = Event::published()->upcoming()->take(3)->get();

        $lecturers = TeamMember::active()->lecturers()->take(4)->get();

        $stats = [
            'cases_handled' => \App\Models\Enquiry::whereIn('status', ['responded', 'closed'])->count() ?: 120,
            'students'      => TeamMember::active()->students()->count() ?: 24,
            'years_running' => now()->year - 2015,
        ];

        return view('home.index', compact('faqCategories', 'upcomingEvents', 'lecturers', 'stats'));
    }
}
