<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Hash;

class Student extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [
        'first_name',
        'last_name',
        'student_id',
        'email',
        'user_id',
        'password', // Added so Filament can pass the new password here
    ];

    /**
     * The "booted" method of the model.
     * This intercepts the Save action to update the linked User's password.
     */
    protected static function booted()
        {
            static::saving(function ($student) {
                // 1. If a password was provided, update the linked User
                if (isset($student->password) && filled($student->password)) {
                    if ($student->user) {
                        $student->user->update([
                            'password' => \Illuminate\Support\Facades\Hash::make($student->password),
                        ]);
                    }
                }

                // 2. IMPORTANT: Remove the password from the student object 
                // so Laravel doesn't try to save it to the 'students' table.
                unset($student->password);
            });
            static::creating(function ($student) {
            // Automatically creates an email like: liampie.otto@college.test
                $domain = "@helpendehand.co.za";
                $student->college_email = strtolower($student->first_name . '.' . $student->last_name . $domain);
            });
        }

    /**
     * Relationship: A Student belongs to one User account (for login).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: A Student can be enrolled in many Courses.
     */
    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class);
    }

    /**
     * Helper: Get the full name of the student.
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }
}