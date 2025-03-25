<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Lesson;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;

class AssignmentsController extends Controller
{
    public function index()
    {
        return view('assignments', [
            'assignments' => auth()->user()->courses->flatMap->assignments->sortBy('due_at'),
        ]);
    }

    public function calendar(Request $request)
    {
        $month = $request->get('month', now()->format('m'));
        $year = $request->get('year', now()->format('Y'));
        $carbon = CarbonImmutable::parse("$year-$month-01");

        $days = [];
        for ($i = 1; $i <= $carbon->daysInMonth; $i++) {
            $day = $carbon->setDay($i);
            $days[] = [
                'number' => $i,
                'dayOfWeek' => $day->dayOfWeek,
                'isToday' => $day->isToday(),
                'assignments' => auth()->user()->courses->flatMap->assignments
                    ->where('due_at', $day->format('Y-m-d 00:00:00')),
                'lessons' => auth()->user()->courses->flatMap->lessons
                    ->whereBetween('start', [$day->startOfDay(), $day->endOfDay()]),
            ];
        }

        return view('calendar', [
            'prevMonth' => $carbon->clone()->subMonth()->month,
            'prevYear' => $carbon->clone()->subYear()->year,
            'nextMonth' => $carbon->clone()->addMonth()->month,
            'nextYear' => $carbon->clone()->addYear()->year,
            'month' => $month,
            'year' => $year,
            'days' => $days,
            'title' => $carbon->format('F Y'),
        ]);
    }

    public function complete(Assignment $assignment)
    {
        $assignment->users()->attach(auth()->id(), ['grade' => random_int(4, 10)]);

        return redirect()->back()->with('message', 'Completed');
    }
}
