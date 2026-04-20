@extends('layouts.app')
@section('title', 'About the Clinic')

@section('content')
<div class="bg-crimson py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 text-center">
        <h1 class="font-serif text-3xl sm:text-4xl font-bold text-white mb-4">About ESUT Law Clinic</h1>
        <p class="text-gray-300 max-w-2xl mx-auto">A pro bono legal initiative of the Faculty of Law, Enugu State University of Science and Technology — bridging the gap between legal knowledge and the community.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">

    {{-- Mission & Vision --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-16">
        <div class="bg-white border border-gray-100 rounded-2xl p-8 shadow-sm">
            <div class="w-10 h-10 bg-crimson/5 rounded-xl flex items-center justify-center mb-5 text-xl">🎯</div>
            <h2 class="font-serif text-xl font-bold text-crimson mb-3">Our Mission</h2>
            <p class="text-gray-600 leading-relaxed">To provide accessible, free, and confidential legal guidance to members of the ESUT community and the broader public — empowering individuals to understand and exercise their legal rights, while giving law students practical clinical experience.</p>
        </div>
        <div class="bg-white border border-gray-100 rounded-2xl p-8 shadow-sm">
            <div class="w-10 h-10 bg-crimson/5 rounded-xl flex items-center justify-center mb-5 text-xl">🌟</div>
            <h2 class="font-serif text-xl font-bold text-crimson mb-3">Our Vision</h2>
            <p class="text-gray-600 leading-relaxed">A society where every person — regardless of economic standing — has access to basic legal information and the ability to assert their rights. We envision the clinic as the leading student-run legal aid initiative in South-East Nigeria.</p>
        </div>
    </div>

    {{-- Lecturers --}}
    @if($lecturers->isNotEmpty())
    <section class="mb-16">
        <div class="mb-8">
            <h2 class="font-serif text-2xl font-bold text-crimson mb-2">Faculty Supervisors</h2>
            <p class="text-gray-500">All clinic responses are reviewed and approved by qualified members of faculty.</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($lecturers as $lecturer)
            <div class="bg-white border border-gray-100 rounded-2xl p-6 flex gap-4 shadow-sm">
                <div class="w-16 h-16 rounded-full bg-crimson/10 overflow-hidden flex-shrink-0">
                    <img src="{{ $lecturer->photo_url }}" alt="{{ $lecturer->name }}" class="w-full h-full object-cover">
                </div>
                <div>
                    <p class="font-semibold text-crimson">{{ $lecturer->name }}</p>
                    <p class="text-sm text-gold font-medium">{{ $lecturer->role }}</p>
                    @if($lecturer->bio)
                    <p class="text-xs text-gray-500 mt-1.5 leading-relaxed">{{ Str::limit($lecturer->bio, 80) }}</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    {{-- Students --}}
    @if($students->isNotEmpty())
    <section class="mb-16">
        <div class="mb-8">
            <h2 class="font-serif text-2xl font-bold text-crimson mb-2">Student Advisors</h2>
            <p class="text-gray-500">Our dedicated team of law students who handle enquiries, research legal issues, and draft responses.</p>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-5">
            @foreach($students as $student)
            <div class="bg-white border border-gray-100 rounded-2xl p-5 text-center shadow-sm">
                <div class="w-14 h-14 rounded-full bg-crimson/10 overflow-hidden mx-auto mb-3">
                    <img src="{{ $student->photo_url }}" alt="{{ $student->name }}" class="w-full h-full object-cover">
                </div>
                <p class="font-medium text-crimson text-sm">{{ $student->name }}</p>
                <p class="text-xs text-gray-500 mt-0.5">{{ $student->role }}</p>
                @if($student->level)
                <span class="inline-block mt-1.5 text-xs bg-crimson/5 text-crimson px-2 py-0.5 rounded-full">{{ $student->level }}</span>
                @endif
            </div>
            @endforeach
        </div>
    </section>
    @endif

    {{-- Bottom CTA --}}
    <div class="bg-crimson rounded-2xl p-10 text-center">
        <h3 class="font-serif text-2xl font-bold text-white mb-3">Need Legal Guidance?</h3>
        <p class="text-gray-300 mb-7 max-w-lg mx-auto">Submit your enquiry today. Our team will review your matter and provide free, confidential guidance.</p>
        <a href="{{ route('enquiry.create') }}" class="inline-flex items-center gap-2 bg-gold text-crimson font-bold px-8 py-3.5 rounded-xl hover:bg-gold/90 transition-colors">
            Submit a Free Enquiry
        </a>
    </div>
</div>
@endsection
