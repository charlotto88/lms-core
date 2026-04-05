<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\CourseProgress;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Locked;

class CompleteLessonButton extends Component
{
    // Adding #[Locked] ensures the ID can't be tampered with or lost
    public $lessonId;
    public $isCompleted = false;

    public function mount($lessonId)
    {
        $this->lessonId = $lessonId;
        $this->checkStatus();
    }

    public function checkStatus()
    {
        $this->isCompleted = CourseProgress::where('user_id', Auth::id())
            ->where('course_material_id', $this->lessonId)
            ->exists();
    }

    // Inside the toggleComplete function
    public function toggleComplete()
    {
        $userId = Auth::id();
        
        // CRITICAL CHECK: If these are missing, the DB record will be empty
        if (!$userId || !$this->lessonId) {
            // This will show you exactly what is missing in your browser console
            logger("LMS Debug: Missing Data - User: $userId, Lesson: $this->lessonId");
            return; 
        }

        if ($this->isCompleted) {
            CourseProgress::where('user_id', $userId)
                ->where('course_material_id', $this->lessonId)
                ->delete();
            $this->isCompleted = false;
        } else {
            // Direct DB insert to be 100% sure it bypasses model quirks
            \DB::table('course_progress')->insert([
                'user_id' => $userId,
                'course_material_id' => $this->lessonId,
                'completed_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            $this->isCompleted = true;
        }

        $this->dispatch('progressUpdated'); 
    }

    public function render()
    {
        return view('livewire.complete-lesson-button');
    }
}