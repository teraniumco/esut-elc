@extends('portal.layout')
@section('title', $category ? 'Edit Category' : 'New Category')
@section('page-title', $category ? 'Edit Category' : 'New Category')
@section('page-subtitle', $category ? $category->name : 'Add a new FAQ category to the Legal Resources page')

@section('content')

<div class="flex items-center gap-3 mb-5">
    <a href="{{ route('portal.admin.faq.index') }}" class="btn-ghost btn-sm">← Legal Resources</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <div class="portal-card">
            <form method="POST"
                  action="{{ $category ? route('portal.admin.faq.categories.update', $category) : route('portal.admin.faq.categories.store') }}"
                  class="space-y-5">
                @csrf
                @if($category) @method('PUT') @endif

                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                    <div>
                        <label class="form-label">Icon <span class="font-normal" style="color:var(--text-light)">(emoji)</span></label>
                        <input type="text" name="icon" class="form-input text-2xl text-center"
                               value="{{ old('icon', $category->icon ?? '') }}" placeholder="⚖️" maxlength="4">
                        @error('icon') <p class="form-hint text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div class="sm:col-span-3">
                        <label class="form-label">Category Name</label>
                        <input type="text" name="name" class="form-input @error('name') border-red-400 @enderror" required
                               value="{{ old('name', $category->name ?? '') }}" placeholder="e.g. Police & Your Rights">
                        @error('name') <p class="form-hint text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="form-label">Description <span class="font-normal" style="color:var(--text-light)">(shown as a subtitle under the category name)</span></label>
                    <textarea name="description" class="form-input" rows="2"
                              placeholder="A brief sentence describing this category...">{{ old('description', $category->description ?? '') }}</textarea>
                    @error('description') <p class="form-hint text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center gap-3 pt-2 border-t" style="border-color:var(--border)">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" class="w-4 h-4 rounded accent-crimson"
                               {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}>
                        <span class="text-sm font-medium" style="color:var(--text)">Visible on Legal Resources page</span>
                    </label>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit" class="btn-crimson">{{ $category ? 'Save Changes' : 'Create Category' }}</button>
                    <a href="{{ route('portal.admin.faq.index') }}" class="btn-ghost">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <div class="space-y-4">
        <div class="portal-card">
            <h4 class="text-xs font-bold uppercase tracking-wider mb-3" style="color:var(--text-light)">Tips</h4>
            <ul class="space-y-2 text-sm" style="color:var(--text-mid)">
                <li>Use a simple emoji as the icon — it appears next to the category name on the public page.</li>
                <li>The URL slug is auto-generated from the name and cannot be edited directly (to avoid breaking existing links).</li>
                <li>Hidden categories are not shown to the public but their articles remain in the database.</li>
            </ul>
        </div>
        @if($category)
        <div class="portal-card">
            <h4 class="text-xs font-bold uppercase tracking-wider mb-3" style="color:var(--text-light)">Category Info</h4>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span style="color:var(--text-light)">Slug</span>
                    <code class="text-xs bg-gray-100 px-2 py-0.5 rounded">{{ $category->slug }}</code>
                </div>
                <div class="flex justify-between">
                    <span style="color:var(--text-light)">Articles</span>
                    <span style="color:var(--text)">{{ $category->articles()->count() }}</span>
                </div>
                <div class="flex justify-between">
                    <span style="color:var(--text-light)">Created</span>
                    <span style="color:var(--text)">{{ $category->created_at->format('d M Y') }}</span>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

@endsection
