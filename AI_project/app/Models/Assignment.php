<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'description',
        'due_at',
    ];

    protected $casts = [
        'due_at' => 'datetime',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'assignment_has_users');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function isCompleted()
    {
        return $this->users()->where('user_id', auth()->id())->exists();
    }

    public function getGrade()
    {
        return $this->users()->where('user_id', auth()->id())->value('grade');
    }

    public function getTitle()
    {
        return "{$this->course->name} (due {$this->due_at->format('Y-m-d')})";
    }

    public function toMinimumInfoArray()
    {
        return [
            'id' => $this->id,
            'courseName' => $this->course->name,
            'due' => $this->due_at->format('Y-m-d'),
            'isCompleted' => $this->isCompleted(),
            'grade' => $this->getGrade(),
        ];
    }
}
