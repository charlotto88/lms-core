<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseMaterial;
use Illuminate\View\View;

class CourseController extends Controller
{
    /**
     * Show the Curriculum Map
     */
    public function curriculum(Course $course)
    {
        // Fetch all completed material IDs for this user as a simple array of integers
        $completedIds = \App\Models\CourseProgress::where('user_id', auth()->id())
            ->pluck('course_material_id')
            ->map(fn($id) => (int) $id)
            ->toArray();

        return view('student.courses.curriculum', compact('course', 'completedIds'));
    }

    public function show(Course $course, $materialId)
    {
        $currentLesson = \App\Models\CourseMaterial::findOrFail($materialId);
        
        $completedIds = \App\Models\CourseProgress::where('user_id', auth()->id())
            ->pluck('course_material_id')
            ->map(fn($id) => (int) $id)
            ->toArray();

        return view('student.courses.lesson', compact('course', 'currentLesson', 'completedIds'));
    }

    /**
     * Show an Individual Lesson
     */
    public function lesson(Course $course, CourseMaterial $material): View
    {
        $course->load(['chapters.modules.materials']);
        
        return view('student.courses.lesson', [
            'course' => $course,
            'currentLesson' => $material
        ]);
    }
}