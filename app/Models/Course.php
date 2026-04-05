<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Course extends Model
{
    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'description',
        'course_code',
    ];

    // 1. The Relationship for Departments (Step 4)
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // 2. The Relationship for PDFs/Materials (The one missing in your error!)
    public function materials(): HasMany
    {
        return $this->hasMany(CourseMaterial::class);
    }

    // 3. The Relationship for Students
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class);
    }

    public function chapters()
    {
        // This tells Laravel that one course has many chapters/modules
        return $this->hasMany(Chapter::class)->orderBy('sort_order');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}