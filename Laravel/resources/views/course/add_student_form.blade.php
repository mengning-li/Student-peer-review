<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Teaching Staff: ') }}
            @foreach($teachers as $teacher)
                {{ $teacher->name }}
                @if(!$loop->last), 
                @endif
            @endforeach
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Enroll a Student</h3>

                    <!-- Enrollment form -->
                    <form action="{{ route('course.enroll', $course->id) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="student" class="block text-sm font-medium text-gray-700">Select a Student</label>
                            <select name="student_id" id="student" class="form-control">
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->email }})</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Enroll Student</button>
                    </form>

                    <h3 class="mt-6 text-lg font-semibold">Currently Enrolled Students</h3>
                    <!-- Display a list of enrolled students here -->
                    <ul>
                        @foreach($enrolledStudents as $student)
                            <li>{{ $student->name }} ({{ $student->email }})</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
