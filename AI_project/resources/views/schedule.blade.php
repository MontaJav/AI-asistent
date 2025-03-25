<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My schedule') }}

                <a href="{{ route('schedule.create') }}" class="text-sm text-gray-600 dark:text-white hover:text-gray-900 dark:hover:text-gray-100 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded float-right">+</a>
            </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                  <div class="calendar">
                        <div class="calendar__header flex justify-between items-center mb-4">
                            <div class="calendar__header__prev">
                                <a href="{{ route('schedule.index') . "?week=$prevWeek" }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">❮</a>
                            </div>
                            <div class="calendar__header__week text-lg font-semibold">
                                {{ $title }}
                            </div>
                            <div class="calendar__header__next">
                                <a href="{{ route('schedule.index') . "?week=$nextWeek" }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">❯</a>
                            </div>
                        </div>
                        <div class="calendar__weekdays grid grid-cols-8 text-center font-semibold mb-2">
                            <div class="calendar__weekdays__day"></div>
                            <div class="calendar__weekdays__day">Mon</div>
                            <div class="calendar__weekdays__day">Tue</div>
                            <div class="calendar__weekdays__day">Wed</div>
                            <div class="calendar__weekdays__day">Thu</div>
                            <div class="calendar__weekdays__day">Fri</div>
                            <div class="calendar__weekdays__day">Sat</div>
                            <div class="calendar__weekdays__day">Sun</div>
                        </div>
                        <div>
                            @for ($i = $startOfDay; $i <= $endOfDay; $i++)
                                <div class="calendar__days grid grid-cols-8">
                                    <div class="border-r border-b border-gray-100 dark:border-gray-700 p-2">{{ $i }}:00</div>
                                    @foreach($days as $day)
                                        @php
                                            $hasEvent = false;
                                        @endphp
                                        <div class="calendar__day border-b border-gray-100 dark:border-gray-700 text-sm hover:bg-gray-200 dark:hover:bg-gray-800">
                                            <div class="calendar__day__body">
                                                @foreach($day['schedule'] as $slot)
                                                    @if((int)$slot->start->format('H') === $i)
                                                        @php
                                                            $hasEvent = true;
                                                        @endphp
                                                        <div class="calendar__day__event bg-blue-500 text-white dark:bg-blue-600 dark:text-white rounded-lg p-2">
                                                            <div class="calendar__day__event__title">{{ $slot->getTitle() }}</div>
                                                            <div class="calendar__day__event__time">{{ $slot->start->format('H:i') }} - {{ $slot->end->format('H:i') }}</div>
                                                            <form action="{{ route('schedule.destroy', $slot) }}" method="POST">
                                                                @csrf
                                                                <button type="submit" class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded">Delete</button>
                                                            </form>
                                                        </div>
                                                    @endif
                                                @endforeach
                                                @if(!$hasEvent)
                                                    <div class="calendar__day__event bg-gray-200 h-full w-full"></div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
