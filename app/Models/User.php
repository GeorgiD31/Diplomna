<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    
    protected $fillable = [
        'name',
        'email',
        'password',
        'preferences', 
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'preferences' => 'array', 
    ];

    public function getCategoriesByNameAttribute()
    {
        $categoryNames = $this->preferences['categories'] ?? [];
        return Category::whereIn('name', $categoryNames)->get();
    }

    
    public function articles()
    {
        return $this->hasMany(Article::class);
    }
}
