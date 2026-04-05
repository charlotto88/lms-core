<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Chapter extends Model
{
    // Disable the "Bouncer" so we can save data easily
    protected $guarded = [];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function modules(): HasMany
    {
        // A Chapter has many Modules
        return $this->hasMany(Module::class)->orderBy('sort_order');
    }

    public function materials()
    {
        // This links the Chapter to the actual files/lessons
        return $this->hasMany(CourseMaterial::class)->orderBy('sort_order');
    }
}