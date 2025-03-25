<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'teacher',
        'duration',
        'creditpoints',
        'mandatory',
    ];

    protected $casts = [
        'duration' => 'integer',
        'creditpoints' => 'integer',
        'mandatory' => 'boolean',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'description',
    ];

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'course_has_users');
    }

    public function isRegistered()
    {
        return $this->users->contains(auth()->id());
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    public function toMinimumInfoArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'teacher' => $this->teacher,
            'creditpoints' => $this->creditpoints,
            'mandatory' => $this->mandatory,
            'isRegistered' => $this->isRegistered(),
            'nextLessonAt' => $this->lessons->sortBy('start')->first()?->start->format('d.m.Y H:i'),
        ];
    }
}
