<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Course extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * * Ensure 'slug' is here so Filament can save it, and 
     * 'course_code' matches what you have in your database.
     */
    protected $fillable = [
        'title',
        'slug',
        'description',
        'course_code',
        'banner_image',
        'is_visible', // <--- Make sure this is here
        'sort_order',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'is_visible' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * THE FIX FOR THE 404 ERROR:
     * This tells Laravel to use the 'slug' column instead of the 'id' 
     * when looking up a course in the URL.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * THE FIX FOR THE TYPEERROR:
     * Filament's CourseResource expects a relationship named 'chapters'.
     * Without this method, the Edit page will crash with:
     * "Argument #1 ($relationship) must be of type Relation, null given"
     */
    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class)->orderBy('sort_order');
    }

    /**
     * Helper to get all materials (lessons) associated with this course.
     * Useful for progress tracking and the sidebar.
     */
    public function materials()
    {
        return $this->hasManyThrough(
            CourseMaterial::class, 
            Chapter::class,
            'course_id',    // Foreign key on chapters table
            'chapter_id',   // Foreign key on modules table (adjust if materials belong to modules)
            'id',           // Local key on courses table
            'id'            // Local key on chapters table
        );
    }

    /**
     * Auto-generate a slug from the title if one isn't provided.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($course) {
            if (empty($course->slug)) {
                $course->slug = Str::slug($course->title);
            }
        });
        
        static::updating(function ($course) {
            if (empty($course->slug)) {
                $course->slug = Str::slug($course->title);
            }
        });
    }

    public function getProgressPercentAttribute()
    {
        $userId = auth()->id();
        if (!$userId) return 0;

        // 1. Get all lessons for this course
        $lessons = $this->chapters->flatMap->modules->flatMap->materials;
        $totalMinutes = $lessons->sum('duration');
        
        if ($totalMinutes <= 0) return 0;

        // 2. Get the IDs of the lessons for this specific course
        $lessonIds = $lessons->pluck('id')->toArray();

        // 3. Query the progress table directly for completed minutes
        $completedMinutes = \DB::table('course_progress')
            ->join('course_materials', 'course_progress.course_material_id', '=', 'course_materials.id')
            ->where('course_progress.user_id', $userId)
            ->whereIn('course_progress.course_material_id', $lessonIds)
            ->sum('course_materials.duration');

        return (int) round(($completedMinutes / $totalMinutes) * 100);
    }
}