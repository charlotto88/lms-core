<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; // This must be here!
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Module extends Model
{
    protected $guarded = [];

    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class);
    }

    public function materials(): HasMany
    {
        // Filament needs this exact name 'materials' to match the Repeater
        return $this->hasMany(CourseMaterial::class, 'module_id')->orderBy('sort_order');
    }
}