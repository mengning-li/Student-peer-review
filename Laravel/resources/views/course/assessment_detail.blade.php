<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Course: ') . $course->name }}
            <br>
            {{ __('Assessment: ') . $assessment->title }}
        </h2>
    </x-slot>

    <!-- show  for student -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                @if (auth()->check() && auth()->user()->role === 'student')
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



                    <!-- student review received -->
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="my-3 p-3 bg-body rounded shadow-sm">
                            <p class="pb-3 fs-4"><strong>Received Reviews</strong> (Only one received review can be marked useful)</p>                       
                            @if ($reviewReceived->isNotEmpty())
                                <div class="list-group">
                                    @foreach ($reviewReceived as $review)
                                        <div class="list-group-item d-flex justify-content-between align-items-start">
                                            <div class="ms-2 me-auto">
                                                <div class="fw-bold">{{ $review->reviewer_name }}:</div>
                                                <span class="d-block">{{ $review->review_content }}</span>
                                            </div>
                                            <form action="{{ route('review.mark_useful', [$course->id, $assessment->id, $review->id]) }}" method="POST" class="ms-3">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-secondary btn-sm">Mark as Useful</button>
                                            </form>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted">No review found.</p>
                            @endif
                        </div>
                    
                        <!-- student submit review form -->
                        <div class="my-3 p-3 bg-body rounded shadow-sm">
                        @if ($assessment->type === 'student-select')
                            <form action="{{ route('review.store', [$course->id, $assessment->id]) }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <p><strong>Select your reviewee</strong> </p>
                                        <select name="reviewee_id" id="reviewee" class="form-control" required>
                                            <option value="">Select a student to review</option>
                                            @foreach($studentsToReview as $student)
                                                <option value="{{ $student->id }}">{{ $student->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                
                                <div class="mb-4">
                                    <p><strong>Review content</strong> </p>
                                    <textarea class="form-control w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" name="review_content" ></textarea>
                                    <x-input-error :messages="$errors->get('review_content')" class="mt-2" />
                                </div>

                                <button type="submit" class="btn btn-primary">Submit Review</button>
                            </form>
                        @else
                            <p class="text-muted">Join your workshop</p>                 
                        </div>
                        @endif
                @endif

                <!-- asessement page for teacher -->
                @if (auth()->check() && auth()->user()->role === 'teacher')
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
