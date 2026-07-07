<?php

namespace App\Http\Controllers\Portal\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\FaqArticle;
use App\Models\FaqCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FaqAdminController extends Controller
{
    // ════════════════════════════════════════════════════════════════════
    // CATEGORIES
    // ════════════════════════════════════════════════════════════════════

    public function index()
    {
        $categories = FaqCategory::withCount(['articles', 'publishedArticles'])
            ->orderBy('sort_order')
            ->get();

        return view('portal.admin.faq.index', compact('categories'));
    }

    public function createCategory()
    {
        return view('portal.admin.faq.category-form', ['category' => null]);
    }

    public function storeCategory(Request $request)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:100'],
            'icon'        => ['nullable', 'string', 'max:10'],
            'description' => ['nullable', 'string', 'max:500'],
            'is_active'   => ['nullable', 'boolean'],
        ]);

        $data['slug']       = Str::slug($data['name']);
        $data['sort_order'] = FaqCategory::max('sort_order') + 1;
        $data['is_active']  = $request->boolean('is_active', true);

        // Ensure slug is unique
        $base = $data['slug'];
        $n = 1;
        while (FaqCategory::where('slug', $data['slug'])->exists()) {
            $data['slug'] = $base . '-' . $n++;
        }

        $category = FaqCategory::create($data);
        ActivityLog::record('faq.category_created', $category);

        return redirect()->route('portal.admin.faq.index')
            ->with('success', "Category \"{$category->name}\" created.");
    }

    public function editCategory(FaqCategory $category)
    {
        return view('portal.admin.faq.category-form', compact('category'));
    }

    public function updateCategory(Request $request, FaqCategory $category)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:100'],
            'icon'        => ['nullable', 'string', 'max:10'],
            'description' => ['nullable', 'string', 'max:500'],
            'is_active'   => ['nullable', 'boolean'],
        ]);

        // Regenerate slug only if name changed
        if ($data['name'] !== $category->name) {
            $base = Str::slug($data['name']);
            $slug = $base;
            $n = 1;
            while (FaqCategory::where('slug', $slug)->where('id', '!=', $category->id)->exists()) {
                $slug = $base . '-' . $n++;
            }
            $data['slug'] = $slug;
        }

        $data['is_active'] = $request->boolean('is_active');
        $category->update($data);
        ActivityLog::record('faq.category_updated', $category);

        return redirect()->route('portal.admin.faq.index')
            ->with('success', "Category \"{$category->name}\" updated.");
    }

    public function destroyCategory(FaqCategory $category)
    {
        if ($category->articles()->count() > 0) {
            return back()->with('error', "Cannot delete \"{$category->name}\" — it has articles. Delete the articles first, or move them to another category.");
        }

        $category->delete();
        ActivityLog::record('faq.category_deleted');

        return redirect()->route('portal.admin.faq.index')
            ->with('success', 'Category deleted.');
    }

    // ════════════════════════════════════════════════════════════════════
    // ARTICLES
    // ════════════════════════════════════════════════════════════════════

    public function articlesIndex(FaqCategory $category)
    {
        $articles = $category->articles()->orderBy('created_at', 'desc')->get();
        return view('portal.admin.faq.articles-index', compact('category', 'articles'));
    }

    public function createArticle(FaqCategory $category)
    {
        return view('portal.admin.faq.article-form', [
            'category' => $category,
            'article'  => null,
            'categories' => FaqCategory::orderBy('sort_order')->get(),
        ]);
    }

    public function storeArticle(Request $request, FaqCategory $category)
    {
        $data = $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'excerpt'      => ['nullable', 'string', 'max:500'],
            'content'      => ['required', 'string'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $base = Str::slug($data['title']);
        $slug = $base;
        $n = 1;
        while (FaqArticle::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $n++;
        }

        $article = $category->articles()->create([
            ...$data,
            'slug'         => $slug,
            'is_published' => $request->boolean('is_published'),
        ]);

        ActivityLog::record('faq.article_created', $article);

        return redirect()->route('portal.admin.faq.articles.index', $category)
            ->with('success', "Article \"{$article->title}\" created.");
    }

    public function editArticle(FaqCategory $category, FaqArticle $article)
    {
        $categories = FaqCategory::orderBy('sort_order')->get();
        return view('portal.admin.faq.article-form', compact('category', 'article', 'categories'));
    }

    public function updateArticle(Request $request, FaqCategory $category, FaqArticle $article)
    {
        $data = $request->validate([
            'faq_category_id' => ['required', 'exists:faq_categories,id'],
            'title'           => ['required', 'string', 'max:255'],
            'excerpt'         => ['nullable', 'string', 'max:500'],
            'content'         => ['required', 'string'],
            'is_published'    => ['nullable', 'boolean'],
        ]);

        // Regenerate slug if title changed
        if ($data['title'] !== $article->title) {
            $base = Str::slug($data['title']);
            $slug = $base;
            $n = 1;
            while (FaqArticle::where('slug', $slug)->where('id', '!=', $article->id)->exists()) {
                $slug = $base . '-' . $n++;
            }
            $data['slug'] = $slug;
        }

        $data['is_published'] = $request->boolean('is_published');
        $article->update($data);
        ActivityLog::record('faq.article_updated', $article);

        $newCategory = FaqCategory::find($data['faq_category_id']);

        return redirect()->route('portal.admin.faq.articles.index', $newCategory)
            ->with('success', "Article \"{$article->title}\" updated.");
    }

    public function destroyArticle(FaqCategory $category, FaqArticle $article)
    {
        $title = $article->title;
        $article->delete();
        ActivityLog::record('faq.article_deleted');

        return redirect()->route('portal.admin.faq.articles.index', $category)
            ->with('success', "Article \"{$title}\" deleted.");
    }
}
