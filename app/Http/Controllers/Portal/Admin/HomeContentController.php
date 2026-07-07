<?php

namespace App\Http\Controllers\Portal\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\GalleryItem;
use App\Models\HeroSlide;
use App\Models\HomeStat;
use App\Models\HowItWorksStep;
use App\Models\MarqueeItem;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeContentController extends Controller
{
    public function index()
    {
        $heroSlides      = HeroSlide::orderBy('sort_order')->get();
        $galleryItems    = GalleryItem::orderBy('sort_order')->get();
        $steps           = HowItWorksStep::orderBy('sort_order')->get();
        $marqueeItems    = MarqueeItem::orderBy('sort_order')->get();
        $stats           = HomeStat::orderBy('sort_order')->get();
        $iconOptions     = HowItWorksStep::ICONS;

        $aboutSettings = [
            'about_mission' => SiteSetting::get('about_mission'),
            'about_vision'  => SiteSetting::get('about_vision'),
        ];
        $contactSettings = [
            'contact_address' => SiteSetting::get('contact_address'),
            'contact_email'   => SiteSetting::get('contact_email'),
            'contact_hours'   => SiteSetting::get('contact_hours'),
        ];

        return view('portal.admin.content.index', compact(
            'heroSlides', 'galleryItems', 'steps', 'marqueeItems', 'stats', 'iconOptions',
            'aboutSettings', 'contactSettings'
        ));
    }

    /**
     * Shared helper: resolve image_path from either an uploaded file
     * or a pasted URL/asset path. Returns null if neither was given.
     */
    private function resolveImagePath(Request $request, string $folder, ?string $existing = null): ?string
    {
        if ($request->hasFile('image')) {
            // Clean up the old uploaded file if it was on our disk (not a pasted URL)
            if ($existing && !preg_match('#^https?://#i', $existing) && !str_starts_with($existing, 'assets/')) {
                Storage::disk('public')->delete($existing);
            }
            return $request->file('image')->store("home-content/{$folder}", 'public');
        }

        if ($request->filled('image_url')) {
            return $request->input('image_url');
        }

        return $existing; // unchanged
    }

    // ════════════════════════════════════════════════════════════════════
    // HERO SLIDES
    // ════════════════════════════════════════════════════════════════════

    public function heroStore(Request $request)
    {
        $data = $request->validate([
            'heading'              => ['required', 'string', 'max:500'],
            'subtitle'              => ['nullable', 'string', 'max:1000'],
            'primary_cta_label'     => ['nullable', 'string', 'max:50'],
            'primary_cta_url'       => ['nullable', 'string', 'max:255'],
            'secondary_cta_label'   => ['nullable', 'string', 'max:50'],
            'secondary_cta_url'     => ['nullable', 'string', 'max:255'],
            'image'                 => ['nullable', 'image', 'max:4096'],
            'image_url'             => ['nullable', 'string', 'max:500'],
        ]);

        $data['image_path'] = $this->resolveImagePath($request, 'hero');
        unset($data['image'], $data['image_url']);

        $data['sort_order'] = HeroSlide::max('sort_order') + 1;
        $data['is_active']  = true;

        $slide = HeroSlide::create($data);
        ActivityLog::record('content.hero_slide_created', $slide);

        return back()->with('success', 'Hero slide added.');
    }

    public function heroUpdate(Request $request, HeroSlide $slide)
    {
        $data = $request->validate([
            'heading'              => ['required', 'string', 'max:500'],
            'subtitle'              => ['nullable', 'string', 'max:1000'],
            'primary_cta_label'     => ['nullable', 'string', 'max:50'],
            'primary_cta_url'       => ['nullable', 'string', 'max:255'],
            'secondary_cta_label'   => ['nullable', 'string', 'max:50'],
            'secondary_cta_url'     => ['nullable', 'string', 'max:255'],
            'image'                 => ['nullable', 'image', 'max:4096'],
            'image_url'             => ['nullable', 'string', 'max:500'],
            'is_active'             => ['nullable', 'boolean'],
        ]);

        $data['image_path'] = $this->resolveImagePath($request, 'hero', $slide->image_path);
        unset($data['image'], $data['image_url']);
        $data['is_active'] = $request->boolean('is_active');

        $slide->update($data);
        ActivityLog::record('content.hero_slide_updated', $slide);

        return back()->with('success', 'Hero slide updated.');
    }

    public function heroDestroy(HeroSlide $slide)
    {
        if ($slide->image_path && !preg_match('#^https?://#i', $slide->image_path) && !str_starts_with($slide->image_path, 'assets/')) {
            Storage::disk('public')->delete($slide->image_path);
        }
        $slide->delete();
        ActivityLog::record('content.hero_slide_deleted');

        return back()->with('success', 'Hero slide removed.');
    }

    public function heroReorder(Request $request)
    {
        $request->validate(['order' => ['required', 'array']]);
        foreach ($request->order as $i => $id) {
            HeroSlide::where('id', $id)->update(['sort_order' => $i]);
        }
        return response()->json(['success' => true]);
    }

    // ════════════════════════════════════════════════════════════════════
    // GALLERY
    // ════════════════════════════════════════════════════════════════════

    public function galleryStore(Request $request)
    {
        $data = $request->validate([
            'caption'   => ['nullable', 'string', 'max:120'],
            'height'    => ['nullable', 'integer', 'min:120', 'max:600'],
            'image'     => ['nullable', 'image', 'max:4096'],
            'image_url' => ['nullable', 'string', 'max:500'],
        ]);

        $data['image_path'] = $this->resolveImagePath($request, 'gallery');
        unset($data['image'], $data['image_url']);

        $data['sort_order'] = GalleryItem::max('sort_order') + 1;
        $data['is_active']  = true;

        $item = GalleryItem::create($data);
        ActivityLog::record('content.gallery_item_created', $item);

        return back()->with('success', 'Gallery photo added.');
    }

    public function galleryUpdate(Request $request, GalleryItem $item)
    {
        $data = $request->validate([
            'caption'   => ['nullable', 'string', 'max:120'],
            'height'    => ['nullable', 'integer', 'min:120', 'max:600'],
            'image'     => ['nullable', 'image', 'max:4096'],
            'image_url' => ['nullable', 'string', 'max:500'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['image_path'] = $this->resolveImagePath($request, 'gallery', $item->image_path);
        unset($data['image'], $data['image_url']);
        $data['is_active'] = $request->boolean('is_active');

        $item->update($data);
        ActivityLog::record('content.gallery_item_updated', $item);

        return back()->with('success', 'Gallery photo updated.');
    }

    public function galleryDestroy(GalleryItem $item)
    {
        if ($item->image_path && !preg_match('#^https?://#i', $item->image_path) && !str_starts_with($item->image_path, 'assets/')) {
            Storage::disk('public')->delete($item->image_path);
        }
        $item->delete();
        ActivityLog::record('content.gallery_item_deleted');

        return back()->with('success', 'Gallery photo removed.');
    }

    public function galleryReorder(Request $request)
    {
        $request->validate(['order' => ['required', 'array']]);
        foreach ($request->order as $i => $id) {
            GalleryItem::where('id', $id)->update(['sort_order' => $i]);
        }
        return response()->json(['success' => true]);
    }

    // ════════════════════════════════════════════════════════════════════
    // HOW IT WORKS STEPS
    // ════════════════════════════════════════════════════════════════════

    public function stepStore(Request $request)
    {
        $data = $request->validate([
            'title'       => ['required', 'string', 'max:80'],
            'description' => ['required', 'string', 'max:300'],
            'icon_key'    => ['required', 'string', 'in:' . implode(',', array_keys(HowItWorksStep::ICONS))],
        ]);

        $data['sort_order'] = HowItWorksStep::max('sort_order') + 1;
        $data['is_active']  = true;

        $step = HowItWorksStep::create($data);
        ActivityLog::record('content.step_created', $step);

        return back()->with('success', 'Step added.');
    }

    public function stepUpdate(Request $request, HowItWorksStep $step)
    {
        $data = $request->validate([
            'title'       => ['required', 'string', 'max:80'],
            'description' => ['required', 'string', 'max:300'],
            'icon_key'    => ['required', 'string', 'in:' . implode(',', array_keys(HowItWorksStep::ICONS))],
            'is_active'   => ['nullable', 'boolean'],
        ]);
        $data['is_active'] = $request->boolean('is_active');

        $step->update($data);
        ActivityLog::record('content.step_updated', $step);

        return back()->with('success', 'Step updated.');
    }

    public function stepDestroy(HowItWorksStep $step)
    {
        $step->delete();
        ActivityLog::record('content.step_deleted');

        return back()->with('success', 'Step removed.');
    }

    public function stepReorder(Request $request)
    {
        $request->validate(['order' => ['required', 'array']]);
        foreach ($request->order as $i => $id) {
            HowItWorksStep::where('id', $id)->update(['sort_order' => $i]);
        }
        return response()->json(['success' => true]);
    }

    // ════════════════════════════════════════════════════════════════════
    // MARQUEE
    // ════════════════════════════════════════════════════════════════════

    public function marqueeStore(Request $request)
    {
        $data = $request->validate(['text' => ['required', 'string', 'max:100']]);

        $data['sort_order'] = MarqueeItem::max('sort_order') + 1;
        $data['is_active']  = true;

        $item = MarqueeItem::create($data);
        ActivityLog::record('content.marquee_item_created', $item);

        return back()->with('success', 'Marquee text added.');
    }

    public function marqueeUpdate(Request $request, MarqueeItem $item)
    {
        $data = $request->validate([
            'text'      => ['required', 'string', 'max:100'],
            'is_active' => ['nullable', 'boolean'],
        ]);
        $data['is_active'] = $request->boolean('is_active');

        $item->update($data);
        ActivityLog::record('content.marquee_item_updated', $item);

        return back()->with('success', 'Marquee text updated.');
    }

    public function marqueeDestroy(MarqueeItem $item)
    {
        $item->delete();
        ActivityLog::record('content.marquee_item_deleted');

        return back()->with('success', 'Marquee text removed.');
    }

    public function marqueeReorder(Request $request)
    {
        $request->validate(['order' => ['required', 'array']]);
        foreach ($request->order as $i => $id) {
            MarqueeItem::where('id', $id)->update(['sort_order' => $i]);
        }
        return response()->json(['success' => true]);
    }

    // ════════════════════════════════════════════════════════════════════
    // STATS (bulk update — fixed set of rows, no create/delete)
    // ════════════════════════════════════════════════════════════════════

    public function statsUpdate(Request $request)
    {
        $request->validate([
            'stats' => ['required', 'array'],
            'stats.*.id'           => ['required', 'exists:home_stats,id'],
            'stats.*.label'        => ['required', 'string', 'max:40'],
            'stats.*.suffix'       => ['nullable', 'string', 'max:5'],
            'stats.*.manual_value' => ['nullable', 'string', 'max:20'],
            'stats.*.is_auto'      => ['nullable'],
        ]);

        foreach ($request->input('stats') as $row) {
            HomeStat::where('id', $row['id'])->update([
                'label'        => $row['label'],
                'suffix'       => $row['suffix'] ?? null,
                'manual_value' => $row['manual_value'] ?? null,
                'is_auto'      => !empty($row['is_auto']),
            ]);
        }

        ActivityLog::record('content.stats_updated');

        return back()->with('success', 'Stats updated.');
    }

    // ════════════════════════════════════════════════════════════════════
    // SITE SETTINGS — About page (Mission / Vision)
    // ════════════════════════════════════════════════════════════════════

    public function aboutSettingsUpdate(Request $request)
    {
        $data = $request->validate([
            'about_mission' => ['required', 'string', 'max:2000'],
            'about_vision'  => ['required', 'string', 'max:2000'],
        ]);

        SiteSetting::setMany($data);
        ActivityLog::record('content.about_settings_updated');

        return back()->with('success', 'About page content updated.');
    }

    // ════════════════════════════════════════════════════════════════════
    // SITE SETTINGS — Contact page (Address / Email / Hours)
    // ════════════════════════════════════════════════════════════════════

    public function contactSettingsUpdate(Request $request)
    {
        $data = $request->validate([
            'contact_address' => ['required', 'string', 'max:500'],
            'contact_email'   => ['required', 'email', 'max:255'],
            'contact_hours'   => ['required', 'string', 'max:200'],
        ]);

        SiteSetting::setMany($data);
        ActivityLog::record('content.contact_settings_updated');

        return back()->with('success', 'Contact page content updated.');
    }
}
