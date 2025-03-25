<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'start',
        'end',
        'description',
        'mandatory',
        'cancelled_at',
    ];

    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime',
        'mandatory' => 'boolean',
        'cancelled_at' => 'datetime',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_has_lessons');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
