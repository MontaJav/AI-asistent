<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Assignments') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if($assignments->isEmpty())
                        <p>No assignments found.</p>
                    @else
                        <table class="table-auto w-full">
                            <thead>
                            <tr>
                                <th class="px-4 py-2">Course</th>
                                <th class="px-4 py-2">Description</th>
                                <th class="px-4 py-2">Due at</th>
                                <th class="px-4 py-2">Completed</th>
                                <th class="px-4 py-2">Grade</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($assignments as $assignment)
                                <tr>
                                    <td class="border px-4 py-2">{{ $assignment->course->name }}</td>
                                    <td class="border px-4 py-2">{{ $assignment->description }}</td>
                                    <td class="border px-4 py-2">{{ $assignment->due_at->format('d.m.Y H:i') }}</td>
                                    <td class="border px-4 py-2">
                                        @if($assignment->isCompleted())
                                            ✅
                                        @else
                                            <form action="{{ route('assignments.complete', $assignment) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Complete</button>
                                            </form>
                                        @endif
                                    </td>
                                    <td class="border px-4 py-2">{{ $assignment->getGrade() }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
