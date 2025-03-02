<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class ArticleController extends Controller
{
    public function create()
    {
        return view('articles.create');
    }
//znam che pak ne e po design patterna, no mi pravishe greshka, utre shte go napravq po pravilno
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:articles,title',
            'description' => 'required|string',
            'content' => 'nullable|string',
            'url_to_image' => 'nullable|url',
        ]);

        $validated['user_id'] = Auth::id();

        Article::create($validated);

        return redirect('/dashboard')->with('success', 'Article created successfully.');
    }

    public function show(Article $article)
    {
        $biasResult = null;

        try {
            $process = new Process(['python', base_path('classify_political_bias.py'), $article->content]);
            $process->run();

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            $biasResult = json_decode($process->getOutput(), true);
        } catch (\Exception $e) {
            $biasResult = ['label' => 'Unknown', 'score' => 0];
        }

        return view('articles.show', compact('article', 'biasResult'));
    }

    public function edit(Article $article)
    {
        if ($article->user_id !== Auth::id()) {
            abort(403);
        }

        return view('articles.edit', compact('article'));
    }

    public function update(Request $request, Article $article)
    {
        if ($article->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'content' => 'nullable|string',
            'url_to_image' => 'nullable|url',
        ]);

        $article->update($validated);

        return redirect('/dashboard')->with('success', 'Article updated successfully.');
    }

    public function destroy(Article $article)
    {
        $article->delete();
        return redirect('/dashboard')->with('success', 'Article deleted successfully.');
    }
    
}
