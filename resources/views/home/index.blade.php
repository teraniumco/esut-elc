@extends('layouts.app')
@section('title', 'Home')
@section('meta_description', 'ESUT Law Clinic offers free, confidential legal guidance to the ESUT community — submit your enquiry, track your case, and access legal resources.')

@push('styles')
<style>
    /* ─────────────────────────────────────────────────────────
    HERO — Full-width carousel
    ───────────────────────────────────────────────────────── */
    .hero-section {
        position: relative;
        width: 100%;
        height: 100vh;           /* explicit height — slides are absolute, parent must have defined height */
        min-height: 600px;       /* floor so nothing collapses on tiny screens */
        overflow: hidden;
    }

    /* ── Individual slides ── */
    .hero-slide {
        position: absolute; inset: 0;
        display: flex; align-items: center;
        opacity: 0;
        transition: opacity 1s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 1;
    }
    .hero-slide.active { opacity: 1; z-index: 2; }

    /* Slide background */
    .hero-slide-bg {
        position: absolute; inset: 0;
        background-size: cover; background-position: center;
        z-index: 0;
        transform: scale(1.05);
        transition: transform 7s ease;
    }
    .hero-slide.active .hero-slide-bg { transform: scale(1); } /* Ken Burns zoom-out */

    /* Multi-layer overlay */
    .hero-slide-overlay {
        position: absolute; inset: 0; z-index: 1;
        background:
            linear-gradient(to right,  rgba(74,13,0,0.88) 0%, rgba(74,13,0,0.50) 55%, rgba(74,13,0,0.18) 100%),
            linear-gradient(to top,    rgba(74,13,0,0.65) 0%, transparent 55%);
    }

    /* Atmospheric layers */
    .hero-grid-lines {
        position: absolute; inset: 0; z-index: 2; pointer-events: none;
        background-image:
            linear-gradient(rgba(255,255,255,0.04) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,0.04) 1px, transparent 1px);
        background-size: 60px 60px;
    }
    .hero-noise {
        position: absolute; inset: 0; z-index: 2; pointer-events: none;
        background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.04'/%3E%3C/svg%3E");
        opacity: 0.15;
    }
    .hero-ring {
        position: absolute; border-radius: 50%;
        border: 1px solid rgba(255,255,255,0.06); z-index: 2; pointer-events: none;
    }
    .hero-ring-1 { width: 700px; height: 700px; top: -250px; right: -150px; }
    .hero-ring-2 { width: 400px; height: 400px; bottom: -150px; left: -80px; border-color: rgba(255,255,255,0.03); }
    .hero-ring-3 { width: 180px; height: 180px; top: 30%; left: 42%; border-color: rgba(201,168,76,0.12); }

    /* Slide text content */
    .hero-slide-content {
        position: relative; z-index: 3;
        max-width: 1280px; margin: 0 auto;
        padding: 0 40px;
        width: 100%;
    }
    .hero-slide-inner { max-width: 680px; }

    /* Badge */
    .hero-badge {
        display: inline-flex; align-items: center; gap: 8px;
        font-size: 11px; font-weight: 700; letter-spacing: 2.5px;
        text-transform: uppercase; color: rgba(255,255,255,0.65);
        margin-bottom: 20px;
    }
    .hero-badge-dot {
        width: 8px; height: 8px; background: var(--gold);
        border-radius: 50%;
        animation: pulse-dot 2s ease-in-out infinite;
    }
    @keyframes pulse-dot {
        0%,100% { box-shadow: 0 0 0 0 rgba(201,168,76,0.5); }
        50%      { box-shadow: 0 0 0 8px rgba(201,168,76,0); }
    }

    /* Tag pill */
    .hero-tag-pill {
        display: inline-flex; align-items: center; gap: 8px;
        background: rgba(255,255,255,0.08);
        border: 1px solid rgba(255,255,255,0.14);
        border-radius: 30px; padding: 6px 16px;
        font-size: 12px; color: rgba(255,255,255,0.7);
        margin-bottom: 24px; backdrop-filter: blur(8px);
        letter-spacing: 0.5px;
    }
    .hero-tag-pill-dot { width: 5px; height: 5px; border-radius: 50%; background: var(--gold); flex-shrink: 0; }

    /* Title */
    .hero-title {
        font-family: 'DM Serif Display', serif;
        font-size: clamp(40px, 6.5vw, 82px);
        font-weight: 400; color: #fff;
        line-height: 1.0; letter-spacing: -2.5px;
        margin-bottom: 22px;
    }
    .hero-title em   { font-style: italic; color: rgba(255,255,255,0.55); }
    .hero-title-gold { color: var(--gold-light); }

    /* Subtitle */
    .hero-subtitle {
        font-size: 16px; color: rgba(255,255,255,0.55);
        line-height: 1.65; max-width: 520px;
        margin-bottom: 36px;
    }

    /* CTAs */
    .hero-ctas { display: flex; align-items: center; gap: 14px; flex-wrap: wrap; }
    .hero-btn-primary {
        display: inline-flex; align-items: center; gap: 10px;
        background: var(--gold); color: var(--dark);
        font-size: 14px; font-weight: 700;
        padding: 14px 30px; border-radius: 7px;
        text-decoration: none; letter-spacing: 0.3px;
        transition: background 0.25s, transform 0.2s, box-shadow 0.25s;
        box-shadow: 0 6px 24px rgba(201,168,76,0.35);
    }
    .hero-btn-primary:hover { background: var(--gold-light); transform: translateY(-3px); box-shadow: 0 12px 32px rgba(201,168,76,0.45); text-decoration: none; color: var(--dark); }
    .hero-btn-ghost {
        display: inline-flex; align-items: center; gap: 8px;
        background: transparent; color: rgba(255,255,255,0.75);
        font-size: 14px; font-weight: 600;
        padding: 14px 24px; border-radius: 7px;
        border: 1px solid rgba(255,255,255,0.2);
        text-decoration: none;
        transition: background 0.2s, border-color 0.2s, color 0.2s;
    }
    .hero-btn-ghost:hover { background: rgba(255,255,255,0.08); border-color: rgba(255,255,255,0.4); color: #fff; text-decoration: none; }

    /* Stats bar */
    .hero-stats-bar {
        position: absolute; bottom: 0; left: 0; right: 0;
        z-index: 10;
        background: rgba(74,13,0,0.62);
        backdrop-filter: blur(16px);
        border-top: 1px solid rgba(255,255,255,0.07);
    }
    .hero-stats-inner {
        max-width: 1280px; margin: 0 auto;
        padding: 0 40px;
        display: flex; align-items: stretch; justify-content: space-between;
    }
    .hero-stat-item {
        flex: 1; display: flex; flex-direction: column;
        align-items: center; justify-content: center;
        gap: 4px; padding: 20px 16px;
        border-right: 1px solid rgba(255,255,255,0.07);
    }
    .hero-stat-item:last-child { border-right: none; }
    .hero-stat-num { font-family: 'DM Serif Display', serif; font-size: 26px; color: #fff; line-height: 1; }
    .hero-stat-num sup { font-size: 13px; color: var(--gold); }
    .hero-stat-cap { font-size: 10px; color: rgba(255,255,255,0.4); text-transform: uppercase; letter-spacing: 1.5px; }

    /* Arrows */
    .carousel-arrow {
        position: absolute; top: 50%; transform: translateY(-50%);
        z-index: 10; width: 48px; height: 48px; border-radius: 50%;
        background: rgba(255,255,255,0.08);
        border: 1px solid rgba(255,255,255,0.15);
        backdrop-filter: blur(12px);
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; color: rgba(255,255,255,0.85);
        transition: background 0.2s, border-color 0.2s;
    }
    .carousel-arrow:hover { background: rgba(201,168,76,0.22); border-color: rgba(201,168,76,0.45); color: #fff; }
    .carousel-arrow-prev { left: 28px; }
    .carousel-arrow-next { right: 28px; }
    .carousel-arrow:active { transform: translateY(-50%) scale(0.91); }

    /* Dots */
    .carousel-dots {
        position: absolute; bottom: 90px; left: 50%; transform: translateX(-50%);
        display: flex; gap: 8px; z-index: 10;
    }
    .carousel-dot {
        width: 7px; height: 7px; border-radius: 50%;
        background: rgba(255,255,255,0.3);
        cursor: pointer; border: none;
        transition: background 0.3s, width 0.35s cubic-bezier(0.4,0,0.2,1), border-radius 0.35s;
    }
    .carousel-dot.active { width: 28px; border-radius: 4px; background: var(--gold); }

    /* Progress bar */
    .hero-progress {
        position: absolute; top: 0; left: 0; right: 0; height: 3px; z-index: 10;
        background: rgba(255,255,255,0.08);
    }
    .hero-progress-fill {
        height: 100%; background: var(--gold); width: 0%;
    }
    .hero-progress-fill.running {
        animation: progress-fill 5s linear forwards;
    }
    @keyframes progress-fill {
        from { width: 0%; }
        to   { width: 100%; }
    }

    /* Slide counter */
    .hero-counter {
        position: absolute; right: 40px; bottom: 98px;
        z-index: 10; font-size: 12px; color: rgba(255,255,255,0.35);
        font-weight: 600; letter-spacing: 2px;
    }

    /* ── Responsive ── */
    @media (max-width: 991px) {
        .hero-slide-content  { padding: 0 24px; }
        .how-cut, .faq-cut, .team-cut { display: none; }
        .stats-strip         { flex-wrap: wrap; justify-content: center; gap: 24px; }
        .stat-divider        { display: none; }
        .stat-item           { min-width: 100px; }
        .faq-num             { width: 60px; }
        .faq-num span        { font-size: 26px; }
        .carousel-arrow      { width: 40px; height: 40px; }
        .carousel-arrow-prev { left: 14px; }
        .carousel-arrow-next { right: 14px; }
        .hero-counter        { display: none; }
    }
    @media (max-width: 767px) {
        .hero-title          { font-size: 38px; letter-spacing: -1.5px; }
        .hero-subtitle       { font-size: 14px; }
        .hero-ctas           { flex-direction: column; align-items: flex-start; }
        .hero-stat-item      { padding: 14px 8px; }
        .hero-stat-num       { font-size: 19px; }
        .carousel-dots       { bottom: 80px; }
        .hero-stats-inner    { padding: 0 16px; }
        .stats-strip         { padding: 24px 20px; }
        .stat-big            { font-size: 30px; }
        .faq-item:hover      { transform: none; }
        .faq-num             { display: none; }
        .feature-pill        { padding: 18px; }
        .cta-inner           { padding: 48px 24px; }
    }
    @media (max-width: 480px) {
        .hero-section        { min-height: 100svh; height: 100svh; }
        .hero-slide-content  { padding: 0 18px; }
        .carousel-arrow      { display: none; }
        .hero-stat-cap       { display: none; }
        .hero-stat-num       { font-size: 17px; }
        .hero-stats-inner    { padding: 0 10px; }
    }


    /* ─────────────────────────────────────────────────────────
    HOW IT WORKS (Feature Pills)
    ───────────────────────────────────────────────────────── */
    .how-section {
        position: relative;
        background: var(--off-white);
        padding: 100px 0 140px; overflow: hidden;
    }
    .how-section .container-inner { position: relative; z-index: 1; }

    .feature-pill {
        display: flex; align-items: center; gap: 18px;
        background: #fff; border: 1px solid var(--border);
        border-radius: 14px; padding: 24px;
        transition: box-shadow 0.25s, border-color 0.25s, transform 0.25s;
        height: 100%;
    }
    .feature-pill:hover {
        border-color: var(--crimson);
        box-shadow: 0 8px 32px rgba(113,21,0,0.1);
        transform: translateY(-4px);
    }
    .fp-icon {
        flex-shrink: 0; width: 52px; height: 52px;
        background: var(--crimson-light); border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        color: var(--crimson); transition: background 0.25s;
    }
    .feature-pill:hover .fp-icon { background: var(--crimson); color: #fff; }
    .fp-num { font-family: 'DM Serif Display', serif; font-size: 11px; font-weight: 400; letter-spacing: 1px; text-transform: uppercase; color: var(--gold); margin-bottom: 5px; }
    .fp-title { font-size: 15px; font-weight: 700; color: var(--text); margin-bottom: 5px; }
    .fp-desc  { font-size: 12.5px; color: var(--text-light); line-height: 1.6; margin: 0; }

    /* Stats strip */
    .stats-strip {
        background: var(--crimson); border-radius: 16px; padding: 36px 48px;
        display: flex; align-items: center; justify-content: space-between; gap: 20px;
        overflow: hidden; position: relative;
    }
    .stats-strip::before { content: ''; position: absolute; top: -40px; right: -40px; width: 220px; height: 220px; border-radius: 50%; background: rgba(255,255,255,0.04); }
    .stats-strip::after  { content: ''; position: absolute; bottom: -60px; left: -60px; width: 200px; height: 200px; border-radius: 50%; background: rgba(0,0,0,0.08); }
    .stat-item   { display: flex; flex-direction: column; align-items: center; gap: 5px; flex: 1; position: relative; z-index: 1; }
    .stat-big    { font-family: 'DM Serif Display', serif; font-size: 38px; font-weight: 400; color: #fff; line-height: 1; }
    .stat-big sup { font-size: 16px; color: var(--gold-light); font-family: 'DM Sans', sans-serif; font-weight: 600; }
    .stat-cap    { font-size: 11px; color: rgba(255,255,255,0.5); text-transform: uppercase; letter-spacing: 1px; text-align: center; }
    .stat-divider { width: 1px; height: 44px; background: rgba(255,255,255,0.12); flex-shrink: 0; }

    .how-cut { position: absolute; bottom: -1px; left: 0; right: 0; height: 70px; background: var(--crimson-dark); clip-path: polygon(0 0, 100% 100%, 0 100%); z-index: 2; }


    /* ─────────────────────────────────────────────────────────
    FAQ CATEGORIES (Subtheme-style list)
    ───────────────────────────────────────────────────────── */
    .faq-section { position: relative; padding: 120px 0 130px; overflow: hidden; }
    .faq-bg { position: absolute; inset: 0; background: linear-gradient(135deg, rgba(74,13,0,0.97) 0%, rgba(113,21,0,0.94) 50%, rgba(74,13,0,0.97) 100%); z-index: 0; }
    .faq-bg-grid { position: absolute; inset: 0; z-index: 1; background-image: linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px); background-size: 60px 60px; }
    .faq-ring { position: absolute; border-radius: 50%; border: 1px solid rgba(201,168,76,0.07); z-index: 1; }
    .faq-ring-1 { width: 600px; height: 600px; top: -250px; right: -180px; }
    .faq-ring-2 { width: 350px; height: 350px; bottom: -120px; left: -100px; }
    .faq-section .inner { position: relative; z-index: 2; }
    .faq-list { display: flex; flex-direction: column; gap: 14px; }

    .faq-item {
        position: relative; display: flex; align-items: stretch;
        background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.07);
        border-radius: 14px; overflow: hidden; text-decoration: none;
        transition: background 0.3s, border-color 0.3s, transform 0.3s;
    }
    .faq-item:hover { background: rgba(201,168,76,0.06); border-color: rgba(201,168,76,0.3); transform: translateX(8px); text-decoration: none; }
    .faq-accent { position: absolute; left: 0; top: 0; bottom: 0; width: 3px; background: linear-gradient(180deg, var(--gold) 0%, rgba(201,168,76,0.15) 100%); transform: scaleY(0.3); transform-origin: top; transition: transform 0.35s ease; }
    .faq-item:hover .faq-accent { transform: scaleY(1); }
    .faq-num { flex-shrink: 0; width: 80px; display: flex; align-items: center; justify-content: center; border-right: 1px solid rgba(255,255,255,0.06); padding: 28px 0; }
    .faq-num span { font-family: 'DM Serif Display', serif; font-size: 32px; font-weight: 400; color: rgba(201,168,76,0.2); line-height: 1; transition: color 0.3s; }
    .faq-item:hover .faq-num span { color: rgba(201,168,76,0.6); }
    .faq-content { display: flex; align-items: flex-start; gap: 18px; padding: 26px 26px 26px 24px; flex: 1; }
    .faq-icon-wrap { flex-shrink: 0; width: 44px; height: 44px; border-radius: 10px; background: rgba(201,168,76,0.1); border: 1px solid rgba(201,168,76,0.2); display: flex; align-items: center; justify-content: center; font-size: 20px; margin-top: 2px; transition: background 0.3s, border-color 0.3s; }
    .faq-item:hover .faq-icon-wrap { background: rgba(201,168,76,0.2); border-color: rgba(201,168,76,0.45); }
    .faq-text h3 { font-family: 'DM Serif Display', serif; font-size: 19px; font-weight: 400; color: #fff; margin-bottom: 6px; line-height: 1.25; transition: color 0.3s; }
    .faq-item:hover .faq-text h3 { color: var(--gold-light); }
    .faq-text p { font-size: 13px; color: rgba(255,255,255,0.45); line-height: 1.7; margin: 0; }
    .faq-text .faq-count { font-size: 11px; color: rgba(201,168,76,0.5); margin-top: 6px; letter-spacing: 0.5px; }
    .faq-arrow { flex-shrink: 0; display: flex; align-items: center; padding-right: 24px; color: rgba(255,255,255,0.15); transition: color 0.3s; }
    .faq-item:hover .faq-arrow { color: var(--gold); }
    .faq-cut { position: absolute; bottom: -1px; left: 0; right: 0; height: 70px; background: var(--off-white); clip-path: polygon(0 100%, 100% 0, 100% 100%); z-index: 2; }


    /* ─────────────────────────────────────────────────────────
    TEAM / SUPERVISORS
    ───────────────────────────────────────────────────────── */
    .team-section { background: var(--off-white); padding: 110px 0 130px; overflow: hidden; }
    .lecturer-card { background: #fff; border: 1px solid var(--border); border-radius: 16px; padding: 24px; display: flex; gap: 16px; align-items: flex-start; transition: box-shadow 0.25s, transform 0.25s; }
    .lecturer-card:hover { box-shadow: 0 8px 32px rgba(113,21,0,0.1); transform: translateY(-4px); }
    .lecturer-photo { width: 56px; height: 56px; border-radius: 50%; background: var(--crimson-light); overflow: hidden; flex-shrink: 0; border: 2px solid var(--border); }
    .lecturer-photo img { width: 100%; height: 100%; object-fit: cover; }
    .lecturer-name { font-size: 15px; font-weight: 700; color: var(--text); margin-bottom: 3px; }
    .lecturer-role { font-size: 12px; color: var(--gold); font-weight: 600; }
    .lecturer-bio  { font-size: 12px; color: var(--text-light); margin-top: 6px; line-height: 1.5; }
    .team-cut { position: absolute; bottom: -1px; left: 0; right: 0; height: 70px; background: #fff; clip-path: polygon(0 0, 0 100%, 100% 100%); }


    /* ─────────────────────────────────────────────────────────
    EVENTS
    ───────────────────────────────────────────────────────── */
    .events-section { background: #fff; padding: 100px 0 110px; }
    .event-card { background: #fff; border: 1px solid var(--border); border-radius: 18px; overflow: hidden; transition: box-shadow 0.3s, transform 0.3s, border-color 0.3s; text-decoration: none; display: flex; flex-direction: column; }
    .event-card:hover { box-shadow: 0 16px 56px rgba(113,21,0,0.12); transform: translateY(-6px); border-color: rgba(113,21,0,0.2); text-decoration: none; }
    .event-card-head { background: var(--crimson); padding: 20px; display: flex; align-items: flex-start; gap: 16px; }
    .event-date-block { text-align: center; flex-shrink: 0; }
    .event-day   { font-family: 'DM Serif Display', serif; font-size: 32px; font-weight: 400; color: var(--gold); line-height: 1; }
    .event-month { font-size: 11px; color: rgba(255,255,255,0.55); letter-spacing: 1px; text-transform: uppercase; margin-top: 2px; }
    .event-card-title { font-size: 15px; font-weight: 700; color: #fff; line-height: 1.3; transition: color 0.2s; }
    .event-card:hover .event-card-title { color: var(--gold-light); }
    .event-card-loc  { font-size: 11px; color: rgba(255,255,255,0.5); margin-top: 4px; }
    .event-card-body { padding: 18px; flex: 1; }
    .event-card-desc { font-size: 13px; color: var(--text-light); line-height: 1.65; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }


    /* ─────────────────────────────────────────────────────────
    MARQUEE
    ───────────────────────────────────────────────────────── */
    .marquee-section { background: var(--crimson-dark); overflow: hidden; padding: 22px 0; border-top: 1px solid rgba(255,255,255,0.05); border-bottom: 1px solid rgba(255,255,255,0.05); }
    .marquee-track { overflow: hidden; }
    .marquee-inner { display: flex; align-items: center; animation: marquee-scroll 32s linear infinite; white-space: nowrap; width: max-content; }
    @keyframes marquee-scroll { from { transform: translateX(0); } to { transform: translateX(-50%); } }
    .mq-item { font-family: 'DM Serif Display', serif; font-size: 16px; font-weight: 400; color: rgba(255,255,255,0.7); letter-spacing: 0.5px; padding: 0 32px; }
    .mq-dot  { width: 5px; height: 5px; border-radius: 50%; background: var(--gold); flex-shrink: 0; }


    /* ─────────────────────────────────────────────────────────
    BOTTOM CTA
    ───────────────────────────────────────────────────────── */
    .cta-section { background: var(--off-white); padding: 100px 0; }
    .cta-inner { background: var(--crimson); border-radius: 24px; padding: 72px 48px; text-align: center; position: relative; overflow: hidden; }
    .cta-inner::before { content: ''; position: absolute; top: -80px; right: -80px; width: 350px; height: 350px; border-radius: 50%; background: rgba(255,255,255,0.04); }
    .cta-inner::after  { content: ''; position: absolute; bottom: -100px; left: -60px; width: 300px; height: 300px; border-radius: 50%; background: rgba(0,0,0,0.1); }
    .cta-inner .content { position: relative; z-index: 1; }
    .cta-title { font-family: 'DM Serif Display', serif; font-size: clamp(28px,3.5vw,42px); font-weight: 400; color: #fff; line-height: 1.15; margin-bottom: 16px; }
    .cta-desc  { font-size: 15px; color: rgba(255,255,255,0.65); margin-bottom: 36px; line-height: 1.65; }
    .cta-btn-primary { display: inline-flex; align-items: center; gap: 10px; background: var(--gold); color: var(--dark); font-size: 14px; font-weight: 700; padding: 14px 32px; border-radius: 8px; text-decoration: none; transition: background 0.2s, transform 0.15s; box-shadow: 0 6px 20px rgba(201,168,76,0.35); }
    .cta-btn-primary:hover { background: var(--gold-light); transform: translateY(-3px); text-decoration: none; color: var(--dark); }
    .cta-btn-ghost { display: inline-flex; align-items: center; gap: 8px; background: transparent; color: rgba(255,255,255,0.75); font-size: 14px; font-weight: 600; padding: 14px 28px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.2); text-decoration: none; transition: background 0.2s, color 0.2s; }
    .cta-btn-ghost:hover { background: rgba(255,255,255,0.08); color: #fff; text-decoration: none; }


    /* ─────────────────────────────────────────────────────────
    GALLERY
    ───────────────────────────────────────────────────────── */
    .gallery-section { background: var(--off-white); padding: 100px 0 110px; overflow: hidden; }
    .gallery-grid { columns: 3; column-gap: 16px; }
    @media (max-width: 900px) { .gallery-grid { columns: 2; } }
    @media (max-width: 540px) { .gallery-grid { columns: 1; } }
    .gallery-item { break-inside: avoid; margin-bottom: 16px; border-radius: 14px; overflow: hidden; position: relative; cursor: pointer; opacity: 0; transform: translateY(28px); animation: gallery-fade-up 0.65s ease forwards; }
    .gallery-item:nth-child(1) { animation-delay: 0.05s; }
    .gallery-item:nth-child(2) { animation-delay: 0.15s; }
    .gallery-item:nth-child(3) { animation-delay: 0.25s; }
    .gallery-item:nth-child(4) { animation-delay: 0.35s; }
    .gallery-item:nth-child(5) { animation-delay: 0.45s; }
    .gallery-item:nth-child(6) { animation-delay: 0.55s; }
    @keyframes gallery-fade-up { to { opacity: 1; transform: translateY(0); } }
    .gallery-item img, .gallery-item .gallery-placeholder { width: 100%; display: block; transition: transform 0.55s cubic-bezier(0.25, 0.46, 0.45, 0.94); }
    .gallery-item:hover img, .gallery-item:hover .gallery-placeholder { transform: scale(1.05); }
    .gallery-overlay { position: absolute; inset: 0; background: linear-gradient(to top, rgba(74,13,0,0.75) 0%, transparent 60%); opacity: 0; transition: opacity 0.35s ease; display: flex; align-items: flex-end; padding: 20px; }
    .gallery-item:hover .gallery-overlay { opacity: 1; }
    .gallery-overlay-text { font-family: 'DM Serif Display', serif; font-size: 15px; color: #fff; line-height: 1.3; transform: translateY(6px); transition: transform 0.35s ease; }
    .gallery-item:hover .gallery-overlay-text { transform: translateY(0); }
    .gallery-placeholder { background: linear-gradient(135deg, var(--crimson-light) 0%, #ede3de 100%); display: flex; align-items: center; justify-content: center; font-size: 28px; color: rgba(113,21,0,0.15); }

</style>
@endpush

@section('content')

{{-- ═══ HERO — Full-width Carousel ════════════════════════════════════ --}}
<section class="hero-section"
         x-data="{
             current: 0,
             total: 4,
             timer: null,
             start() { this.timer = setInterval(() => this.next(), 5000); },
             next()  { this.current = (this.current + 1) % this.total; this.restart(); },
             prev()  { this.current = (this.current - 1 + this.total) % this.total; this.restart(); },
             go(i)   { this.current = i; this.restart(); },
             restart() { clearInterval(this.timer); this.resetProgress(); this.start(); },
             resetProgress() {
                 const bar = document.getElementById('hero-progress-fill');
                 if (!bar) return;
                 bar.classList.remove('running');
                 void bar.offsetWidth; /* force reflow to restart animation */
                 bar.classList.add('running');
             }
         }"
         x-init="start(); $nextTick(() => resetProgress())">

    {{-- Progress bar — restarted via JS on each slide change --}}
    <div class="hero-progress">
        <div id="hero-progress-fill" class="hero-progress-fill running"></div>
    </div>

    {{-- Atmospheric overlays --}}
    <div class="hero-grid-lines"></div>
    <div class="hero-noise"></div>
    <div class="hero-ring hero-ring-1"></div>
    <div class="hero-ring hero-ring-2"></div>
    <div class="hero-ring hero-ring-3"></div>

    {{-- ── Slide 1 · Legal Aid Clinic Sessions ── --}}
    <div class="hero-slide" :class="{ active: current === 0 }">
        <div class="hero-slide-bg" style="
            background-image:
                url('<?php echo asset('assets/img/hero/hero-1.jpg'); ?>'),
                radial-gradient(ellipse at 50% 50%, rgba(74,13,0,0.6) 0%, transparent 70%),
                linear-gradient(135deg, #3a0800 0%, #6a1400 50%, #4a0d00 100%);
        "></div>
        <div class="hero-slide-overlay"></div>
        <div class="hero-slide-content">
            <div class="hero-slide-inner">
                <h1 class="hero-title">
                    Free Legal<br>
                    <em>Guidance</em><br>
                    for <span class="hero-title-gold">Every Need</span>
                </h1>
                <p class="hero-subtitle">Qualified law students, supervised by ESUT faculty, handle your enquiry with complete confidentiality. No fees. No account needed.</p>
                <div class="hero-ctas">
                    <a href="{{ route('enquiry.create') }}" class="hero-btn-primary">
                        Get Free Legal Help
                        <svg width="12" height="12" viewBox="0 0 13 14" fill="none"><path d="M0.944 13.5C0.686 13.5 0.454 13.402 0.276 13.224C0.098 13.046 0 12.806 0 12.556C0 12.307 0.098 12.066 0.276 11.888L9.768 2.388H2.021C1.496 2.388 1.077 1.96 1.077 1.444C1.077 0.927 1.505 0.5 2.03 0.5H12.056C12.199 0.509 12.279 0.527L12.403 0.562L12.537 0.634C12.626 0.696 12.751 0.803 12.795 0.856L12.956 1.141L12.982 1.221C13 1.292 13.009 1.373 13.009 1.444V11.47C13.009 11.995 12.582 12.414 12.065 12.414C11.549 12.414 11.121 11.986 11.121 11.47V3.723L1.621 13.224C1.442 13.402 1.202 13.5 0.944 13.5Z" fill="currentColor"/></svg>
                    </a>
                    <a href="{{ route('enquiry.track') }}" class="hero-btn-ghost">Track My Case</a>
                    <a href="#faq-section" class="hero-btn-ghost">Legal Resources</a>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Slide 2 · Trained Student Advisors ── --}}
    <div class="hero-slide" :class="{ active: current === 1 }">
        <div class="hero-slide-bg" style="
            background-image:
                url('<?php echo asset('assets/img/hero/hero-2.webp'); ?>'),
                radial-gradient(ellipse at 50% 50%, rgba(74,13,0,0.6) 0%, transparent 70%),
                linear-gradient(135deg, #3a0800 0%, #6a1400 50%, #4a0d00 100%);
        "></div>
        <div class="hero-slide-overlay"></div>
        <div class="hero-slide-content">
            <div class="hero-slide-inner">
                <h1 class="hero-title">
                    Expert Advice<br>
                    <em>by Students,</em><br>
                    <span class="hero-title-gold">for Students</span>
                </h1>
                <p class="hero-subtitle">Our advisors are trained law students at ESUT, working under the direct supervision of experienced faculty to deliver quality legal guidance.</p>
                <div class="hero-ctas">
                    <a href="{{ route('about') }}" class="hero-btn-primary">Meet the Team</a>
                    <a href="{{ route('enquiry.create') }}" class="hero-btn-ghost">Submit an Enquiry</a>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Slide 3 · Excellence in Advocacy ── --}}
    <div class="hero-slide" :class="{ active: current === 2 }">
        <div class="hero-slide-bg" style="
            background-image:
                url('<?php echo asset('assets/img/hero/hero-3.webp'); ?>'),
                radial-gradient(ellipse at 50% 50%, rgba(74,13,0,0.6) 0%, transparent 70%),
                linear-gradient(135deg, #3a0800 0%, #6a1400 50%, #4a0d00 100%);
        "></div>
        <div class="hero-slide-overlay"></div>
        <div class="hero-slide-content">
            <div class="hero-slide-inner">
                <h1 class="hero-title">
                    Shaping<br>
                    <em>Tomorrow's</em><br>
                    <span class="hero-title-gold">Legal Minds</span>
                </h1>
                <p class="hero-subtitle">Through moot court competitions, legal aid clinics, and faculty mentorship, the ESUT Law Clinic builds the next generation of Nigerian legal practitioners.</p>
                <div class="hero-ctas">
                    <a href="{{ route('events.index') }}" class="hero-btn-primary">View Events</a>
                    <a href="{{ route('faq.index') }}" class="hero-btn-ghost">Legal Resources</a>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Slide 4 · Community Outreach ── --}}
    <div class="hero-slide" :class="{ active: current === 3 }">
        <div class="hero-slide-bg" style="
            background-image:
                url('<?php echo asset('assets/img/hero/hero-4.webp'); ?>'),
                radial-gradient(ellipse at 50% 50%, rgba(74,13,0,0.6) 0%, transparent 70%),
                linear-gradient(135deg, #3a0800 0%, #6a1400 50%, #4a0d00 100%);
        "></div>
        <div class="hero-slide-overlay"></div>
        <div class="hero-slide-content">
            <div class="hero-slide-inner">
                <h1 class="hero-title">
                    Justice<br>
                    <em>Belongs</em><br>
                    <span class="hero-title-gold">to Everyone</span>
                </h1>
                <p class="hero-subtitle">Beyond the campus — the ESUT Law Clinic extends free legal guidance to underserved communities across Enugu State, bridging the access-to-justice gap.</p>
                <div class="hero-ctas">
                    <a href="{{ route('enquiry.create') }}" class="hero-btn-primary">Get Free Help</a>
                    <a href="{{ route('contact.index') }}" class="hero-btn-ghost">Contact Us</a>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Prev / Next arrows ── --}}
    <button @click="prev()" class="carousel-arrow carousel-arrow-prev" aria-label="Previous slide">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    </button>
    <button @click="next()" class="carousel-arrow carousel-arrow-next" aria-label="Next slide">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
    </button>

    {{-- ── Dot indicators ── --}}
    <div class="carousel-dots">
        <template x-for="i in total" :key="i">
            <button class="carousel-dot" :class="{ active: current === i - 1 }" @click="go(i - 1)" :aria-label="'Slide ' + i"></button>
        </template>
    </div>

    {{-- ── Slide counter ── --}}
    <div class="hero-counter">
        <span x-text="String(current + 1).padStart(2,'0')"></span>
        <span style="color:rgba(255,255,255,0.18)"> / </span>
        <span x-text="String(total).padStart(2,'0')"></span>
    </div>

    {{-- ── Stats bar ── --}}
    <div class="hero-stats-bar">
        <div class="hero-stats-inner">
            <div class="hero-stat-item">
                <span class="hero-stat-num">{{ number_format(\App\Models\Enquiry::whereIn('status',['responded','closed'])->count() ?: 120) }}<sup>+</sup></span>
                <span class="hero-stat-cap">Cases Handled</span>
            </div>
            <div class="hero-stat-item">
                <span class="hero-stat-num">{{ \App\Models\TeamMember::active()->students()->count() ?: 24 }}</span>
                <span class="hero-stat-cap">Student Advisors</span>
            </div>
            <div class="hero-stat-item">
                <span class="hero-stat-num">{{ now()->year - 2015 }}<sup>+</sup></span>
                <span class="hero-stat-cap">Years Serving</span>
            </div>
            <div class="hero-stat-item">
                <span class="hero-stat-num">48<sup>h</sup></span>
                <span class="hero-stat-cap">Avg. Response</span>
            </div>
            <div class="hero-stat-item">
                <span class="hero-stat-num">Mon – Fri</span>
                <span class="hero-stat-cap">9 AM – 5 PM</span>
            </div>
        </div>
    </div>

</section>


{{-- ═══ HOW IT WORKS ════════════════════════════════════════════════════ --}}
<section class="how-section">
    <div class="container-inner max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
            <span class="section-eyebrow section-eyebrow-light">Simple Process</span>
            <h2 class="section-title section-title-light">How the <span>Clinic Works</span></h2>
            <div class="title-rule title-rule-light"><span></span><i></i><span></span></div>
            <p class="text-sm max-w-lg mx-auto" style="color:var(--text-mid)">Getting legal guidance is simple, free, and completely confidential. Three steps — no account, no fees.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-16">
            @foreach([
                ['01','Submit Your Enquiry','Fill in our short online form. You can remain anonymous for sensitive matters. Attach any relevant documents. Takes under 5 minutes.','M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z'],
                ['02','Advisors Review','A qualified student advisor, supervised by a faculty lecturer, reviews your matter and prepares a thorough legal response.','M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                ['03','Track & Receive','Use your reference code anytime to check progress. Once approved, your legal advice is delivered to your email.','M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4'],
            ] as $step)
            <div>
                <div class="feature-pill">
                    <div><div class="fp-icon">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $step[3] }}"/></svg>
                    </div></div>
                    <div>
                        <div class="fp-num">Step {{ $step[0] }}</div>
                        <div class="fp-title">{{ $step[1] }}</div>
                        <p class="fp-desc">{{ $step[2] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="max-w-4xl mx-auto">
            <div class="stats-strip">
                <div class="stat-item">
                    <span class="stat-big">{{ number_format(\App\Models\Enquiry::whereIn('status',['responded','closed'])->count() ?: 120) }}<sup>+</sup></span>
                    <span class="stat-cap">Cases Handled</span>
                </div>
                <div class="stat-divider"></div>
                <div class="stat-item">
                    <span class="stat-big">{{ \App\Models\TeamMember::active()->students()->count() ?: 24 }}</span>
                    <span class="stat-cap">Student Advisors</span>
                </div>
                <div class="stat-divider"></div>
                <div class="stat-item">
                    <span class="stat-big">{{ now()->year - 2015 }}<sup>+</sup></span>
                    <span class="stat-cap">Years Serving</span>
                </div>
                <div class="stat-divider"></div>
                <div class="stat-item">
                    <span class="stat-big">48<sup>h</sup></span>
                    <span class="stat-cap">Avg. Response</span>
                </div>
            </div>
        </div>
    </div>
    <div class="how-cut"></div>
</section>


{{-- ═══ FAQ CATEGORIES ══════════════════════════════════════════════════ --}}
<section class="faq-section" id="faq-section">
    <div class="faq-bg"></div>
    <div class="faq-bg-grid"></div>
    <div class="faq-ring faq-ring-1"></div>
    <div class="faq-ring faq-ring-2"></div>
    <div class="inner max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 items-start">
            <div class="lg:sticky lg:top-28">
                <span class="section-eyebrow section-eyebrow-dark">Knowledge Base</span>
                <h2 class="section-title section-title-dark">Legal <em>Resources</em></h2>
                <div class="title-rule title-rule-dark text-left justify-start"><span></span><i></i><span></span></div>
                <p class="text-sm leading-relaxed mt-2" style="color:rgba(255,255,255,0.45)">Plain-language answers to common legal questions — written by law students, reviewed by faculty supervisors.</p>
                <a href="{{ route('faq.index') }}" class="inline-flex items-center gap-2 mt-8 text-sm font-semibold" style="color:var(--gold)">
                    View All Resources
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
            <div class="lg:col-span-2">
                <div class="faq-list">
                    @forelse($faqCategories as $idx => $cat)
                    <a href="{{ route('faq.category', $cat->slug) }}" class="faq-item">
                        <div class="faq-accent"></div>
                        <div class="faq-num"><span>{{ str_pad($idx + 1, 2, '0', STR_PAD_LEFT) }}</span></div>
                        <div class="faq-content">
                            <div class="faq-icon-wrap">{{ $cat->icon ?? '⚖️' }}</div>
                            <div class="faq-text">
                                <h3>{{ $cat->name }}</h3>
                                <p>{{ $cat->description }}</p>
                                <div class="faq-count">{{ $cat->publishedArticles->count() }} article{{ $cat->publishedArticles->count() !== 1 ? 's' : '' }}</div>
                            </div>
                        </div>
                        <div class="faq-arrow">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </div>
                    </a>
                    @empty
                    <p style="color:rgba(255,255,255,0.3)" class="text-sm">Legal resources are being prepared.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    <div class="faq-cut"></div>
</section>


{{-- ═══ GALLERY ════════════════════════════════════════════════════════ --}}
<section class="gallery-section">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <span class="section-eyebrow section-eyebrow-light">Life at the Clinic</span>
            <h2 class="section-title section-title-light">Activities & <span>Achievements</span></h2>
            <div class="title-rule title-rule-light"><span></span><i></i><span></span></div>
            <p class="text-sm max-w-md mx-auto" style="color:var(--text-mid)">A glimpse into the work, events, and milestones of the ESUT Law Clinic and Faculty of Law.</p>
        </div>
        <div class="gallery-grid">
            {{-- Replace each .gallery-placeholder with <img src="..." alt="..."> when real photos are ready --}}
            <div class="gallery-item">
                <div class="gallery-placeholder" style="height:340px">⚖️</div>
                <div class="gallery-overlay"><span class="gallery-overlay-text">Legal Aid Clinic Session</span></div>
            </div>
            <div class="gallery-item">
                <div class="gallery-placeholder" style="height:200px">🎓</div>
                <div class="gallery-overlay"><span class="gallery-overlay-text">Student Advisor Training</span></div>
            </div>
            <div class="gallery-item">
                <div class="gallery-placeholder" style="height:360px">📚</div>
                <div class="gallery-overlay"><span class="gallery-overlay-text">Faculty Supervision Workshop</span></div>
            </div>
            <div class="gallery-item">
                <div class="gallery-placeholder" style="height:220px">🏛️</div>
                <div class="gallery-overlay"><span class="gallery-overlay-text">Moot Court Competition</span></div>
            </div>
            <div class="gallery-item">
                <div class="gallery-placeholder" style="height:270px">🤝</div>
                <div class="gallery-overlay"><span class="gallery-overlay-text">Community Outreach Programme</span></div>
            </div>
            <div class="gallery-item">
                <div class="gallery-placeholder" style="height:250px">🏆</div>
                <div class="gallery-overlay"><span class="gallery-overlay-text">Law Clinic Awards Ceremony</span></div>
            </div>
        </div>
    </div>
</section>


{{-- ═══ SUPERVISORS ══════════════════════════════════════════════════════ --}}
@if($lecturers->isNotEmpty())
<section class="team-section relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <span class="section-eyebrow section-eyebrow-light">Faculty Oversight</span>
            <h2 class="section-title section-title-light">Supervising <span>Lecturers</span></h2>
            <div class="title-rule title-rule-light"><span></span><i></i><span></span></div>
            <p class="text-sm max-w-md mx-auto" style="color:var(--text-mid)">All legal advice from the clinic is reviewed and approved by qualified faculty members before dispatch.</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($lecturers as $lecturer)
            <div class="lecturer-card">
                <div class="lecturer-photo"><img src="{{ $lecturer->photo_url }}" alt="{{ $lecturer->name }}"></div>
                <div>
                    <div class="lecturer-name">{{ $lecturer->name }}</div>
                    <div class="lecturer-role">{{ $lecturer->role }}</div>
                    @if($lecturer->bio)<div class="lecturer-bio">{{ Str::limit($lecturer->bio, 80) }}</div>@endif
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-10">
            <a href="{{ route('about') }}" class="text-sm font-semibold" style="color:var(--crimson)">Meet the full team →</a>
        </div>
    </div>
    <div class="team-cut absolute bottom-0 left-0 right-0" style="height:70px"></div>
</section>
@endif


{{-- ═══ EVENTS ═══════════════════════════════════════════════════════════ --}}
@if($upcomingEvents->isNotEmpty())
<section class="events-section">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between mb-12">
            <div>
                <span class="section-eyebrow section-eyebrow-light">Upcoming</span>
                <h2 class="section-title section-title-light">Events & <span>Outreach</span></h2>
                <div class="title-rule title-rule-light justify-start text-left"><span></span><i></i><span></span></div>
            </div>
            <a href="{{ route('events.index') }}" class="hidden sm:flex items-center gap-1 text-sm font-semibold" style="color:var(--crimson)">
                All Events <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($upcomingEvents as $event)
            <a href="{{ route('events.show', $event->slug) }}" class="event-card">
                <div class="event-card-head">
                    <div class="event-date-block">
                        <div class="event-day">{{ $event->event_date->format('d') }}</div>
                        <div class="event-month">{{ $event->event_date->format('M Y') }}</div>
                    </div>
                    <div>
                        <div class="event-card-title">{{ $event->title }}</div>
                        @if($event->location)<div class="event-card-loc">📍 {{ $event->location }}</div>@endif
                    </div>
                </div>
                <div class="event-card-body">
                    <p class="event-card-desc">{{ strip_tags($event->description) }}</p>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif


{{-- ═══ MARQUEE ══════════════════════════════════════════════════════════ --}}
<section class="marquee-section">
    <div class="marquee-track">
        <div class="marquee-inner">
            @foreach(array_fill(0, 2, null) as $_)
                <span class="mq-item">ESUT Law Clinic</span><span class="mq-dot"></span>
                <span class="mq-item">Free Legal Guidance</span><span class="mq-dot"></span>
                <span class="mq-item">Submit Your Enquiry Today</span><span class="mq-dot"></span>
                <span class="mq-item">Confidential & No Cost</span><span class="mq-dot"></span>
                <span class="mq-item">Faculty of Law · ESUT</span><span class="mq-dot"></span>
                <span class="mq-item">Know Your Rights</span><span class="mq-dot"></span>
                <span class="mq-item">Agbani, Enugu State</span><span class="mq-dot"></span>
            @endforeach
        </div>
    </div>
</section>


{{-- ═══ BOTTOM CTA ═══════════════════════════════════════════════════════ --}}
<section class="cta-section">
    <div class="max-w-4xl mx-auto px-4 sm:px-6">
        <div class="cta-inner">
            <div class="content">
                <h2 class="cta-title">Do you have a legal question?</h2>
                <p class="cta-desc max-w-xl mx-auto">
                    Don't navigate the law alone. Our student advisors — supervised by faculty — are ready to help. Free, confidential, and reference-tracked.
                </p>
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="{{ route('enquiry.create') }}" class="cta-btn-primary">
                        Submit Your Enquiry
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    </a>
                    <a href="{{ route('faq.index') }}" class="cta-btn-ghost">Browse Legal Resources</a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection