<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseMaterial;

class CourseController extends Controller
{
    public function show(Course $course)
    {
        // Find the first lesson to redirect the student to
        $firstLesson = $course->chapters()
            ->first()?->modules()
            ->first()?->materials()
            ->first();

        if (!$firstLesson) {
            return back()->with('error', 'Hierdie kursus het nog geen lesse nie.');
        }

        return redirect()->route('student.lesson.show', [$course->slug, $firstLesson->id]);
    }

    public function lesson(Course $course, CourseMaterial $material)
    {
        // Load the navigation sidebar data
        $course->load(['chapters.modules.materials']);

        return view('student.courses.lesson', [
            'course' => $course,
            'currentLesson' => $material,
        ]);
    }

    public function curriculum(Course $course)
    {
        $course->load(['chapters.modules.materials']);
        return view('student.courses.curriculum', compact('course'));
    }
}