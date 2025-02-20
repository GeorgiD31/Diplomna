<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Category;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ProfileController;

Route::get('/', function (Request $request) {
    $categoryName = $request->input('category'); 
    
    if ($categoryName) {
        $category = Category::where('name', $categoryName)->first();
        if ($category) {

            $articles = $category->articles()->latest()->take(10)->get();
        } else {
            $articles = Article::latest()->take(10)->get();
        }
    } else {
        
        $articles = Article::latest()->take(10)->get();
    }
    
    return view('welcome', compact('articles'));
})->name('home');

Route::get('/dashboard', function () {
    $myArticles = Article::where('user_id', auth()->id())->latest()->get();
    return view('dashboard', compact('myArticles'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/articles/create', [ArticleController::class, 'create'])->name('articles.create');
Route::post('/articles', [ArticleController::class, 'store'])->name('articles.store');
Route::get('/articles/{article}', [ArticleController::class, 'show'])->name('articles.show');
