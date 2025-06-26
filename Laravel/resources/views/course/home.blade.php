<x-app-layout>    
    @auth
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            @if (auth()->user()->role === 'student')
                {{ __('Enrolled Courses') }}
            @elseif (auth()->user()->role === 'teacher')
                {{ __('Taught Courses') }}
            @endif
        </h2>    
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                <table class="table table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Course Code</th>
                            <th scope="col">Course Name</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                        @if ($courses->isEmpty())
                            <tr>
                                <td class="text-muted" colspan="2">No courses found.</td>
                            </tr>
                        @else
                        @foreach ($courses as $course)
                            <tr>
                                <td><a href="{{ url("course_detail/{$course->id}") }}" class="text-indigo-600 hover:text-indigo-800 font-medium text-decoration-none">{{ $course->code }}</a></td>
                                <td><a href="{{ url("course_detail/{$course->id}") }}" class="text-indigo-600 hover:text-indigo-800 font-medium text-decoration-none">{{ $course->name }}</a></td>
                                @if (auth()->user()->role === 'teacher')
                                    <td>
                                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                            <a href="{{route('course.add_student', $course->id) }}" class="text-gray-600 hover:text-gray-800 text-decoration-none">ADD STUDENT</a>                     
                                        </button>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>    
                </div>
            </div>
        </div>
    </div>
     
    @if (auth()->user()->role === 'teacher')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                <form action="{{ route('course.upload') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <strong><label for="formFile" class="form-label">Create a new course</label></strong>
                        <input class="form-control" type="file" id="formFile" name="course_file" style="border: 2px solid #ccc; border-radius: 5px; padding: 10px;">
                    </div>
                    <button type="submit" class="btn btn-dark">Submit</button>
                    <a href="{{ route('course.template.download') }}" class="btn btn-outline-primary ms-2 text-decoration-none"> Download Template</a>
                </form>              
                </div>
            </div>
        </div>
    </div>
    <!-- Success Message -->
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<!-- Error Message -->
@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<!-- General Session Messages -->
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

    @endif






@endauth   
</x-app-layout>
