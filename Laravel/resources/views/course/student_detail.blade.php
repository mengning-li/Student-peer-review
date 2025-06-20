<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Course: ') . $course->name }}
            <br>
            {{ __('Assessment: ') . $assessment->title }} - {{ $student->name }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <!-- Back Button -->
            <div class="flex justify-start">
                <a href="{{ route('assessment.detail', ['course_id' => $course->id, 'assessment_id' => $assessment->id]) }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                    ← Back to Assessment Overview
                </a>
            </div>

            <!-- Student Overview Card -->
            <div class="bg-white rounded-2xl shadow p-6 border border-gray-200 mb-6" style="border-radius: 1rem;">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div class="flex items-center space-x-4 sm:space-x-6">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">{{ $student->name }}</h3>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-sm">
                        <div class="text-center">
                            <div class="text-lg font-bold text-green-600">{{ $reviewReceived->count() }}</div>
                            <div class="text-gray-500">Received</div>
                        </div>
                        <div class="text-center">
                            <div class="text-lg font-bold text-blue-600">{{ $reviewSubmitted->count() }}</div>
                            <div class="text-gray-500">Submitted</div>
                        </div>
                        <div class="text-center">
                            <div class="text-lg font-bold text-purple-600">
                                @if($reviewReceived->where('score', '!=', null)->count() > 0)
                                    {{ number_format($reviewReceived->where('score', '!=', null)->avg('score'), 1) }}
                                @else
                                    N/A
                                @endif
                            </div>
                            <div class="text-gray-500">Avg Score</div>
                        </div>
                        <div class="text-center col-span-2 sm:col-span-1 border-t sm:border-t-0 sm:border-l border-gray-200 pt-4 sm:pt-0 sm:pl-4">
                            <div class="text-xs text-gray-500">Current Score</div>
                            @if (!empty($assessment_score))
                                <div class="text-lg font-bold text-indigo-600">{{ $assessment_score->score }}/{{ $assessment->max_score }}</div>
                            @else
                                <div class="text-sm font-medium text-gray-400">Not graded</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reviews Received by Student -->
            <div class="bg-white rounded-2xl shadow p-6 border border-gray-200 mb-6" style="border-radius: 1rem;">
                <div class="p-4 sm:p-6 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-indigo-700">Reviews Received by {{ $student->name }}</h3>
                    <p class="text-gray-600 mt-2">Reviews this student has received from peers</p>
                </div>
                
                @if ($reviewReceived && $reviewReceived->count())
                    <div class="p-4 sm:p-6 space-y-4">
                        @foreach ($reviewReceived as $review)
                            <div class="bg-green-50 rounded-xl p-4 border border-green-200">
                                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-3 mb-3">
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-green-800">From: {{ $review->reviewer->name ?? 'Unknown' }}</h4>
                                        @if($review->score)
                                            <span class="inline-flex items-center px-2 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium mt-1">
                                                Score Received: {{ $review->score }}/100
                                            </span>
                                        @endif
                                    </div>
                                    <div class="text-sm text-gray-500 flex-shrink-0">
                                        {{ $review->created_at ? $review->created_at->format('M d, Y') : 'Date unknown' }}
                                    </div>
                                </div>
                                <div class="text-gray-800 bg-white rounded-lg p-3 border break-words">
                                    {{ $review->review_content }}
                                </div>
                                @if ($review->rating)
                                    <div class="mt-3 p-3 bg-white rounded-lg border">
                                        <div class="text-sm font-medium text-gray-700">Student's rating of this review: ({{ $review->rating }}/5)</div>
                                        @if($review->feedback)
                                            <div class="text-gray-600 mt-1 break-words">{{ $review->feedback }}</div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-8 text-center">
                        <div class="text-gray-400 text-lg">No reviews received yet</div>
                    </div>
                @endif
            </div>

            <!-- Reviews Submitted by Student -->
            <div class="bg-white rounded-2xl shadow p-6 border border-gray-200 mb-6" style="border-radius: 1rem;">
                <div class="p-4 sm:p-6 border-b border-orange-200">
                    <h3 class="text-xl font-bold text-orange-700">Reviews Submitted by {{ $student->name }}</h3>
                    <p class="text-orange-600 mt-2">Reviews this student has written for peers</p>
                </div>
                
                @if ($reviewSubmitted && $reviewSubmitted->count())
                    <div class="p-4 sm:p-6 space-y-4">
                        @foreach ($reviewSubmitted as $review)
                            <div class="bg-orange-100 rounded-xl p-4 border border-orange-300">
                                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-3 mb-3">
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-orange-800">Reviewed: {{ $review->reviewee->name ?? 'Unknown' }}</h4>
                                        @if($review->score)
                                            <span class="inline-flex items-center px-2 py-1 bg-orange-200 text-orange-800 rounded-full text-sm font-medium mt-1">
                                                Score Given: {{ $review->score }}/100
                                            </span>
                                        @endif
                                    </div>
                                    <div class="text-sm text-gray-500 flex-shrink-0">
                                        {{ $review->created_at ? $review->created_at->format('M d, Y') : 'Date unknown' }}
                                    </div>
                                </div>
                                <div class="text-gray-800 bg-white rounded-lg p-3 border break-words">
                                    {{ $review->review_content }}
                                </div>
                                @if ($review->rating)
                                    <div class="mt-3 p-3 bg-white rounded-lg border">
                                        <div class="text-sm font-medium text-gray-700">Feedback received from reviewee: ({{ $review->rating }}/5)</div>
                                        @if($review->feedback)
                                            <div class="text-gray-600 mt-1 break-words">{{ $review->feedback }}</div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-8 text-center">
                        <div class="text-orange-400 text-lg">No reviews submitted yet</div>
                    </div>
                @endif
            </div>

            <!-- Score Assignment Card -->
            <div class="bg-white rounded-2xl shadow p-6 border border-gray-200 mb-6" style="border-radius: 1rem;">
                <div class="p-4 sm:p-6 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-indigo-700">Assign Score</h3>
                    <p class="text-gray-600 mt-2">Set the final assessment score for this student (Max: {{ $assessment->max_score }})</p>
                </div>
                
                <div class="p-4 sm:p-6">
                    <form action="{{ route('student.score', [$course->id, $assessment->id, $student->id]) }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label for="score" class="block text-sm font-medium text-gray-700 mb-2">Assessment Score</label>
                            <div class="flex items-center space-x-4">
                                <input 
                                    type="number" 
                                    id="score" 
                                    name="score" 
                                    min="0" 
                                    max="{{ $assessment->max_score }}" 
                                    value="{{ old('score', $assessment_score->score ?? '') }}" 
                                    class="flex-1 px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 w-full"
                                    placeholder="Enter score (0-{{ $assessment->max_score }})"
                                >
                                <div class="text-gray-500 whitespace-nowrap">/ {{ $assessment->max_score }}</div>
                            </div>
                            <x-input-error :messages="$errors->get('score')" class="mt-2" />
                        </div>
                        
                        <div class="pt-4">
                            <button type="submit" class="px-6 py-3 bg-white border-2 border-indigo-600 text-indigo-600 rounded-lg hover:bg-indigo-50 transition font-bold text-lg shadow-md w-full sm:w-auto">
                                {{ $assessment_score ? 'Update Score' : 'Assign Score' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>