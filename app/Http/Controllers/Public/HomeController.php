<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\FaqCategory;
use App\Models\GalleryItem;
use App\Models\HeroSlide;
use App\Models\HomeStat;
use App\Models\HowItWorksStep;
use App\Models\MarqueeItem;
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
        $lecturers      = TeamMember::active()->lecturers()->take(4)->get();

        // ── Admin-managed homepage content ──────────────────────────────────
        $heroSlides       = HeroSlide::active()->get();
        $howItWorksSteps  = HowItWorksStep::active()->get();
        $galleryItems     = GalleryItem::active()->get();
        $marqueeItems     = MarqueeItem::active()->get();
        $homeStats        = HomeStat::ordered()->get();

        return view('home.index', compact(
            'faqCategories', 'upcomingEvents', 'lecturers',
            'heroSlides', 'howItWorksSteps', 'galleryItems', 'marqueeItems', 'homeStats'
        ));
    }
}
