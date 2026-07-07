@extends('layouts.app')
@section('title', 'Legal Resources')
@section('meta_description', 'Browse free legal resources and answers to common legal questions, written in plain language by ESUT Law Clinic.')

@section('content')
<div class="bg-crimson py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 text-center">
        <h1 class="font-serif text-3xl sm:text-4xl font-bold text-white mb-4">Legal Resources</h1>
        <p class="text-gray-300 mb-8">Plain-language answers to common legal questions, written by law students and reviewed by faculty.</p>

        {{-- Search bar --}}
        <form action="{{ route('faq.index') }}" method="GET" class="flex gap-3 max-w-xl mx-auto">
            <input type="text" name="q" value="{{ $search }}" placeholder="Search legal questions..."
                   class="flex-1 border-0 rounded-xl px-5 py-3.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold/50 text-gray-900">
            <button type="submit" class="bg-gold text-navy font-semibold px-5 py-3 rounded-xl hover:bg-gold/90 transition-colors text-sm">
                Search
            </button>
        </form>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">

    {{-- SEARCH RESULTS --}}
    @if($search)
        <div class="mb-8 flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Search results for <strong>"{{ $search }}"</strong></p>
                @if($articles->total())
                    <p class="text-xs text-gray-400 mt-0.5">{{ $articles->total() }} article{{ $articles->total() !== 1 ? 's' : '' }} found</p>
                @endif
            </div>
            <a href="{{ route('faq.index') }}" class="text-sm text-navy hover:text-gold transition-colors">Clear search</a>
        </div>

        @if($articles->isNotEmpty())
            <div class="space-y-4 mb-10">
                @foreach($articles as $article)
                <a href="{{ route('faq.show', [$article->category->slug, $article->slug]) }}"
                   class="group block bg-white border border-gray-100 rounded-xl p-5 hover:border-navy/20 hover:shadow-sm transition-all">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <span class="text-xs text-gold font-semibold uppercase tracking-wide">{{ $article->category->name }}</span>
                            <h3 class="font-semibold text-navy group-hover:text-gold transition-colors mt-1">{{ $article->title }}</h3>
                            @if($article->excerpt)
                            <p class="text-gray-500 text-sm mt-1.5 line-clamp-2">{{ $article->excerpt }}</p>
                            @endif
                        </div>
                        <svg class="w-5 h-5 text-gray-300 group-hover:text-navy flex-shrink-0 mt-1 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </div>
                </a>
                @endforeach
            </div>
            {{ $articles->links() }}
        @else
            <div class="text-center py-16 bg-gray-50 rounded-2xl">
                <svg class="w-10 h-10 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-gray-500 font-medium">No articles found for "{{ $search }}"</p>
                <p class="text-gray-400 text-sm mt-1">Try different keywords or <a href="{{ route('enquiry.create') }}" class="text-navy hover:underline">submit a direct enquiry</a>.</p>
            </div>
        @endif

    {{-- CATEGORY BROWSING --}}
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($categories as $cat)
            <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-sm">
                {{-- Category header --}}
                <a href="{{ route('faq.category', $cat->slug) }}"
                   class="group block bg-crimson/5 hover:bg-crimson/10 px-5 py-4 transition-colors">
                    <div class="flex items-center gap-3">
                        <span class="text-2xl">{{ $cat->icon ?? '⚖️' }}</span>
                        <div>
                            <h2 class="font-semibold text-navy group-hover:text-gold transition-colors">{{ $cat->name }}</h2>
                            <p class="text-xs text-gray-500">{{ $cat->publishedArticles->count() }} article{{ $cat->publishedArticles->count() !== 1 ? 's' : '' }}</p>
                        </div>
                    </div>
                </a>
                {{-- Article list --}}
                <div class="divide-y divide-gray-50">
                    @forelse($cat->publishedArticles->take(4) as $article)
                    <a href="{{ route('faq.show', [$cat->slug, $article->slug]) }}"
                       class="group flex items-center justify-between px-5 py-3.5 hover:bg-gray-50 transition-colors">
                        <span class="text-sm text-gray-700 group-hover:text-navy transition-colors line-clamp-1 pr-2">
                            {{ $article->title }}
                        </span>
                        <svg class="w-4 h-4 text-gray-300 group-hover:text-navy flex-shrink-0 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                    @empty
                    <p class="px-5 py-4 text-sm text-gray-400 italic">No articles published yet.</p>
                    @endforelse
                    @if($cat->publishedArticles->count() > 4)
                    <a href="{{ route('faq.category', $cat->slug) }}"
                       class="block px-5 py-3 text-xs text-gold font-semibold hover:bg-gold/5 transition-colors">
                        View all {{ $cat->publishedArticles->count() }} articles →
                    </a>
                    @endif
                </div>
            </div>
            @empty
            <div class="col-span-3 text-center py-16 text-gray-400">
                <p>Legal resources are being prepared. Check back soon.</p>
            </div>
            @endforelse
        </div>
    @endif

    {{-- Bottom CTA --}}
    <div class="mt-16 bg-crimson rounded-2xl p-8 text-center">
        <h3 class="font-serif text-xl font-bold text-white mb-2">Can't find what you're looking for?</h3>
        <p class="text-gray-300 text-sm mb-5">Submit a direct enquiry and our student advisors will respond personally.</p>
        <a href="{{ route('enquiry.create') }}" class="inline-flex items-center gap-2 bg-gold text-navy font-bold px-6 py-3 rounded-xl hover:bg-gold/90 transition-colors">
            Submit a Free Enquiry
        </a>
    </div>
</div>
@endsection
