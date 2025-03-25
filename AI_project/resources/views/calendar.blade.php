<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Calendar') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                  <div class="calendar">
                        <div class="calendar__header flex justify-between items-center mb-4">
                            <div class="calendar__header__prev">
                                <a href="{{ route('assignments.calendar') . "?month=$prevMonth" }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">❮</a>
                            </div>
                            <div class="calendar__header__month text-lg font-semibold">
                                {{ $title }}
                            </div>
                            <div class="calendar__header__next">
                                <a href="{{ route('assignments.calendar') . "?month=$nextMonth" }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">❯</a>
                            </div>
                        </div>
                        <div class="calendar__weekdays grid grid-cols-7 text-center font-semibold mb-2">
                            <div class="calendar__weekdays__day">Mon</div>
                            <div class="calendar__weekdays__day">Tue</div>
                            <div class="calendar__weekdays__day">Wed</div>
                            <div class="calendar__weekdays__day">Thu</div>
                            <div class="calendar__weekdays__day">Fri</div>
                            <div class="calendar__weekdays__day">Sat</div>
                            <div class="calendar__weekdays__day">Sun</div>
                        </div>
                        <div class="calendar__days grid grid-cols-7 gap-2">
                            @for($i = 1; $i < $days[0]['dayOfWeek']; $i++)
                                <div class="calendar__days__day"></div>
                            @endfor
                            @foreach($days as $day)
                                <div class="calendar__days__day p-2 border rounded @if($day['isToday']) bg-blue-100 dark:bg-blue-900 @endif">
                                    <div class="calendar__days__day__number font-bold mb-1">{{ $day['number'] }}</div>
                                    <div class="calendar__days__day__assignments space-y-1">
                                        @foreach($day['assignments'] as $assignment)
                                            <div class="calendar__days__day__assignments__assignment p-1 bg-gray-100 dark:bg-gray-700 rounded">
                                                <div class="calendar__days__day__assignments__assignment__course text-sm font-semibold @if($assignment->isCompleted()) line-through @endif">
                                                    {{ $assignment->course->name }}
                                                </div>
                                                @if($assignment->isCompleted())
                                                    <div class="calendar__days__day__assignments__assignment__description text-xs">
                                                        Grade: {{ $assignment->getGrade() }}
                                                    </div>
                                                @else
                                                    <div class="calendar__days__day__assignments__assignment__description text-xs overflow-hidden truncate max-h-[50px]">
                                                        {{ $assignment->description }}
                                                        <form action="{{ route('assignments.complete', $assignment) }}" method="POST">
                                                            @csrf
                                                            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Complete</button>
                                                        </form>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                        @foreach($day['lessons'] as $lesson)
                                            <div class="calendar__days__day__assignments__assignment p-1 bg-violet-100 dark:bg-violet-700 rounded">
                                                <div class="calendar__days__day__assignments__assignment__course text-sm font-semibold @if($lesson->mandatory) text-orange-700 @endif">
                                                    {{ $lesson->course->name }}
                                                </div>
                                                <div class="calendar__days__day__assignments__assignment__description text-xs">
                                                    {{ $lesson->start->format('H:i') }} - {{ $lesson->end->format('H:i') }}
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
