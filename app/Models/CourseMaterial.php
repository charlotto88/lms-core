<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseMaterial extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * We MUST include 'content_blocks' here so the Admin panel can save it.
     */
    protected $fillable = [
        'module_id',
        'title',
        'slug',
        'description',
        'type',
        'content',
        'content_blocks', // This is the most important line for your Lesson Designer
        'file_path',
        'external_url',
        'is_visible',
        'sort_order',
    ];

    /**
     * The attributes that should be cast.
     * This tells Laravel to treat the database 'text' column as a JSON array.
     */
    protected $casts = [
        'content_blocks' => 'array',
        'is_visible' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Relationship: A Material (Lesson) belongs to a Module.
     */
    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    /**
     * Boot function to handle automatic logic (like generating slugs).
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = \Illuminate\Support\Str::slug($model->title);
            }
        });
    }
}