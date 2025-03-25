<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Courses') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if($courses->isEmpty())
                        <p>No courses found.</p>
                    @else
                        <div class="filters flex flex-wrap">
                            <form action="{{ route('courses.index') }}" method="GET">
                                <input type="checkbox" name="mandatory" id="mandatory" @if(request('mandatory')) checked @endif>
                                <label for="mandatory">Mandatory</label>

                                <input type="checkbox" name="with_assignments" id="with_assignments" @if(request('with_assignments')) checked @endif>
                                <label for="with_assignments">With assignments</label>

                                <input type="checkbox" name="mine" id="mine" @if(request('mine')) checked @endif>
                                <label for="mine">Mine</label>

                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Filter</button>
                            </form>
                        </div>
                        <table class="table-auto w-full">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2">ID</th>
                                    <th class="px-4 py-2">Name</th>
                                    <th class="px-4 py-2">Description</th>
                                    <th class="px-4 py-2">Teacher</th>
                                    <th class="px-4 py-2">Duration (lessons)</th>
                                    <th class="px-4 py-2">Credit points</th>
                                    <th class="px-4 py-2">Mandatory</th>
                                    <th class="px-4 py-2">Registered</th>
                                    <th class="px-4 py-2">Next lesson at</th>
                                    <th class="px-4 py-2"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($courses as $course)
                                    <tr>
                                        <td class="border px-4 py-2">{{ $course->id }}</td>
                                        <td class="border px-4 py-2">{{ $course->name }}</td>
                                        <td class="border px-4 py-2">{{ $course->description }}</td>
                                        <td class="border px-4 py-2">{{ $course->teacher }}</td>
                                        <td class="border px-4 py-2">{{ $course->duration }}</td>
                                        <td class="border px-4 py-2">{{ $course->creditpoints }}</td>
                                        <td class="border px-4 py-2">
                                            @if($course->mandatory) ✅ @endif
                                        </td>
                                        <td class="border px-4 py-2">
                                            @if($course->isRegistered()) ✅ @elseif($course->mandatory) ❌ @endif
                                        </td>
                                        <td class="border px-4 py-2">{{ $course->lessons->sortBy('start')->first()?->start->format('d.m.Y H:i') }}</td>
                                        <td class="border px-4 py-2">
                                            @if(!$course->isRegistered())
                                                <form action="{{ route('courses.register', $course) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Register</button>
                                                </form>
                                            @else
                                                <form action="{{ route('courses.unregister', $course) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Unregister</button>
                                                </form>
                                            @endif
                                        </td>
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
