<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Course: ') . $course->name }}
            <br>
            {{ __('Assessment: ') . $assessment->title }} - {{ $student->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="p-6 bg-white border-b border-gray-200">
                <p class="pb-3 fs-4">Submitted Reviews by {{ $student->name }}:</p>
                @if ($reviewSubmitted && $reviewSubmitted->count())
                    <div class="list-group">
                        @foreach ($reviewSubmitted as $review)
                            <div class="list-group-item d-flex justify-content-between align-items-start">
                                <div class="ms-2 me-auto">
                                    <div class="fw-bold">{{ $review->name }}:</div>
                                    <span class="d-block">{{ $review->review_content }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p>No submitted reviews found.</p>
                @endif
            </div>

                <div class="p-6 bg-white border-b border-gray-200">
                    <p class="pb-3 fs-4">Received Reviews for {{ $student->name }}:</p>
                    @if ($reviewReceived&& $reviewReceived->count())
                        <div class="list-group">
                            @foreach ($reviewReceived as $review)
                                <div class="list-group-item d-flex justify-content-between align-items-start">
                                    <div class="ms-2 me-auto">
                                        <div class="fw-bold">{{ $review->name }}:</div>
                                        <span class="d-block">{{ $review->review_content }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p>No received reviews found.</p>
                    @endif
                </div>

                <div class="p-6 bg-white border-b border-gray-200">
                    <h4><strong>Current Score:</strong></h4>
                    @if (!empty($assessment_score))
                        {{ $assessment_score->score }}
                    @else
                        <p>Not assign yet.</p>
                    @endif
                </div>

                <!-- Score for teacher to assign -->
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('student.score', [$course->id, $assessment->id, $student->id]) }}" method="POST">
                        @csrf
                        <label for="score">Assign Score:</label>
                        <input class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" id="score" type="number" name="score" value="{{ old('score', $student->score ?? '') }}" >
                        <x-input-error :messages="$errors->get('score')" class="mt-2" />
                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-dark">{{ __('Save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
