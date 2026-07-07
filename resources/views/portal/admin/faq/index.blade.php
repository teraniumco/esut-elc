@extends('portal.layout')
@section('title', 'Legal Resources')
@section('page-title', 'Legal Resources')
@section('page-subtitle', 'Manage FAQ categories and articles shown on the public site')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div class="flex gap-3">
        <a href="{{ route('portal.admin.faq.categories.create') }}" class="btn-crimson">+ New Category</a>
    </div>
</div>

@if($categories->isEmpty())
<div class="portal-card text-center py-16">
    <div class="text-4xl mb-4">📚</div>
    <h3 class="text-lg font-semibold mb-2" style="color:var(--text)">No categories yet</h3>
    <p class="text-sm mb-5" style="color:var(--text-light)">Create your first FAQ category to start adding legal resources.</p>
    <a href="{{ route('portal.admin.faq.categories.create') }}" class="btn-crimson">Create First Category</a>
</div>
@else
<div class="portal-card overflow-hidden p-0">
    <table class="w-full text-sm">
        <thead class="border-b" style="border-color:var(--border);background:var(--off-white)">
            <tr>
                <th class="text-left px-5 py-3 text-xs font-semibold" style="color:var(--text-light)">Category</th>
                <th class="text-left px-4 py-3 text-xs font-semibold hidden md:table-cell" style="color:var(--text-light)">Description</th>
                <th class="text-center px-4 py-3 text-xs font-semibold" style="color:var(--text-light)">Articles</th>
                <th class="text-center px-4 py-3 text-xs font-semibold hidden sm:table-cell" style="color:var(--text-light)">Published</th>
                <th class="text-center px-4 py-3 text-xs font-semibold" style="color:var(--text-light)">Status</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody>
        @foreach($categories as $category)
        <tr class="border-b hover:bg-gray-50" style="border-color:var(--border)">
            <td class="px-5 py-3">
                <div class="flex items-center gap-2">
                    <span class="text-xl">{{ $category->icon ?? '⚖️' }}</span>
                    <div>
                        <div class="font-semibold" style="color:var(--text)">{{ $category->name }}</div>
                        <div class="text-xs font-mono" style="color:var(--text-light)">{{ $category->slug }}</div>
                    </div>
                </div>
            </td>
            <td class="px-4 py-3 hidden md:table-cell" style="color:var(--text-mid)">
                {{ Str::limit($category->description, 70) }}
            </td>
            <td class="px-4 py-3 text-center">
                <a href="{{ route('portal.admin.faq.articles.index', $category) }}"
                   class="inline-flex items-center gap-1 font-semibold text-sm hover:underline" style="color:var(--crimson)">
                    {{ $category->articles_count }}
                </a>
            </td>
            <td class="px-4 py-3 text-center hidden sm:table-cell">
                <span class="text-sm font-semibold" style="color:var(--text)">{{ $category->published_articles_count }}</span>
            </td>
            <td class="px-4 py-3 text-center">
                <span class="text-xs px-2 py-0.5 rounded-full font-semibold"
                      style="{{ $category->is_active ? 'background:#f0fdf4;color:#15803d' : 'background:#f9fafb;color:#9ca3af' }}">
                    {{ $category->is_active ? 'Active' : 'Hidden' }}
                </span>
            </td>
            <td class="px-4 py-3">
                <div class="flex items-center justify-end gap-2">
                    <a href="{{ route('portal.admin.faq.articles.index', $category) }}" class="btn-ghost btn-sm">Articles</a>
                    <a href="{{ route('portal.admin.faq.categories.edit', $category) }}" class="btn-ghost btn-sm">Edit</a>
                    <form method="POST" action="{{ route('portal.admin.faq.categories.destroy', $category) }}"
                          onsubmit="return confirm('Delete category? This cannot be undone.')">
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
