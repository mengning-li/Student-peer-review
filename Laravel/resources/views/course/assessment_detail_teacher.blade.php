<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Course: ') . $course->name }}
            <br>
            {{ __('Assessment: ') . $assessment->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                @if (auth()->check() && auth()->user()->role === 'teacher')
                <!-- assessment details for students -->
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="my-3 p-3 bg-body rounded shadow-sm">
                        <p class="pb-3"><strong>Title:</strong> {{ $assessment->title }}</p>
                        <p class="pb-3"><strong>Instruction:</strong> {{ $assessment->instruction }}</p>
                        <p class="pb-3"><strong>Due Date:</strong> {{ $assessment->due_date }}</p>
                        <p class="pb-3"><strong>Max Score:</strong> {{ $assessment->max_score }}</p>
                        <p class="pb-3"><strong>Required Reviews:</strong> {{ $assessment->required_reviews }}</p>
                    </div>
                </div>


                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="my-3 p-3 bg-body rounded shadow-sm">
                        <p class="pb-3 fs-4"><strong>Worshop List:</strong></p>
                        @if ($workshops)
                            <div class="list-group">
                                <table class="table table-bordered table-striped table-hover">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th scope="col">Workshop title</th>
                                            <th scope="col">workshop Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($workshops as $workshop)
                                            <tr>
                                                <td>
                                                 <a href="{{ route('workshops.list', ['assessment_id' => $assessment->id] }}">
                                        <p>{{ $workshop->title }}</p> 
                                    </a>
                                </td>
                                <td>
                                    @if ($workshop->active_status)
                                        <!-- If the workshop is active, show the "Join" button -->
                                        <form action="{{ route('workshop.join', [$workshop->id, $assessment->id, $user->id]) }}" method="POST" class="ms-3">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-secondary btn-sm">Join</button>
                                        </form>
                                    @else
                                        <!-- If the workshop is inactive, disable the button and show a message -->
                                        <button type="button" class="btn btn-outline-secondary btn-sm" disabled>Inactive</button>
                                    @endif
                                </td>
                                <td>{{ $student->submitted_num ?? 'N/A' }}</td>                
                                <td>{{ $student->score ?? 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>    
                </table>
            </div>
        @else
            <p class="text-muted">No workshops found.</p>
        @endif
    </div>

                <div class="p-6 text-gray-900">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">Student Name</th>
                                <th scope="col">Review received</th>
                                <th scope="col">Review submitted</th>
                                <th scope="col">Score</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($enrolledStudents as $student)
                                <tr>
                                    <td>
                                    <a href="{{ route('student.detail', ['course_id' => $course->id, 'assessment_id' => $assessment->id, 'student_id' => $student->id]) }}">
                                        {{ $student->name }}
                                    </a>
                                    </td>
                                    <td>{{ $student->received_num }}</td>
                                    <td>{{ $student->submitted_num }}</td>                
                                    <td>{{ $student->score }}</td>
                                </tr>
                            @endforeach
                        </tbody>    
                    </table>
                    
                    <!-- Pagination Links -->
                    <div class="pagination">
                        {{ $enrolledStudents->links() }}   
                    </div>
                @endif
                </div>
        </div>
    </div>
</x-app-layout>
