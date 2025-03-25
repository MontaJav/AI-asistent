<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('New schedule item') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('schedule.store') }}" method="POST">
                        @csrf
                        <div class="mb-4 grid grid-cols-3 gap-4">
                            <div>
                                <label for="day" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Day</label>
                                <input required type="date" name="day" id="day" class="form-input rounded-md shadow-sm mt-1 block w-full dark:bg-gray-700 dark:text-gray-200" />
                            </div>
                            <div>
                                <label for="start" class="required block text-sm font-medium text-gray-700 dark:text-gray-200">Start time</label>
                                <input required min="{{ $startOfDay }}" max="{{ $endOfDay }}" type="number" name="start" id="start" class="form-input rounded-md shadow-sm mt-1 block w-full dark:bg-gray-700 dark:text-gray-200" />
                            </div>
                            <div>
                                <label for="end" class="block text-sm font-medium text-gray-700 dark:text-gray-200">End time</label>
                                <input required min="{{ $startOfDay }}" max="{{ $endOfDay }}" type="number" name="end" id="end" class="form-input rounded-md shadow-sm mt-1 block w-full dark:bg-gray-700 dark:text-gray-200" />
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Description</label>
                            <textarea type="text" name="description" id="description" class="form-input rounded-md shadow-sm mt-1 block w-full dark:bg-gray-700 dark:text-gray-200"></textarea>
                        </div>
                        <div class="mb-4 grid grid-cols-3 gap-4">
                            <div>
                                <label for="course_id" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Course</label>
                                <select name="course_id" id="course_id" class="form-select rounded-md shadow-sm mt-1 block w-full dark:bg-gray-700 dark:text-gray-200">
                                    <option value="">-</option>
                                    @foreach ($courses as $course)
                                        <option value="{{ $course->id }}">{{ $course->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="lesson_id" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Lesson</label>
                                <select name="lesson_id" id="lesson_id" class="form-select rounded-md shadow-sm mt-1 block w-full dark:bg-gray-700 dark:text-gray-200">
                                    <option value="">-</option>
                                    @foreach ($lessons as $lesson)
                                        <option value="{{ $lesson->id }}">{{ $lesson->course->name }} ({{ $lesson->start->format('m.d') }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="assignment_id" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Assignment</label>
                                <select name="assignment_id" id="assignment_id" class="form-select rounded-md shadow-sm mt-1 block w-full dark:bg-gray-700 dark:text-gray-200">
                                    <option value="">-</option>
                                    @foreach ($assignments as $assignment)
                                        <option value="{{ $assignment->id }}">{{ $assignment->getTitle() }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mb-4">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
