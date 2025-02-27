<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use Illuminate\Support\Facades\Auth;

class SavedArticleController extends Controller
{
    public function store(Article $article)
    {
        if (!Auth::user()->savedArticles->contains($article->id)) {
            Auth::user()->savedArticles()->attach($article->id);
            return response()->json(['success' => 'Article saved successfully.']);
        }
        return response()->json(['info' => 'Article is already saved.']);
    }

    public function destroy(Article $article)
    {
        if (Auth::user()->savedArticles->contains($article->id)) {
            Auth::user()->savedArticles()->detach($article->id);
            return response()->json(['success' => 'Article unsaved successfully.']);
        }
        return response()->json(['info' => 'Article is not saved.']);
    }
}