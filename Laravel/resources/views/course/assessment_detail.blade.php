<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-indigo-800 leading-tight font-sans tracking-wide">
            {{ __('Course: ') . $course->name }}<br>
            {{ __('Assessment: ') . $assessment->title }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8 sm:rounded-lg">
            <!-- Success Message -->
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Error Message -->
            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif
            @if (auth()->check() && auth()->user()->role === 'student')
                <!-- Assessment Details Card -->
                <div class="bg-white rounded-2xl shadow p-6 border border-gray-200 mb-6" style="border-radius: 1rem;">
                    <h3 class="text-xl font-bold text-indigo-700 mb-4">Assessment Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-gray-700">
                        <div><span class="font-semibold">Title:</span> {{ $assessment->title }}</div>
                        <div><span class="font-semibold">Instruction:</span> {{ $assessment->instruction }}</div>
                        <div><span class="font-semibold">Due Date:</span> {{ $assessment->due_date }}</div>
                        <div><span class="font-semibold">Max Score:</span> {{ $assessment->max_score }}</div>
                        <div><span class="font-semibold">Required Reviews:</span> {{ $assessment->required_reviews }}</div>
                    </div>
                </div>

                

                <!-- Received Reviews Card -->
                <div class="bg-white rounded-2xl shadow p-6 border border-gray-200 mb-6" style="border-radius: 1rem;">
                    <h3 class="text-xl font-bold text-indigo-700 mb-4">Received Reviews</h3>
                    @if ($reviewReceived->isNotEmpty())
                        <div class="space-y-6">
                            @foreach ($reviewReceived as $review)
                                <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                    <div class="mb-4">
                                        <div class="font-semibold text-indigo-600 mb-2">
                                            {{ $review->reviewer->name ?? 'Unknown' }}
                                            @if($review->score)
                                                <span class="ml-2 px-2 py-1 bg-purple-100 text-purple-800 rounded-full text-sm font-medium">
                                                    Score: {{ $review->score }}/100
                                                </span>
                                            @endif
                                        </div>
                                        <div class="text-gray-800">{{ $review->review_content }}</div>
                                    </div>
                                    
                                    @if (is_null($review->rating))
                                        <!-- Rating and Feedback Form -->
                                        <div class="mt-4 p-4 bg-white rounded-lg border-2 border-indigo-200">
                                            <form action="{{ route('review.rate_feedback', $review->id) }}" method="POST" class="space-y-4">
                                                @csrf
                                                <div class="flex items-center gap-3">
                                                    <label for="rating_{{ $review->id }}" class="font-medium text-gray-700">Rate this review:</label>
                                                    <select name="rating" id="rating_{{ $review->id }}" class="px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                                        <option value="1">1 - Poor</option>
                                                        <option value="2">2 - Fair</option>
                                                        <option value="3" selected>3 - Good</option>
                                                        <option value="4">4 - Very Good</option>
                                                        <option value="5">5 - Excellent</option>
                                                    </select>
                                                </div>
                                                
                                                <div>
                                                    <label for="feedback_{{ $review->id }}" class="block font-medium text-gray-700 mb-1">Feedback (optional):</label>
                                                    <textarea name="feedback" id="feedback_{{ $review->id }}" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Share your thoughts about this review..."></textarea>
                                                </div>

                                                <div class="flex justify-end pt-2">
                                                    <button type="submit" class="px-6 py-3 bg-white border-2 border-indigo-600 text-indigo-600 rounded-lg hover:bg-indigo-50 transition font-bold text-lg shadow-md">Submit feedback</button>
                                                </div>
                                            </form>
                                        </div>
                                    @else
                                        <!-- Display existing rating and feedback -->
                                        <div class="mt-4 p-4 bg-green-50 rounded-lg border border-green-200">
                                            <div class="flex items-center gap-2 mb-2">
                                                <span class="font-medium text-green-800">Your Rating:</span>
                                                <span class="px-3 py-1 bg-green-200 text-green-800 rounded-full font-medium">
                                                    @if($review->rating == 1) ⭐ Poor @endif
                                                    @if($review->rating == 2) ⭐⭐ Fair @endif
                                                    @if($review->rating == 3) ⭐⭐⭐ Good @endif
                                                    @if($review->rating == 4) ⭐⭐⭐⭐ Very Good @endif
                                                    @if($review->rating == 5) ⭐⭐⭐⭐⭐ Excellent @endif
                                                    ({{ $review->rating }}/5)
                                                </span>
                                            </div>
                                            @if($review->feedback)
                                                <div class="text-green-800">
                                                    <span class="font-medium">Your Feedback:</span> {{ $review->feedback }}
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 italic">No review found.</p>
                    @endif
                </div>

                <!-- Submitted Reviews Section (different color, under received reviews) -->
                <div class="bg-white rounded-2xl shadow p-6 border border-gray-200 mb-6" style="border-radius: 1rem;">
                    <h3 class="text-xl font-bold text-indigo-700 mb-4">Reviews I Submitted</h3>
                    @if (isset($reviewSubmitted) && $reviewSubmitted->isNotEmpty())
                        <div class="space-y-4">
                            @foreach ($reviewSubmitted as $review)
                                <div class="bg-white rounded-xl p-4 border border-gray-100">
                                    <div><span class="font-semibold">To:</span> 
                                        {{ $review->reviewee->name ?? 'Unknown' }}
                                        @if($review->score)
                                            <span class="ml-2 px-2 py-1 bg-orange-100 text-orange-800 rounded-full text-sm font-medium">
                                                Score Given: {{ $review->score }}/100
                                            </span>
                                        @endif
                                    </div>
                                    <div><span class="font-semibold">Content:</span> {{ $review->review_content }}</div>
                                    @if ($review->rating)
                                        <div class="mt-2">
                                            <span class="font-semibold text-indigo-700">Feedback {{ $review->rating }}/5:</span> 
                                            <span class="text-gray-600">{{ $review->feedback ?? 'No feedback provided' }}</span>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 italic">You have not submitted any reviews yet.</p>
                    @endif
                </div>

                <!-- Submit Review Card (always at the bottom, with button) -->
                <div class="bg-white rounded-2xl shadow p-6 border border-gray-200 mb-6" style="border-radius: 1rem;">
                    <h3 class="text-xl font-bold text-indigo-700 mb-4">Submit a Review</h3>
                    @if ($assessment->type === 'student-select')
                        <form action="{{ route('review.store', [$course->id, $assessment->id]) }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label for="reviewee" class="block font-semibold text-gray-700 mb-1">Select your reviewee</label>
                                <select name="reviewee_id" id="reviewee" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="">Select a student to review</option>
                                    @foreach($studentsToReview as $student)
                                        <option value="{{ $student->id }}">{{ $student->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="review_content" class="block font-semibold text-gray-700 mb-1">Review content</label>
                                <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" name="review_content" id="review_content"></textarea>
                                <x-input-error :messages="$errors->get('review_content')" class="mt-2" />
                            </div>
                            <div>
                                <label for="score" class="block font-semibold text-gray-700 mb-1">Score for student (0-100)</label>
                                <input type="number" name="score" id="score" min="0" max="100" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Score">
                            </div>
                            <div class="pt-4">
                                <button type="submit" class="px-6 py-3 bg-white border-2 border-indigo-600 text-indigo-600 rounded-lg hover:bg-indigo-50 transition font-bold text-lg shadow-md">Submit Review</button>
                            </div>
                        </form>
                    @else
                        <p class="text-gray-500 italic">Teacher-assign mode requires workshop functionality (not implemented in this demo). Students can use student-select mode to choose their reviewees.</p>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <script>
        // Simple form validation if needed
        document.addEventListener('DOMContentLoaded', function() {
            // You can add any additional form validation here if needed
            console.log('Review rating forms ready');
        });
    </script>
</x-app-layout>