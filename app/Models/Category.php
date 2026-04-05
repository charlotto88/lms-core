<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    // 1. Allow these fields to be saved
    protected $fillable = ['name', 'slug', 'description'];

    // 2. Define the relationship
    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }
}