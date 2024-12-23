<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    public function preferences()
    {
        return $this->hasMany(Preference::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'preferences')
                    ->withPivot('political_bias')
                    ->withTimestamps();
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }
}

