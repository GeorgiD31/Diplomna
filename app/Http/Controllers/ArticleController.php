<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Category;
use App\Models\Source;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Carbon\Carbon;

class ArticleController extends Controller
{
    public function create()
    {
        $categories = Category::all();
        $sources = Source::all();
        return view('articles.create', compact('categories', 'sources'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255|unique:articles,title',
            'author'       => 'nullable|string|max:255',
            'description'  => 'required|string',
            'content'      => 'required|string',
            'url'          => 'required|url|max:2083',
            'url_to_image' => 'required|url|max:2083',
            'source_id'    => 'nullable|exists:sources,id',
            'published_at' => 'required|date',
            'categories'   => 'array',
            'categories.*' => 'exists:categories,id',
        ]);

        if ($request->filled('new_source')) {
            $newSource = Source::create(['name' => $request->new_source]);
            $validated['source_id'] = $newSource->id;
            $validated['source_name'] = $newSource->name;
        } else {
            $source = Source::find($validated['source_id']);
            $validated['source_name'] = $source->name;
        }

        if ($request->filled('new_category')) {
            $newCategory = Category::create(['name' => $request->new_category]);
            $request->merge(['categories' => array_merge($request->categories ?? [], [$newCategory->id])]);
        }

        if (!empty($validated['published_at'])) {
            $validated['published_at'] = Carbon::parse($validated['published_at'])->format('Y-m-d H:i:s');
        }

        $validated['user_id'] = Auth::id();

        $article = Article::create($validated);

        if ($request->has('categories')) {
            $article->categories()->sync($request->categories);
        }

        return redirect('/dashboard')->with('success', 'Article created successfully.');
    }

   public function show(Article $article)
{
    if ($article->user_id) {
        $biasResult = null;

        try {
            $response = \Illuminate\Support\Facades\Http::post('http://localhost:8000/classify', [
                'text' => $article->content,
            ]);

            $biasResult = $response->json();
        } catch (\Exception $e) {
            $biasResult = ['label' => 'Unknown', 'score' => 0];
        }

        return view('articles.show', compact('article', 'biasResult'));
    } else {
        return redirect()->away($article->url);
    }
}


    public function edit(Article $article)
    {
        if ($article->user_id !== Auth::id()) {
            abort(403);
        }

        $sources = Source::all();
        $categories = Category::all();

        return view('articles.edit', compact('article', 'sources', 'categories'));
    }

    public function update(Request $request, Article $article)
    {
        if ($article->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title'        => 'required|string|max:255|unique:articles,title,' . $article->id,
            'author'       => 'nullable|string|max:255',
            'description'  => 'required|string',
            'content'      => 'required|string',
            'url'          => 'required|url|max:2083',
            'url_to_image' => 'required|url|max:2083',
            'source_name'  => 'required|string',
            'published_at' => 'required|date',
        ]);

        if (!empty($validated['published_at'])) {
            $validated['published_at'] = Carbon::parse($validated['published_at'])->format('Y-m-d H:i:s');
        }

        $article->update($validated);

        return redirect('/dashboard')->with('success', 'Article updated successfully.');
    }

    public function destroy(Article $article)
    {
        $article->delete();
        return redirect('/dashboard')->with('success', 'Article deleted successfully.');
    }

    public function filterBySource(Request $request, $sourceId) {
        $articles = Article::bySource($sourceId)->get();
        return view('articles.index', compact('articles'));
    }
    public function index()
{
    $articles = Article::orderBy('published_at', 'desc')->paginate(10);
    return view('welcome', compact('articles'));
}

}
