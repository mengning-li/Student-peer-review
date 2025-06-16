<x-app-layout>    
    @auth
    <x-slot name="header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Course name: ') }} {{ $course->name }}
                <br>
                {{ __('Assessment Title: ') }}{{ $assessment->title }}
            </h2>
        </div>
    </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <p><strong>List of Useful Reviews</strong></p>
                    @if($usefulReviews->isEmpty())
                        <p>No useful reviews available.</p>
                    @else
                        <ul>
                            @foreach($usefulReviews as $review)
                                <li>
                                    <strong>Anonymous:</strong> {{ $review->review_content }}
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endauth    
</x-app-layout>
