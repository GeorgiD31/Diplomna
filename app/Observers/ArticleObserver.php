<?php

namespace App\Observers;

use App\Models\Article;

class ArticleObserver
{
    public function deleting(Article $article)
    {
        $article->categories()->detach();
        $article->savedByUsers()->detach();
    }
}
