<?php

namespace Database\Seeders;

use App\Models\GalleryItem;
use App\Models\HeroSlide;
use App\Models\HomeStat;
use App\Models\HowItWorksStep;
use App\Models\MarqueeItem;
use Illuminate\Database\Seeder;

class HomeContentSeeder extends Seeder
{
    public function run(): void
    {
        // ── Hero slides ──────────────────────────────────────────────────────
        if (HeroSlide::count() === 0) {
            $slides = [
                [
                    'heading' => "Free Legal\nGuidance for Every Need",
                    'subtitle' => 'Qualified law students, supervised by ESUT faculty, handle your enquiry with complete confidentiality. No fees. No account needed.',
                    'primary_cta_label' => 'Get Free Legal Help',
                    'primary_cta_url' => route('enquiry.create'),
                    'secondary_cta_label' => 'Track My Case',
                    'secondary_cta_url' => route('enquiry.track'),
                ],
                [
                    'heading' => "Expert Advice by Students,\nfor Students",
                    'subtitle' => 'Our advisors are trained law students at ESUT, working under the direct supervision of experienced faculty to deliver quality legal guidance.',
                    'primary_cta_label' => 'Meet the Team',
                    'primary_cta_url' => route('about'),
                    'secondary_cta_label' => 'Submit an Enquiry',
                    'secondary_cta_url' => route('enquiry.create'),
                ],
                [
                    'heading' => "Shaping Tomorrow's\nLegal Minds",
                    'subtitle' => 'Through moot court competitions, legal aid clinics, and faculty mentorship, the ESUT Law Clinic builds the next generation of Nigerian legal practitioners.',
                    'primary_cta_label' => 'View Events',
                    'primary_cta_url' => route('events.index'),
                    'secondary_cta_label' => 'Legal Resources',
                    'secondary_cta_url' => route('faq.index'),
                ],
                [
                    'heading' => "Justice Belongs\nto Everyone",
                    'subtitle' => 'Beyond the campus — the ESUT Law Clinic extends free legal guidance to underserved communities across Enugu State, bridging the access-to-justice gap.',
                    'primary_cta_label' => 'Get Free Help',
                    'primary_cta_url' => route('enquiry.create'),
                    'secondary_cta_label' => 'Contact Us',
                    'secondary_cta_url' => route('contact.index'),
                ],
            ];
            foreach ($slides as $i => $data) {
                HeroSlide::create([...$data, 'sort_order' => $i, 'is_active' => true]);
            }
        }

        // ── How it works steps ──────────────────────────────────────────────
        if (HowItWorksStep::count() === 0) {
            $steps = [
                ['title' => 'Submit Your Enquiry', 'description' => 'Fill in our short online form. You can remain anonymous for sensitive matters. Attach any relevant documents. Takes under 5 minutes.', 'icon_key' => 'document'],
                ['title' => 'Advisors Review', 'description' => 'A qualified student advisor, supervised by a faculty lecturer, reviews your matter and prepares a thorough legal response.', 'icon_key' => 'review'],
                ['title' => 'Track & Receive', 'description' => 'Use your reference code anytime to check progress. Once approved, your legal advice is delivered to your email.', 'icon_key' => 'track'],
            ];
            foreach ($steps as $i => $data) {
                HowItWorksStep::create([...$data, 'sort_order' => $i, 'is_active' => true]);
            }
        }

        // ── Gallery placeholders ────────────────────────────────────────────
        if (GalleryItem::count() === 0) {
            $items = [
                ['caption' => 'Legal Aid Clinic Session', 'height' => 340],
                ['caption' => 'Student Advisor Training', 'height' => 200],
                ['caption' => 'Faculty Supervision Workshop', 'height' => 360],
                ['caption' => 'Moot Court Competition', 'height' => 220],
                ['caption' => 'Community Outreach Programme', 'height' => 270],
                ['caption' => 'Law Clinic Awards Ceremony', 'height' => 250],
            ];
            foreach ($items as $i => $data) {
                GalleryItem::create([...$data, 'sort_order' => $i, 'is_active' => true]);
            }
        }

        // ── Marquee ticker ───────────────────────────────────────────────────
        if (MarqueeItem::count() === 0) {
            $texts = [
                'ESUT Law Clinic', 'Free Legal Guidance', 'Submit Your Enquiry Today',
                'Confidential & No Cost', 'Faculty of Law · ESUT', 'Know Your Rights',
                'Agbani, Enugu State',
            ];
            foreach ($texts as $i => $text) {
                MarqueeItem::create(['text' => $text, 'sort_order' => $i, 'is_active' => true]);
            }
        }

        // ── Stats (hero bar + how-it-works strip) ───────────────────────────
        if (HomeStat::count() === 0) {
            $stats = [
                ['stat_key' => 'cases_handled',    'label' => 'Cases Handled',     'suffix' => '+', 'is_auto' => true],
                ['stat_key' => 'student_advisors', 'label' => 'Student Advisors',  'suffix' => null, 'is_auto' => true],
                ['stat_key' => 'years_serving',    'label' => 'Years Serving',     'suffix' => '+', 'is_auto' => true],
                ['stat_key' => 'avg_response',     'label' => 'Avg. Response',     'suffix' => 'h', 'is_auto' => true, 'manual_value' => '48'],
            ];
            foreach ($stats as $i => $data) {
                HomeStat::create([...$data, 'sort_order' => $i]);
            }
        }
    }
}
