<?php

use App\Http\Controllers\Student\CourseController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->group(function () {
    
    // Curriculum Map
    Route::get('/student/course/{course}/curriculum', [CourseController::class, 'curriculum'])
        ->name('student.course.curriculum');

    // Lesson Player
    Route::get('/student/course/{course}/lesson/{material}', [CourseController::class, 'lesson'])
        ->name('student.lesson.show');

    // Default Redirect
    Route::get('/student/course/{course}', [CourseController::class, 'curriculum']);
});

// Firewall for the Login redirect crash
Route::get('/login', function () {
    return redirect('/student/login');
})->name('login');