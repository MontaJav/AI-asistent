<?php

namespace App\Http\Controllers;

use App\Models\UserSchedule;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $week = $request->get('week', now()->startOfWeek()->format('Y-m-d'));
        $carbon = CarbonImmutable::parse($week);

        for ($i = 0; $i < 7; $i++) {
            $day = $carbon->addDays($i);
            $days[] = [
                'number' => $day->day,
                'dayOfWeek' => $day->dayOfWeek,
                'isToday' => $day->isToday(),
                'schedule' => auth()->user()->schedule->whereBetween('start', [
                    $day->startOfDay(),
                    $day->endOfDay(),
                ]),
            ];
        }

        return view('schedule', [
            'prevWeek' => $carbon->clone()->subWeek()->format('Y-m-d'),
            'nextWeek' => $carbon->clone()->addWeek()->format('Y-m-d'),
            'week' => $week,
            'days' => $days,
            'title' => $carbon->dayOfMonth . ' - ' . $carbon->endOfWeek()->dayOfMonth . ' ' . $carbon->format('F Y'),
            'startOfDay' => env('START_OF_DAY', 8),
            'endOfDay' => env('END_OF_DAY', 20),
        ]);
    }

    public function create()
    {
        return view('add_schedule', [
            'startOfDay' => env('START_OF_DAY', 8),
            'endOfDay' => env('END_OF_DAY', 20),
            'courses' => auth()->user()->courses,
            'assignments' => auth()->user()->courses->flatMap->assignments,
            'lessons' => auth()->user()->courses->flatMap->lessons,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'day' => 'required|date',
            'start' => 'required|numeric',
            'end' => 'required|numeric|gt:start',
            'description' => 'nullable|string|required_without_all:lesson_id,assignment_id,course_id',
            'course_id' => 'nullable|exists:courses,id|required_without_all:lesson_id,assignment_id,description',
            'lesson_id' => 'nullable|exists:lessons,id|required_without_all:course_id,assignment_id,description',
            'assignment_id' => 'nullable|exists:assignments,id|required_without_all:course_id,lesson_id,description',
        ]);

        $start = CarbonImmutable::parse($request->get('day'))->setHour((int)$request->get('start'))->format('Y-m-d H:00:00');
        $end = CarbonImmutable::parse($request->get('day'))->setHour((int)$request->get('end'))->format('Y-m-d H:00:00');

        auth()->user()->schedule()->create([
            'start' => $start,
            'end' => $end,
            'description' => $request->get('description'),
            'course_id' => $request->get('course_id'),
            'lesson_id' => $request->get('lesson_id'),
            'assignment_id' => $request->get('assignment_id'),
        ]);

        return redirect()->route('schedule.index')->with('message', 'Scheduled');
    }

    public function destroy(UserSchedule $schedule)
    {
        $schedule->delete();

        return redirect()->back()->with('message', 'Deleted');
    }
}
