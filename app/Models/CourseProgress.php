<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseProgress extends Model
{
    // This tells Laravel these columns are safe to save via Mass Assignment
    protected $fillable = ['user_id', 'course_material_id', 'completed_at'];

    /**
     * Relationship to the user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship to the lesson (material)
     */
    public function material()
    {
        return $this->belongsTo(CourseMaterial::class, 'course_material_id');
    }
}