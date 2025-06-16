<x-app-layout>    
    @auth
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Enrolled Courses') }}
        </h2>    
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                <table class="table table-striped-columns">
                    <thead>
                        <tr>
                            <th scope="col">Course Code</th>
                            <th scope="col">Course Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($courses)
                            @foreach($courses as $course)
                            <tr>
                                <!-- Clicking on the course will lead to the course details page -->
                                <td><a href="{{ url("course_detail/{$course->id}") }}">{{ $course->code }}</a></td>
                                <td><a href="{{ url("course_detail/{$course->id}") }}">{{ $course->name }}</a></td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class="text-muted" colspan="2">No courses found.</td>
                            </tr>
                        @endif  
                    </tbody>
                </table>    
                </div>
            </div>
        </div>
    </div>
    @endauth    
</x-app-layout>
