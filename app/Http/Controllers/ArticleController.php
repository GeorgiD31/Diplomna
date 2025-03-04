<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Carbon\Carbon;

class ArticleController extends Controller
{
    public function create()
    {
        return view('articles.create');
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
            'source_name'  => 'required|string',
            'published_at' => 'required|date',
        ]);

        if (!empty($validated['published_at'])) {
            $validated['published_at'] = Carbon::parse($validated['published_at'])->format('Y-m-d H:i:s');
        }

        $validated['user_id'] = Auth::id();

        Article::create($validated);

        return redirect('/dashboard')->with('success', 'Article created successfully.');
    }

    public function show(Article $article)
    {
        if ($article->user_id) {
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
        } else {
            return redirect()->away($article->url);
        }
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
}
