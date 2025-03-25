<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $course = Course::query();

        if ($request->get('mandatory')) {
            $course->where('mandatory', true);
        }
        if ($request->get('with_assignments')) {
            $course->whereHas('assignments');
        }
        if ($request->get('mine')) {
            $course->whereHas('users', fn ($query) => $query->where('user_id', auth()->id()));
        }

        return view('courses', [
            'courses' => $course->get(),
        ]);
    }

    public function register(Course $course)
    {
        $course->users()->attach(auth()->id());

        return redirect()->route('courses.index')->with('message', 'Registered');
    }

    public function unregister(Course $course)
    {
        $course->users()->detach(auth()->id());

        return redirect()->route('courses.index')->with('message', 'Unregistered');
    }
}
