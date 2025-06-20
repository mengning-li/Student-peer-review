<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-indigo-800 leading-tight font-sans tracking-wide">
            {{ __('Course: ') . $course->name }}<br>
            {{ __('Assessment: ') . $assessment->title }} (Teacher View)
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            @if (auth()->check() && auth()->user()->role === 'teacher')
                <!-- Assessment Details Card -->
                <div class="bg-white rounded-2xl shadow p-6 border border-gray-200 mb-6" style="border-radius: 1rem;">
                    <h3 class="text-xl font-bold text-indigo-700 mb-4">Assessment Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-gray-700">
                        <div><span class="font-semibold">Title:</span> {{ $assessment->title }}</div>
                        <div><span class="font-semibold">Instruction:</span> {{ $assessment->instruction }}</div>
                        <div><span class="font-semibold">Due Date:</span> {{ $assessment->due_date }}</div>
                        <div><span class="font-semibold">Max Score:</span> {{ $assessment->max_score }}</div>
                        <div><span class="font-semibold">Required Reviews:</span> {{ $assessment->required_reviews }}</div>
                        <div><span class="font-semibold">Type:</span> 
                            <span class="capitalize">{{ str_replace('-', ' ', $assessment->type) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Students Overview Card -->
                <div class="bg-white rounded-2xl shadow p-6 border border-gray-200 mb-6" style="border-radius: 1rem;">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-xl font-bold text-indigo-700">Students Overview</h3>
                        <p class="text-gray-600 mt-2">Click on a student's name to view detailed reviews and assign scores</p>
                    </div>
                    
                    @if ($enrolledStudents->isNotEmpty())
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 border-b">Student Name</th>
                                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700 border-b">Reviews Received</th>
                                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700 border-b">Reviews Submitted</th>
                                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700 border-b">Current Score</th>
                                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700 border-b">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach ($enrolledStudents as $student)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4">
                                                <div class="flex items-center">
                                                    <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center mr-3">                              
                                                            {{ $student->name }}         
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                                    @if($student->received_num >= $assessment->required_reviews) 
                                                        bg-green-100 text-green-800 
                                                    @elseif($student->received_num > 0) 
                                                        bg-yellow-100 text-yellow-800 
                                                    @else 
                                                        bg-red-100 text-red-800 
                                                    @endif">
                                                    {{ $student->received_num }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                                    @if($student->submitted_num >= $assessment->required_reviews) 
                                                        bg-green-100 text-green-800 
                                                    @elseif($student->submitted_num > 0) 
                                                        bg-yellow-100 text-yellow-800 
                                                    @else 
                                                        bg-red-100 text-red-800 
                                                    @endif">
                                                    {{ $student->submitted_num }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                @if($student->score !== null)
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                                        {{ $student->score }}/{{ $assessment->max_score }}
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-600">
                                                        Not graded
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <a href="{{ route('student.detail', ['course_id' => $course->id, 'assessment_id' => $assessment->id, 'student_id' => $student->id]) }}" 
                                                   class="inline-flex items-center px-4 py-2 border border-indigo-600 rounded-lg text-sm font-medium text-indigo-600 bg-white hover:bg-indigo-50 transition-colors">
                                                    View Details
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Summary Statistics -->
                        <div class="p-6 bg-gray-50 border-t border-gray-200">
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                                <div>
                                    <div class="text-2xl font-bold text-indigo-600">{{ $enrolledStudents->count() }}</div>
                                    <div class="text-sm text-gray-600">Total Students</div>
                                </div>
                                <div>
                                    <div class="text-2xl font-bold text-green-600">
                                        {{ $enrolledStudents->where('score', '!=', null)->count() }}
                                    </div>
                                    <div class="text-sm text-gray-600">Graded</div>
                                </div>
                                <div>
                                    <div class="text-2xl font-bold text-yellow-600">
                                        {{ $enrolledStudents->where('submitted_num', '>=', $assessment->required_reviews)->count() }}
                                    </div>
                                    <div class="text-sm text-gray-600">Completed Reviews</div>
                                </div>
                                <div>
                                    <div class="text-2xl font-bold text-purple-600">
                                        @if($enrolledStudents->where('score', '!=', null)->count() > 0)
                                            {{ number_format($enrolledStudents->where('score', '!=', null)->avg('score'), 1) }}
                                        @else
                                            N/A
                                        @endif
                                    </div>
                                    <div class="text-sm text-gray-600">Average Score</div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="p-8 text-center">
                            <div class="text-gray-400 text-lg">No students enrolled in this course</div>
                        </div>
                    @endif
                </div>
            @else
                <div class="bg-red-50 border border-red-200 rounded-2xl p-6">
                    <div class="text-red-800 text-center">
                        <h3 class="font-semibold text-lg mb-2">Access Denied</h3>
                        <p>You must be a teacher to access this page.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
