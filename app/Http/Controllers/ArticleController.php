<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;

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
            'content'      => 'nullable|string',
            'url'          => 'nullable|url',
            'url_to_image' => 'nullable|url',
            'source_name'  => 'nullable|string|max:255',
            'published_at' => 'nullable|date',
        ]);

        Article::create($validated);

        return redirect('/')->with('success', 'Article created successfully.');
    }
}
