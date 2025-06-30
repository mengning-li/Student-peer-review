<x-app-layout> 
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $course->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Edit the assessment</h3>
                    <form action="{{ route('assessment.update', [$course->id, $assessment->id]) }}" method="POST">
                        @csrf
                        @method('PUT')                      
                        <!-- Assessment Title -->
                        <div class="mb-4">
                            <x-input-label for="title" :value="__('Assessment Title')" />
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" value="{{ $assessment->title }}"/>
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <!-- Instruction -->
                        <div class="mb-4">
                            <x-input-label for="instruction" :value="__('Instruction')" />
                            <textarea class="form-control w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" name="instruction">{{ $assessment->instruction}}</textarea>
                            <x-input-error :messages="$errors->get('instruction')" class="mt-2" />
                        </div>

                        <!-- Number of Reviews Required -->
                        <div class="mb-4">
                            <x-input-label for="reviews" :value="__('Number of Reviews Required')" />
                            <x-text-input id="required_reviews" class="block mt-1 w-full" type="number" name="required_reviews" value="{{ $assessment->required_reviews}}"/>
                            <x-input-error :messages="$errors->get('required_reviews')" class="mt-2" />
                        </div>
                        
                        <!-- Maximum Score -->
                        <div class="mb-4">
                            <x-input-label for="max_score" :value="__('Maximum Score')" />
                            <x-text-input id="max_score" class="block mt-1 w-full" type="number" name="max_score" value="{{ $assessment->max_score}}"/>
                            <x-input-error :messages="$errors->get('max_score')" class="mt-2" />
                        </div>

                        <!-- Due Date and Time -->
                        <div class="mb-4">
                            <x-input-label for="due_date" :value="__('Due Date and Time')" />
                            <x-text-input id="due_date" class="block mt-1 w-full" type="text" name="due_date" placeholder="YYYY-MM-DD HH:MM" value="{{ $assessment->due_date}}"/>
                            <small class="text-gray-600">Please use the format: YYYY-MM-DD HH:MM (24-hour format).</small>
                            <x-input-error :messages="$errors->get('due_date')" class="mt-2" />
                        </div>


                        <!-- Peer Review Type -->
                        <div class="mb-4">
                            <x-input-label for="type" :value="__('Peer Review Type')" />
                            <select id="type" name="type" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="student-select" {{ old('type') == 'student-select' ? 'selected' : '' }}>Student-Select</option>
                                <option value="teacher-assign" {{ old('type') == 'teacher-assign' ? 'selected' : '' }}>Teacher-Assign</option>
                            </select>
                            <x-input-error :messages="$errors->get('type')" class="mt-2" />
                        </div>

                        <!-- Submit Button -->
                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-dark">{{ __('Update') }}</button>
                        </div>

                    </form>

                    <!-- Delete Assessment Section -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <form id="delete-assessment-form" action="{{ route('assessment.delete', [$course->id, $assessment->id]) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="button" onclick="confirmDelete()" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                {{ __('Delete Assessment') }}
                            </button>
                        </form>
                        <p class="text-sm text-gray-600 mb-4">
                            {{ __('Once you delete this assessment, all related reviews and scores will be permanently deleted. This action cannot be undone.') }}
                        </p>
                        
                        
                    </div>

                    <script>
                        function confirmDelete() {
                            if (confirm('Are you sure you want to delete this assessment? This action cannot be undone and will delete all related reviews and scores.')) {
                                document.getElementById('delete-assessment-form').submit();
                            }
                        }
                    </script>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
