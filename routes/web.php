<?php

use App\Http\Controllers\Student\CourseController;
use Illuminate\Support\Facades\Route;

// 1. The Welcome Page
Route::get('/', function () {
    return view('welcome');
});

// 2. THE FIREWALL FIX: This stops the "Route [login] not defined" error.
// It tells Laravel that if it needs 'login', it should just go to the student login.
Route::get('/login', function () {
    return redirect('/student/login'); 
})->name('login');

// 3. THE STUDENT ROUTES
// We use 'web' instead of 'auth' to stop the redirect loop, 
// and handle the check inside the controller if needed.
Route::middleware(['web'])->group(function () {

    // The Map View
    Route::get('/student/course/{course:slug}/curriculum', [CourseController::class, 'curriculum'])
        ->name('student.course.curriculum');

    // The Lesson View
    Route::get('/student/course/{course:slug}/lesson/{material}', [CourseController::class, 'lesson'])
        ->name('student.lesson.show');

    // Base Redirect
    Route::get('/student/course/{course:slug}', [CourseController::class, 'curriculum']);
});