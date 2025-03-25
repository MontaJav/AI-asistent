<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSchedule extends Model
{
    protected $table = 'user_schedule';

    protected $fillable = [
        'user_id',
        'lesson_id',
        'assignment_id',
        'course_id',
        'start',
        'end',
        'description',
    ];

    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function getDuration()
    {
        return $this->start->diff($this->end)->format('%H:%I');
    }

    public function getTitle()
    {
        if ($this->description) {
            return $this->description;
        }

        $prefix = 'Work on';

        if ($this->assignment) {
            return "{$prefix} assignment: {$this->assignment->getTitle()}";
        }

        if ($this->lesson) {
            return "{$prefix} lesson: {$this->lesson->title}";
        }

        if ($this->course) {
            return "{$prefix} course: {$this->course->title}";
        }
    }

    public function toMinimumInfoArray()
    {
        return [
            'id' => $this->id,
            'start' => $this->start->format('Y-m-d H:i'),
            'duration' => $this->getDuration(),
            'title' => $this->getTitle(),
        ];
    }
}
