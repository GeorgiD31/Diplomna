<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Category;
use App\Models\Source;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SavedArticleController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Artisan;

Route::get('/', function (Request $request) {
    Artisan::call('fetch:news');

    $categoryName = $request->input('category');
    $searchQuery = $request->input('search');
    $sourceId = $request->input('source');

    $categories = Category::with('children')->whereNull('parent_id')->get();
    $sources = Source::all();

    if ($searchQuery) {
        $articles = Article::where('title', 'like', '%' . $searchQuery . '%')->latest()->take(10)->get();
    } elseif ($categoryName) {
        $category = Category::where('name', $categoryName)->first();
        if ($category) {
            $articles = $category->articles()->latest()->take(10)->get();
        } else {
            $articles = Article::latest()->take(10)->get();
        }
    } elseif ($sourceId) {
        $articles = Article::where('source_id', $sourceId)->latest()->take(10)->get();
    } else {
        $articles = Article::latest()->take(10)->get();
    }

    return view('welcome', compact('articles', 'categories', 'sources'));
})->name('home');

Route::post('/fetch-latest-news', function () {
    Artisan::call('fetch:news');
    return response()->json(['message' => 'News fetched successfully!']);
}); 

Route::get('/home/preferred', function () {
    $user = auth()->user();
    $categoryNames = $user->preferences['categories'] ?? [];
    $preferredSources = $user->preferences['sources'] ?? [];

    $categories = Category::with('children')->whereNull('parent_id')->get();
    $sources = Source::all();

    $articles = Article::whereHas('categories', function ($query) use ($categoryNames) {
        $query->whereIn('name', $categoryNames);
    });

    if ($preferredSources) {
        $articles = $articles->whereIn('source_id', $preferredSources);
    }

    $articles = $articles->latest()->take(10)->get();

    return view('welcome', compact('articles', 'categories', 'sources'));
})->middleware(['auth', 'verified'])->name('home.preferred');

Route::get('/dashboard', function () {
    $myArticles = Article::where('user_id', auth()->id())->latest()->get();
    return view('dashboard', compact('myArticles'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard/articles', function () {
    $myArticles = Article::where('user_id', auth()->id())->latest()->get();
    return response()->json(['articles' => $myArticles]);
})->middleware(['auth', 'verified'])->name('dashboard.articles');

Route::get('/dashboard/saved', function () {
    $savedArticles = Auth::user()->savedArticles()->latest()->get();
    return response()->json(['articles' => $savedArticles]);
})->middleware(['auth', 'verified'])->name('dashboard.saved');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::middleware(['auth'])->group(function () {
    Route::get('/articles/create', [ArticleController::class, 'create'])->name('articles.create');
    Route::post('/articles', [ArticleController::class, 'store'])->name('articles.store');
    Route::get('/articles/{article}', [ArticleController::class, 'show'])->name('articles.show');
    Route::get('/articles/{article}/edit', [ArticleController::class, 'edit'])->name('articles.edit'); 
    Route::put('/articles/{article}', [ArticleController::class, 'update'])->name('articles.update'); 
    Route::delete('/articles/{article}', [ArticleController::class, 'destroy'])->name('articles.destroy'); 

    Route::post('/articles/{article}/save', [SavedArticleController::class, 'store'])->name('articles.save');
    Route::delete('/articles/{article}/unsave', [SavedArticleController::class, 'destroy'])->name('articles.unsave');
});

Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');

Route::get('/articles/source/{sourceId}', [ArticleController::class, 'filterBySource'])->name('articles.filterBySource');
