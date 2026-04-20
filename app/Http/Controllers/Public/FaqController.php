<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\FaqCategory;
use App\Models\FaqArticle;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index(Request $request)
    {
        $categories = FaqCategory::active()->with('publishedArticles')->get();

        $search    = $request->get('q');
        $articles  = null;

        if ($search) {
            $articles = FaqArticle::published()
                ->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%")
                          ->orWhere('content', 'like', "%{$search}%")
                          ->orWhere('excerpt', 'like', "%{$search}%");
                })
                ->with('category')
                ->paginate(12)
                ->withQueryString();
        }

        return view('faq.index', compact('categories', 'search', 'articles'));
    }

    public function show(FaqCategory $category, FaqArticle $article)
    {
        abort_if(!$article->is_published, 404);
        abort_if($article->faq_category_id !== $category->id, 404);

        $article->incrementViews();

        $related = FaqArticle::published()
            ->where('faq_category_id', $category->id)
            ->where('id', '!=', $article->id)
            ->take(4)
            ->get();

        return view('faq.show', compact('category', 'article', 'related'));
    }

    public function category(FaqCategory $category)
    {
        abort_if(!$category->is_active, 404);

        $articles = $category->publishedArticles()->paginate(15);

        return view('faq.category', compact('category', 'articles'));
    }

    /**
     * AJAX: thumbs up/down feedback on an article
     */
    public function feedback(Request $request, FaqArticle $article)
    {
        $request->validate(['type' => 'required|in:yes,no']);

        if ($request->type === 'yes') {
            $article->increment('helpful_yes');
        } else {
            $article->increment('helpful_no');
        }

        return response()->json(['message' => 'Thank you for your feedback!']);
    }
}
