<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Observers\ArticleObserver;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'description',
        'content',
        'url',
        'url_to_image',
        'source_name',
        'published_at',
        'user_id',
        'source_id',
    ];

    protected static function boot()
    {
        parent::boot();
        static::observe(ArticleObserver::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'article_category')
                    ->withTimestamps();
    }

    public function savedByUsers()
    {
        return $this->belongsToMany(User::class, 'saved_articles')->withTimestamps();
    }

    public function source()
    {
        return $this->belongsTo(Source::class);
    }

    public function scopeBySource($query, $sourceId)
    {
        return $query->where('source_id', $sourceId);
    }
}
