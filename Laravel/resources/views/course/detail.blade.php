<x-app-layout>    
    @auth
    <x-slot name="header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Course name: ') }} {{ $course->name }}
                <br>
                {{ __('Teaching Staff: ') }}
                @foreach($teachers as $teacher)
                    {{ $teacher->name }}
                    @if(!$loop->last), 
                    @endif
                @endforeach
            </h2>
        </div>
        @if (auth()->user()->role === 'teacher')
            <div>
                <a class="btn btn-dark" href="{{ route('course.add_assessment', ['course_id' => $course->id]) }}">ADD ASSESSMENT</a>
            </div>
        @endif
    </div>
    </x-slot>

                            
    <!-- Assessment List -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                <table class="table table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Weekly Assessment</th>
                            <th scope="col">Due Date</th>
                            @if (auth()->user()->role === 'student')
                                <th scope="col">Score</th>
                            @endif
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                        @if ($assessments)
                            @foreach($assessments as $assessment)
                            <tr>
                                <td><a href="{{ route('assessment.detail', ['course_id' => $course->id, 'assessment_id' => $assessment->id]) }}" class="text-indigo-600 hover:text-indigo-800 font-medium text-decoration-none">
                                        {{ $assessment->title }}
                                    </a>
                                </td>
                                <td>{{ $assessment->due_date }}</td>
                                
                                @if (auth()->user()->role === 'student')
                                <td>
                                    @if($assessment->assessmentScores->isNotEmpty())
                                        <span class="badge bg-success">
                                            {{ $assessment->assessmentScores->first()->score }}/{{ $assessment->max_score }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">Not graded</span>
                                    @endif
                                </td>
                                @endif
                                
                                @if (auth()->user()->role === 'teacher')
                                <td><button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                    <a href="{{route('assessment.edit' ,[$course->id, $assessment->id])}}" class="text-gray-600 hover:text-gray-800 text-decoration-none">Edit</a>                     
                                </button></td>
                                @endif
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class="text-muted" colspan="@if(auth()->user()->role === 'student') 3 @else 2 @endif">No assessment found.</td>
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
