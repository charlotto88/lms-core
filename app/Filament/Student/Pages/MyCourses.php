<?php

namespace App\Filament\Student\Pages;

use App\Models\Course;
use Filament\Pages\Page;
use Illuminate\Database\Eloquent\Collection;

class MyCourses extends Page
{
    // This points to the Blade file we edited earlier
    protected static string $view = 'filament.student.pages.my-courses';

    // Sidebar Icon
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    // Label shown in the sidebar
    protected static ?string $navigationLabel = 'My Enrolled Courses';

    // Title shown at the top of the page
    protected static ?string $title = 'My Enrolled Courses';

    /**
     * This function fetches all courses for the student.
     * In the future, you can change this to only show courses 
     * the logged-in user is actually enrolled in.
     */
    public function getCoursesProperty(): Collection
    {
        return Course::with(['chapters.modules.materials'])
            ->orderBy('title')
            ->get();
    }

    /**
     * Optional: Add breadcrumbs to match your professional vision.
     */
    public function getBreadcrumbs(): array
    {
        return [
            url('/student') => 'Dashboard',
            '#' => 'My Enrolled Courses',
        ];
    }
}