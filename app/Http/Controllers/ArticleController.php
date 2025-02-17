<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArticleRequest;
use App\Models\Article;

class ArticleController extends Controller
{
    public function create()
    {
        return view('articles.create');
    }

    public function store(StoreArticleRequest $request)
    {
        
        $data = $request->validated();
        $data['author'] = auth()->user()->name;
        $data['user_id'] = auth()->id();

        Article::create($data);

        return redirect('/')->with('success', 'Article created successfully.');
    }
}


