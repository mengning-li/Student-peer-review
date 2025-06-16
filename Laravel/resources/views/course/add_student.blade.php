<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Enroll a Student in ') . $course->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <!-- Form to enroll a student in the course -->
                    <form method="POST" action="{{ route('course.enroll', $course->id) }}">
                        @csrf

                        <!-- Select a student who is not enrolled -->
                        <div>
                            <x-input-label for="user_id" :value="__('Select a Student')" />
                            <select name="user_id" id="user_id" class="block mt-1 w-full" required>
                                <option value="">Choose a Student</option>
                                @foreach($unenrolledStudents as $student)
                                <option value="{{ $student->id }}">{{ $student->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('user_id')" class="mt-2" />
                        </div>

                        <!-- Submit Button -->
                        <div class="mt-4 text-right">
                            <x-primary-button>{{ __('Enroll') }}</x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
