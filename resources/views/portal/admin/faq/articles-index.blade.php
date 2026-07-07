@extends('portal.layout')
@section('title', $category->name . ' — Articles')
@section('page-title', $category->name)
@section('page-subtitle', 'Articles in this category')

@section('content')

<div class="flex items-center justify-between mb-5">
    <a href="{{ route('portal.admin.faq.index') }}" class="btn-ghost btn-sm">← All Categories</a>
    <a href="{{ route('portal.admin.faq.articles.create', $category) }}" class="btn-crimson">+ New Article</a>
</div>

<div class="flex items-center gap-3 mb-5 p-4 rounded-xl" style="background:var(--crimson-light);border:1px solid rgba(113,21,0,0.12)">
    <span class="text-3xl">{{ $category->icon ?? '⚖️' }}</span>
    <div>
        <div class="font-semibold" style="color:var(--crimson)">{{ $category->name }}</div>
        <div class="text-sm" style="color:var(--text-mid)">{{ $category->description }}</div>
    </div>
    <div class="ml-auto flex gap-2">
        <a href="{{ route('portal.admin.faq.categories.edit', $category) }}" class="btn-ghost btn-sm">Edit Category</a>
        <a href="{{ route('faq.category', $category->slug) }}" target="_blank" class="btn-ghost btn-sm">View Public ↗</a>
    </div>
</div>

@if($articles->isEmpty())
<div class="portal-card text-center py-14">
    <div class="text-3xl mb-3">📝</div>
    <p class="text-sm mb-4" style="color:var(--text-light)">No articles in this category yet.</p>
    <a href="{{ route('portal.admin.faq.articles.create', $category) }}" class="btn-crimson">Write First Article</a>
</div>
@else
<div class="portal-card overflow-hidden p-0">
    <table class="w-full text-sm">
        <thead class="border-b" style="border-color:var(--border);background:var(--off-white)">
            <tr>
                <th class="text-left px-5 py-3 text-xs font-semibold" style="color:var(--text-light)">Title</th>
                <th class="text-center px-4 py-3 text-xs font-semibold hidden sm:table-cell" style="color:var(--text-light)">Views</th>
                <th class="text-center px-4 py-3 text-xs font-semibold hidden md:table-cell" style="color:var(--text-light)">Helpful</th>
                <th class="text-center px-4 py-3 text-xs font-semibold" style="color:var(--text-light)">Status</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody>
        @foreach($articles as $article)
        <tr class="border-b hover:bg-gray-50" style="border-color:var(--border)">
            <td class="px-5 py-3">
                <div class="font-medium" style="color:var(--text)">{{ $article->title }}</div>
                @if($article->excerpt)
                <div class="text-xs mt-0.5 truncate max-w-xs" style="color:var(--text-light)">{{ $article->excerpt }}</div>
                @endif
            </td>
            <td class="px-4 py-3 text-center text-sm hidden sm:table-cell" style="color:var(--text-mid)">{{ $article->views }}</td>
            <td class="px-4 py-3 text-center hidden md:table-cell">
                @if($article->helpful_yes + $article->helpful_no > 0)
                <span class="text-xs font-semibold" style="color:var(--text)">{{ $article->helpful_percentage }}%</span>
                <span class="text-xs" style="color:var(--text-light)"> ({{ $article->helpful_yes }}/{{ $article->helpful_yes + $article->helpful_no }})</span>
                @else
                <span class="text-xs" style="color:var(--text-light)">—</span>
                @endif
            </td>
            <td class="px-4 py-3 text-center">
                <span class="text-xs px-2 py-0.5 rounded-full font-semibold"
                      style="{{ $article->is_published ? 'background:#f0fdf4;color:#15803d' : 'background:#fff7ed;color:#c2410c' }}">
                    {{ $article->is_published ? 'Published' : 'Draft' }}
                </span>
            </td>
            <td class="px-4 py-3">
                <div class="flex items-center justify-end gap-2">
                    <a href="{{ route('portal.admin.faq.articles.edit', [$category, $article]) }}" class="btn-ghost btn-sm">Edit</a>
                    <form method="POST" action="{{ route('portal.admin.faq.articles.destroy', [$category, $article]) }}"
                          onsubmit="return confirm('Delete this article?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-danger btn-sm">Delete</button>
                    </form>
                </div>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endif

@endsection
