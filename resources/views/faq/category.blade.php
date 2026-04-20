@extends('layouts.app')
@section('title', $category->name . ' — Legal Resources')

@section('content')
<div class="bg-crimson py-14">
    <div class="max-w-4xl mx-auto px-4 sm:px-6">
        <nav class="flex items-center gap-2 text-xs text-white/50 mb-5">
            <a href="{{ route('faq.index') }}" class="hover:text-white transition-colors">Legal Resources</a>
            <span>›</span>
            <span class="text-white/80">{{ $category->name }}</span>
        </nav>
        <div class="flex items-center gap-4">
            <span class="text-4xl">{{ $category->icon ?? '⚖️' }}</span>
            <div>
                <h1 class="font-serif text-2xl sm:text-3xl font-bold text-white">{{ $category->name }}</h1>
                @if($category->description)
                <p class="text-gray-300 mt-1">{{ $category->description }}</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <p class="text-sm text-gray-400 mb-6">{{ $articles->total() }} article{{ $articles->total() !== 1 ? 's' : '' }}</p>

    <div class="space-y-4">
        @forelse($articles as $article)
        <a href="{{ route('faq.show', [$category->slug, $article->slug]) }}"
           class="group block bg-white border border-gray-100 rounded-xl p-6 hover:border-crimson/20 hover:shadow-sm transition-all">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h2 class="font-semibold text-crimson group-hover:text-gold transition-colors">{{ $article->title }}</h2>
                    @if($article->excerpt)
                    <p class="text-gray-500 text-sm mt-2 line-clamp-2 leading-relaxed">{{ $article->excerpt }}</p>
                    @endif
                    <div class="flex items-center gap-4 mt-3 text-xs text-gray-400">
                        <span>{{ number_format($article->views) }} views</span>
                        @if($article->helpful_yes + $article->helpful_no > 0)
                        <span>{{ $article->helpful_percentage }}% found helpful</span>
                        @endif
                    </div>
                </div>
                <svg class="w-5 h-5 text-gray-300 group-hover:text-crimson flex-shrink-0 mt-1 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </div>
        </a>
        @empty
        <div class="text-center py-16 text-gray-400">No articles published in this category yet.</div>
        @endforelse
    </div>

    <div class="mt-8">{{ $articles->links() }}</div>

    <div class="mt-12 bg-crimson rounded-2xl p-8 text-center">
        <h3 class="font-serif text-xl font-bold text-white mb-2">Need specific guidance?</h3>
        <p class="text-gray-300 text-sm mb-5">Submit a free enquiry and our advisors will respond to your individual situation.</p>
        <a href="{{ route('enquiry.create') }}" class="inline-flex items-center gap-2 bg-gold text-crimson font-bold px-6 py-3 rounded-xl hover:bg-gold/90 transition-colors">
            Get Free Help
        </a>
    </div>
</div>
@endsection
