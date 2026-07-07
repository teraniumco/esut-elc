@extends('portal.layout')
@section('title', $article ? 'Edit Article' : 'New Article')
@section('page-title', $article ? 'Edit Article' : 'New Article')
@section('page-subtitle', $article ? Str::limit($article->title, 60) : 'Add a new legal resource article')

@push('styles')
<style>
/* Simple rich-text toolbar */
.editor-toolbar {
    display: flex; flex-wrap: wrap; gap: 4px;
    padding: 8px 10px; border: 1px solid var(--border);
    border-bottom: none; border-radius: 8px 8px 0 0;
    background: var(--off-white);
}
.editor-btn {
    padding: 4px 10px; font-size: 12px; font-weight: 600;
    border: 1px solid var(--border); border-radius: 5px;
    background: #fff; cursor: pointer; color: var(--text-mid);
    transition: background 0.15s, color 0.15s;
}
.editor-btn:hover { background: var(--crimson-light); color: var(--crimson); border-color: var(--crimson); }
.editor-sep { width: 1px; background: var(--border); margin: 2px 2px; align-self: stretch; }
.content-editor {
    width: 100%; min-height: 400px; padding: 16px;
    border: 1px solid var(--border); border-radius: 0 0 8px 8px;
    font-size: 14px; line-height: 1.75; outline: none;
    color: var(--text); background: #fff;
    font-family: 'DM Sans', sans-serif;
}
.content-editor:focus { border-color: var(--crimson); }
.content-editor h2 { font-family: 'DM Serif Display', serif; font-size: 1.2rem; color: var(--crimson); margin: 1.2rem 0 0.5rem; font-weight: 400; }
.content-editor h3 { font-size: 1rem; font-weight: 600; color: var(--text); margin: 1rem 0 0.4rem; }
.content-editor p  { margin-bottom: 0.75rem; }
.content-editor ul { list-style: disc; margin-left: 1.25rem; margin-bottom: 0.75rem; }
.content-editor ol { list-style: decimal; margin-left: 1.25rem; margin-bottom: 0.75rem; }
.content-editor strong { font-weight: 700; }
.content-editor a { color: var(--crimson); text-decoration: underline; }
</style>
@endpush

@section('content')

<div class="flex items-center gap-3 mb-5">
    <a href="{{ route('portal.admin.faq.articles.index', $category) }}" class="btn-ghost btn-sm">← {{ $category->name }}</a>
</div>

<form method="POST"
      action="{{ $article ? route('portal.admin.faq.articles.update', [$category, $article]) : route('portal.admin.faq.articles.store', $category) }}"
      id="article-form">
    @csrf
    @if($article) @method('PUT') @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- ── Main content ── --}}
        <div class="lg:col-span-2 space-y-5">
            <div class="portal-card">
                <div class="space-y-4">
                    <div>
                        <label class="form-label">Article Title</label>
                        <input type="text" name="title" class="form-input @error('title') border-red-400 @enderror" required
                               value="{{ old('title', $article->title ?? '') }}"
                               placeholder="e.g. What are my rights when the police stop me?">
                        @error('title') <p class="form-hint text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Excerpt <span class="font-normal" style="color:var(--text-light)">(short summary shown on the category page)</span></label>
                        <textarea name="excerpt" class="form-input" rows="2"
                                  placeholder="A one-sentence plain-language summary of this article...">{{ old('excerpt', $article->excerpt ?? '') }}</textarea>
                        @error('excerpt') <p class="form-hint text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="portal-card">
                <label class="form-label mb-2 block">Article Content</label>

                {{-- Toolbar --}}
                <div class="editor-toolbar">
                    <button type="button" class="editor-btn" onclick="fmt('bold')"><strong>B</strong></button>
                    <button type="button" class="editor-btn" onclick="fmt('italic')"><em>I</em></button>
                    <div class="editor-sep"></div>
                    <button type="button" class="editor-btn" onclick="insertBlock('h2')">H2</button>
                    <button type="button" class="editor-btn" onclick="insertBlock('h3')">H3</button>
                    <button type="button" class="editor-btn" onclick="insertBlock('p')">¶</button>
                    <div class="editor-sep"></div>
                    <button type="button" class="editor-btn" onclick="fmt('insertUnorderedList')">• List</button>
                    <button type="button" class="editor-btn" onclick="fmt('insertOrderedList')">1. List</button>
                    <div class="editor-sep"></div>
                    <button type="button" class="editor-btn" onclick="insertLink()">Link</button>
                </div>

                {{-- Editable div --}}
                <div id="content-editor" class="content-editor" contenteditable="true">{!! old('content', $article->content ?? '') !!}</div>
                <input type="hidden" name="content" id="content-hidden">

                @error('content') <p class="form-hint text-red-500 mt-1">{{ $message }}</p> @enderror
                <p class="text-xs mt-2" style="color:var(--text-light)">Use H2 for main sections, H3 for sub-sections. Bold for key terms. Bullet lists for rights or steps.</p>
            </div>
        </div>

        {{-- ── Sidebar ── --}}
        <div class="space-y-5">
            <div class="portal-card">
                <h3 class="text-sm font-semibold mb-4" style="color:var(--text)">Publish</h3>
                <div class="space-y-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="hidden" name="is_published" value="0">
                        <input type="checkbox" name="is_published" value="1" id="is_published"
                               class="w-4 h-4 rounded accent-crimson"
                               {{ old('is_published', $article->is_published ?? false) ? 'checked' : '' }}>
                        <div>
                            <span class="text-sm font-medium" style="color:var(--text)">Published</span>
                            <p class="text-xs" style="color:var(--text-light)">Visible to the public when checked</p>
                        </div>
                    </label>
                </div>
                <div class="mt-4 pt-4 border-t flex gap-2" style="border-color:var(--border)">
                    <button type="submit" class="btn-crimson flex-1 justify-center">Save</button>
                    <a href="{{ route('portal.admin.faq.articles.index', $category) }}" class="btn-ghost">Cancel</a>
                </div>
            </div>

            <div class="portal-card">
                <h3 class="text-sm font-semibold mb-4" style="color:var(--text)">Category</h3>
                <select name="faq_category_id" class="form-input">
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}"
                        {{ old('faq_category_id', $article->faq_category_id ?? $category->id) == $cat->id ? 'selected' : '' }}>
                        {{ $cat->icon ?? '⚖️' }} {{ $cat->name }}
                    </option>
                    @endforeach
                </select>
                <p class="text-xs mt-2" style="color:var(--text-light)">Move this article to a different category if needed.</p>
            </div>

            @if($article)
            <div class="portal-card">
                <h3 class="text-sm font-semibold mb-3" style="color:var(--text)">Stats</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span style="color:var(--text-light)">Views</span>
                        <span style="color:var(--text)">{{ number_format($article->views) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span style="color:var(--text-light)">Helpful</span>
                        <span style="color:var(--text)">{{ $article->helpful_yes }} / {{ $article->helpful_yes + $article->helpful_no }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span style="color:var(--text-light)">Last updated</span>
                        <span style="color:var(--text)">{{ $article->updated_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
            @endif
        </div>

    </div>
</form>

@push('scripts')
<script>
// Sync contenteditable → hidden input before submit
document.getElementById('article-form').addEventListener('submit', function() {
    document.getElementById('content-hidden').value = document.getElementById('content-editor').innerHTML;
});

function fmt(cmd) {
    document.getElementById('content-editor').focus();
    document.execCommand(cmd, false, null);
}

function insertBlock(tag) {
    document.getElementById('content-editor').focus();
    const sel = window.getSelection();
    if (!sel.rangeCount) return;
    const range = sel.getRangeAt(0);
    const el = document.createElement(tag);
    el.innerHTML = sel.toString() || (tag === 'p' ? 'Paragraph text...' : 'Heading text...');
    range.deleteContents();
    range.insertNode(el);
    // Move cursor after inserted element
    range.setStartAfter(el);
    range.collapse(true);
    sel.removeAllRanges();
    sel.addRange(range);
}

function insertLink() {
    const url = prompt('Enter URL:');
    if (url) {
        document.getElementById('content-editor').focus();
        document.execCommand('createLink', false, url);
    }
}
</script>
@endpush

@endsection
