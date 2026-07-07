@extends('layouts.app')
@section('title', 'About the Clinic')

@push('styles')
<style>
/* ── Faculty card (portrait style) ── */
.about-lecturer-card {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 20px;
    overflow: hidden;
    position: relative;
    transition: box-shadow 0.35s ease, transform 0.35s ease, border-color 0.35s ease;
}
.about-lecturer-card:hover {
    box-shadow: 0 20px 60px rgba(113,21,0,0.13);
    transform: translateY(-6px);
    border-color: rgba(113,21,0,0.18);
}
.about-lecturer-accent {
    position: absolute; top: 0; left: 0; right: 0; height: 3px; z-index: 2;
    background: linear-gradient(90deg, var(--crimson) 0%, var(--gold) 100%);
}
.about-lecturer-photo {
    position: relative;
    width: 100%;
    aspect-ratio: 3 / 3.5;
    overflow: hidden;
    background: linear-gradient(135deg, var(--crimson-light) 0%, #ede3de 100%);
}
.about-lecturer-photo img {
    width: 100%; height: 100%;
    object-fit: cover; object-position: top center;
    display: block;
    transition: transform 0.55s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}
.about-lecturer-card:hover .about-lecturer-photo img { transform: scale(1.04); }
.about-lecturer-overlay {
    position: absolute; inset: 0;
    background: linear-gradient(to top, rgba(74,13,0,0.72) 0%, rgba(74,13,0,0.1) 55%, transparent 100%);
    opacity: 0;
    transition: opacity 0.35s ease;
    display: flex; align-items: flex-end;
    padding: 22px 20px;
}
.about-lecturer-card:hover .about-lecturer-overlay { opacity: 1; }
.about-lecturer-overlay-bio {
    font-size: 12.5px; color: rgba(255,255,255,0.85);
    line-height: 1.6; transform: translateY(6px);
    transition: transform 0.35s ease;
}
.about-lecturer-card:hover .about-lecturer-overlay-bio { transform: translateY(0); }
.about-lecturer-placeholder {
    width: 100%; height: 100%;
    display: flex; align-items: center; justify-content: center;
}
.about-lecturer-initials {
    font-family: 'DM Serif Display', serif;
    font-size: 56px; font-weight: 400;
    color: rgba(113,21,0,0.18); line-height: 1;
}
.about-lecturer-body {
    padding: 20px 22px 22px;
    border-top: 1px solid var(--border);
    position: relative;
}
.about-lecturer-body::before {
    content: '';
    position: absolute; top: -1px; left: 22px;
    width: 36px; height: 2px;
    background: var(--crimson);
}
.about-lecturer-name {
    font-family: 'DM Serif Display', serif;
    font-size: 17px; font-weight: 400;
    color: var(--text); line-height: 1.2;
    margin-bottom: 5px;
}
.about-lecturer-role {
    font-size: 11px; font-weight: 700;
    color: var(--gold); letter-spacing: 0.8px;
    text-transform: uppercase;
}
.about-lecturer-dept {
    font-size: 11.5px; color: var(--text-light);
    margin-top: 6px; line-height: 1.5;
}

/* ── Student card (compact portrait) ── */
.about-student-card {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 16px;
    overflow: hidden;
    position: relative;
    transition: box-shadow 0.3s ease, transform 0.3s ease, border-color 0.3s ease;
}
.about-student-card:hover {
    box-shadow: 0 12px 40px rgba(113,21,0,0.11);
    transform: translateY(-5px);
    border-color: rgba(113,21,0,0.15);
}
.about-student-photo {
    position: relative;
    width: 100%;
    aspect-ratio: 1 / 1;
    overflow: hidden;
    background: linear-gradient(135deg, var(--crimson-light) 0%, #ede3de 100%);
}
.about-student-photo img {
    width: 100%; height: 100%;
    object-fit: cover; object-position: top center;
    display: block;
    transition: transform 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}
.about-student-card:hover .about-student-photo img { transform: scale(1.06); }
.about-student-photo-shimmer {
    position: absolute; inset: 0;
    background: linear-gradient(to top, rgba(74,13,0,0.35) 0%, transparent 60%);
    opacity: 0;
    transition: opacity 0.3s ease;
}
.about-student-card:hover .about-student-photo-shimmer { opacity: 1; }
.about-student-placeholder {
    width: 100%; height: 100%;
    display: flex; align-items: center; justify-content: center;
}
.about-student-initials {
    font-family: 'DM Serif Display', serif;
    font-size: 36px; font-weight: 400;
    color: rgba(113,21,0,0.2); line-height: 1;
}
.about-student-body {
    padding: 14px 16px 16px;
    border-top: 1px solid var(--border);
    text-align: center;
}
.about-student-name {
    font-size: 13.5px; font-weight: 700;
    color: var(--text); line-height: 1.25;
    margin-bottom: 3px;
}
.about-student-role {
    font-size: 11px; color: var(--text-light);
    line-height: 1.4;
}
.about-student-level {
    display: inline-block; margin-top: 7px;
    font-size: 10.5px; font-weight: 700;
    color: var(--crimson); letter-spacing: 0.5px;
    background: var(--crimson-light);
    padding: 3px 10px; border-radius: 20px;
}
</style>
@endpush

@section('content')
<div style="background:var(--crimson)" class="py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 text-center">
        <h1 class="font-serif text-3xl sm:text-4xl font-bold text-white mb-4">About ESUT Law Clinic</h1>
        <p class="text-gray-300 max-w-2xl mx-auto">A pro bono legal initiative of the Faculty of Law, Enugu State University of Science and Technology — bridging the gap between legal knowledge and the community.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">

    {{-- Mission & Vision --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-16">
        <div class="bg-white border border-gray-100 rounded-2xl p-8 shadow-sm">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-5 text-xl" style="background:var(--crimson-light)">🎯</div>
            <h2 class="font-serif text-xl font-bold mb-3" style="color:var(--crimson)">Our Mission</h2>
            <p class="text-gray-600 leading-relaxed">{{ $mission }}</p>
        </div>
        <div class="bg-white border border-gray-100 rounded-2xl p-8 shadow-sm">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-5 text-xl" style="background:var(--crimson-light)">🌟</div>
            <h2 class="font-serif text-xl font-bold mb-3" style="color:var(--crimson)">Our Vision</h2>
            <p class="text-gray-600 leading-relaxed">{{ $vision }}</p>
        </div>
    </div>

    {{-- ── Faculty Supervisors ── --}}
    @if($lecturers->isNotEmpty())
    <section class="mb-20">
        <div class="mb-10">
            <h2 class="font-serif text-2xl font-bold mb-2" style="color:var(--crimson)">Faculty Supervisors</h2>
            <p style="color:var(--text-light)">All clinic responses are reviewed and approved by qualified members of faculty before dispatch.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($lecturers as $lecturer)
            @php
                $initials = collect(explode(' ', $lecturer->name))
                    ->map(fn($w) => strtoupper($w[0] ?? ''))->take(2)->implode('');
            @endphp
            <div class="about-lecturer-card">
                <div class="about-lecturer-accent"></div>

                <div class="about-lecturer-photo">
                    @if($lecturer->photo_url)
                        <img src="{{ $lecturer->photo_url }}" alt="{{ $lecturer->name }}">
                    @else
                        <div class="about-lecturer-placeholder">
                            <span class="about-lecturer-initials">{{ $initials }}</span>
                        </div>
                    @endif

                    @if($lecturer->bio)
                    <div class="about-lecturer-overlay">
                        <p class="about-lecturer-overlay-bio">{{ Str::limit($lecturer->bio, 130) }}</p>
                    </div>
                    @endif
                </div>

                <div class="about-lecturer-body">
                    <div class="about-lecturer-name">{{ $lecturer->name }}</div>
                    <div class="about-lecturer-role">{{ $lecturer->role }}</div>
                    @if($lecturer->department ?? false)
                        <div class="about-lecturer-dept">{{ $lecturer->department }}</div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    {{-- ── Student Advisors ── --}}
    @if($students->isNotEmpty())
    <section class="mb-16">
        <div class="mb-10">
            <h2 class="font-serif text-2xl font-bold mb-2" style="color:var(--crimson)">Student Advisors</h2>
            <p style="color:var(--text-light)">Our dedicated team of law students who handle enquiries, research legal issues, and draft supervised responses.</p>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-5">
            @foreach($students as $student)
            @php
                $initials = collect(explode(' ', $student->name))
                    ->map(fn($w) => strtoupper($w[0] ?? ''))->take(2)->implode('');
            @endphp
            <div class="about-student-card">
                <div class="about-student-photo">
                    @if($student->photo_url)
                        <img src="{{ $student->photo_url }}" alt="{{ $student->name }}">
                    @else
                        <div class="about-student-placeholder">
                            <span class="about-student-initials">{{ $initials }}</span>
                        </div>
                    @endif
                    <div class="about-student-photo-shimmer"></div>
                </div>

                <div class="about-student-body">
                    <div class="about-student-name">{{ $student->name }}</div>
                    <div class="about-student-role">{{ $student->role }}</div>
                    @if($student->level)
                        <span class="about-student-level">{{ $student->level }}</span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    {{-- Bottom CTA --}}
    <div class="rounded-2xl p-10 text-center" style="background:var(--crimson)">
        <h3 class="font-serif text-2xl font-bold text-white mb-3">Need Legal Guidance?</h3>
        <p class="text-gray-300 mb-7 max-w-lg mx-auto">Submit your enquiry today. Our team will review your matter and provide free, confidential guidance.</p>
        <a href="{{ route('enquiry.create') }}"
           class="inline-flex items-center gap-2 font-bold px-8 py-3.5 rounded-xl transition-colors"
           style="background:var(--gold);color:var(--crimson-dark)">
            Submit a Free Enquiry
        </a>
    </div>
</div>
@endsection