@extends('layouts.app')
@section('title', $article->title . ' — ' . $category->name)
@section('meta_description', $article->excerpt ?? Str::limit(strip_tags($article->content), 155))

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex gap-10">

        {{-- Main content --}}
        <article class="flex-1 min-w-0">

            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-2 text-xs text-gray-400 mb-6">
                <a href="{{ route('faq.index') }}" class="hover:text-navy transition-colors">Legal Resources</a>
                <span>›</span>
                <a href="{{ route('faq.category', $category->slug) }}" class="hover:text-navy transition-colors">{{ $category->name }}</a>
                <span>›</span>
                <span class="text-gray-600">{{ Str::limit($article->title, 50) }}</span>
            </nav>

            {{-- Article header --}}
            <header class="mb-8">
                <span class="inline-flex items-center gap-1.5 bg-crimson/5 text-navy text-xs font-semibold px-3 py-1 rounded-full mb-4">
                    {{ $category->icon ?? '⚖️' }} {{ $category->name }}
                </span>
                <h1 class="font-serif text-2xl sm:text-3xl font-bold text-navy leading-snug mb-3">{{ $article->title }}</h1>
                <div class="flex items-center gap-4 text-xs text-gray-400">
                    <span>Updated {{ $article->updated_at->format('d M Y') }}</span>
                    <span>{{ number_format($article->views) }} view{{ $article->views !== 1 ? 's' : '' }}</span>
                </div>
            </header>

            {{-- Article content --}}
            <div class="prose-legal text-gray-700 leading-relaxed">
                {!! $article->content !!}
            </div>

            {{-- Helpful feedback --}}
            <div class="mt-12 border-t border-gray-100 pt-8" x-data="{ voted: false, message: '' }">
                <p class="text-sm font-medium text-gray-700 mb-4">Was this article helpful?</p>
                <div class="flex gap-3" x-show="!voted">
                    <button @click="
                        fetch('{{ route('faq.feedback', $article->id) }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: JSON.stringify({ type: 'yes' })
                        })
                        .then(() => { voted = true; message = '👍 Thank you for your feedback!' });
                    "
                    class="flex items-center gap-2 border border-gray-200 text-gray-600 text-sm px-4 py-2 rounded-lg hover:border-green-400 hover:text-green-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.096l-.323 1.942A2 2 0 0118.442 16H16m-4-6V6a2 2 0 00-2-2h-1a1 1 0 00-1 1v1.5M12 10v6m0 0H8.528M12 16h3.472"/></svg>
                        Yes, helpful
                    </button>
                    <button @click="
                        fetch('{{ route('faq.feedback', $article->id) }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: JSON.stringify({ type: 'no' })
                        })
                        .then(() => { voted = true; message = '✍️ Thanks — we\'ll work to improve this article.' });
                    "
                    class="flex items-center gap-2 border border-gray-200 text-gray-600 text-sm px-4 py-2 rounded-lg hover:border-red-300 hover:text-red-600 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14H5.236a2 2 0 01-1.789-2.096l.323-1.942A2 2 0 015.558 8H8m4 6v2a2 2 0 002 2h1a1 1 0 001-1v-1.5M12 14V8m0 0H8.528M12 8H15.472"/></svg>
                        Not helpful
                    </button>
                </div>
                <p x-show="voted" x-text="message" class="text-sm text-gray-600 font-medium"></p>
            </div>

            {{-- Need more help --}}
            <div class="mt-10 bg-crimson/5 border border-navy/10 rounded-2xl p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <p class="font-semibold text-navy">Still need help with your specific situation?</p>
                    <p class="text-sm text-gray-500 mt-0.5">Submit a free enquiry and a student advisor will respond to your case personally.</p>
                </div>
                <a href="{{ route('enquiry.create') }}" class="flex-shrink-0 bg-crimson text-white text-sm font-semibold px-5 py-2.5 rounded-xl hover:bg-gold hover:text-navy transition-colors">
                    Get Free Help
                </a>
            </div>
        </article>

        {{-- Sidebar --}}
        <aside class="hidden lg:block w-64 flex-shrink-0">
            <div class="sticky top-24 space-y-6">
                @if($related->isNotEmpty())
                <div class="bg-gray-50 rounded-2xl p-5">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-4">Related Articles</h3>
                    <div class="space-y-3">
                        @foreach($related as $rel)
                        <a href="{{ route('faq.show', [$category->slug, $rel->slug]) }}"
                           class="group block text-sm text-gray-700 hover:text-navy transition-colors leading-snug">
                            {{ $rel->title }}
                        </a>
                        @endforeach
                    </div>
                    <a href="{{ route('faq.category', $category->slug) }}" class="mt-4 block text-xs text-gold font-semibold hover:underline">
                        All {{ $category->name }} articles →
                    </a>
                </div>
                @endif

                <div class="bg-crimson rounded-2xl p-5 text-center">
                    <p class="text-white text-sm font-semibold mb-2">Have a similar problem?</p>
                    <p class="text-gray-400 text-xs mb-4">Get personalized legal guidance for free.</p>
                    <a href="{{ route('enquiry.create') }}" class="block w-full bg-gold text-navy text-sm font-bold py-2.5 rounded-xl hover:bg-gold/90 transition-colors">
                        Submit Enquiry
                    </a>
                </div>
            </div>
        </aside>
    </div>
</div>
@endsection
