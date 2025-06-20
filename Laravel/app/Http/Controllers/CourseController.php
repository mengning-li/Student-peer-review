<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB; 
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use App\Models\Enrollment;
use App\Models\Assessment;


class CourseController extends Controller
{

    // Get the course list for current user
    public function index()
    {
        
        $user = Auth::user();

        $courses = $user->enrolledCourses; 

        return view('course.home', compact('courses', 'user'));
    }

    // Get the course detail for current user
    public function show($id)
    {
        // Find the course by id
        $course = Course::find($id);
        $teachers = $course->users()->where('role', 'teacher')->get();
        
        // Get assessments with scores for the current user
        $currentUserId = auth()->id();
        $assessments = $course->assessments()->with(['assessmentScores' => function($query) use ($currentUserId) {
            $query->where('user_id', $currentUserId);
        }])->get();

        // Return the course details view with both course and teachers
        return view('course.detail', compact('course', 'teachers', 'assessments'));
    }

    // Store for new assessment
    public function store(Request $request, $course_id)
    {
        // Validate the incoming request
        $validation = $request->validate([
            'title' => 'required|max:20',
            'instruction' => 'required',
            'required_reviews' => 'required|integer|min:1',
            'max_score' => 'required|integer|min:1|max:100',
            'due_date' => 'required|date_format:Y-m-d H:i',
            'type' => 'required|in:student-select,teacher-assign',
        ]);
        
        $due_date = Carbon::createFromFormat('Y-m-d H:i', $validation['due_date']);
        // Create the new assessment
        Assessment::create([
            'title' => $validation['title'],
            'instruction' => $validation['instruction'],
            'required_reviews' => $validation['required_reviews'],
            'max_score' => $validation['max_score'],
            'due_date' => $due_date, 
            'type' => $validation['type'],
            'course_id' => $course_id,
        ]);

        // Redirect back with a success message
        return redirect()->route('course.show', $course_id);
    }

    // Function to diplay add student form with unenrolled students
        public function showAddStudentForm($course_id)
    {
        // Fetch the course by ID
        $course = Course::findOrFail($course_id);

        // Fetch students who are not enrolled in the course
        $unenrolledStudents = User::whereDoesntHave('enrollments', function ($query) use ($course_id) {
            $query->where('course_id', $course_id);
        })
        ->where('role', 'student')
        ->get();

        // Pass the course and the unenrolled students to the view
        return view('course.add_student', compact('course', 'unenrolledStudents'));
    }


        // Add a student to the course
        public function enrollStudent(Request $request, $course_id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $course = Course::findOrFail($course_id);
        $student = User::findOrFail($request->user_id);

        $enrollment = new Enrollment([
            'course_id' => $course_id,
            'user_id' => $student->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $enrollment->save();

        return redirect()->route('home', $course_id)->with('success', 'Student enrolled successfully.');
    }

        // Upload a file and read the file to add information
        public function uploadCourseFile(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'course_file' => 'required|file|mimes:json',
        ]);

        // Read the JSON file content
        $filePath = $request->file('course_file')->store('coursesUpload', 'public');
        $fileContent = file_get_contents(Storage::disk('public')->path($filePath));
        $courseData = json_decode($fileContent, true);

        // Check if JSON was properly decoded
        if ($courseData === null) {
            $jsonError = json_last_error_msg();
            return redirect()->back()->with('error', 'Invalid JSON file. Error: ' . $jsonError . '. Please check the file format.')->withInput();
        }

        // Check if all required keys exist
        $requiredKeys = ['CourseCode', 'CourseName', 'Teachers', 'Assessments', 'Students'];
        foreach ($requiredKeys as $key) {
            if (!isset($courseData[$key])) {
                return redirect()->back()->with('error', "Missing required field: $key in JSON file.")->withInput();
            }
        }

        // Extract data from JSON
        $courseCode = $courseData['CourseCode'];
        $courseName = $courseData['CourseName'];
        $teachers = $courseData['Teachers'];
        $assessments = $courseData['Assessments'];
        $students = $courseData['Students'];

        // VALIDATION PHASE - Check everything BEFORE creating anything
        
        // Check if a course with the same code already exists
        $existingCourse = Course::where('code', $courseCode)->first();
        if ($existingCourse) {
            return redirect()->back()->with('error', 'A course with this code already exists.')->withInput();
        }

        // Validate all assessments have required fields
        foreach ($assessments as $index => $assessmentData) {
            $requiredAssessmentKeys = ['Title', 'Instruction', 'MaxScore', 'DueDate', 'Type', 'RequiredReviews'];
            foreach ($requiredAssessmentKeys as $key) {
                if (!isset($assessmentData[$key])) {
                    return redirect()->back()->with('error', "Missing required field: $key in assessment #" . ($index + 1) . ".")->withInput();
                }
            }
        }

        // Validate all teachers exist in the database
        $teacherUsers = [];
        foreach ($teachers as $sNumber) {
            $teacher = User::where('sNumber', $sNumber)->first();
            if (!$teacher) {
                return redirect()->back()->with('error', "Teacher with sNumber $sNumber not found.")->withInput();
            }
            $teacherUsers[] = $teacher; // Store for later use
        }

        // CREATION PHASE - All validations passed, now create everything
        
        // Create the course
        $course = new Course([
            'code' => $courseCode,
            'name' => $courseName,
        ]);
        $course->save();

        // Add assessments
        foreach ($assessments as $assessmentData) {
            $course->assessments()->create([
                'title' => $assessmentData['Title'],
                'instruction' => $assessmentData['Instruction'],
                'max_score' => $assessmentData['MaxScore'],
                'due_date' => $assessmentData['DueDate'],
                'type' => $assessmentData['Type'],
                'required_reviews' => $assessmentData['RequiredReviews'],
            ]);
        }

        // Attach validated teachers to the course
        foreach ($teacherUsers as $teacher) {
            $course->users()->syncWithoutDetaching($teacher->id);
        }

        // Handle students, create if they don't exist
        foreach ($students as $sNumber) {
            $student = User::firstOrCreate(
                ['sNumber' => $sNumber], // Check the sNumber field for uniqueness
                [
                    'name' => 'Default Student Name', // Provide default name
                    'email' => $sNumber . '@school.com', // Create a default email
                    'password' => bcrypt('defaultPassword'), // Default password
                    'role' => 'student' // Set the role as student in the user table
                ]
            );
            // Attach the student to the course without specifying a role in the pivot table
            $course->users()->syncWithoutDetaching($student->id);
        }

        // Redirect after processing all students
        return redirect()->route('home')->with('success', 'Course and its details have been uploaded successfully.');
    }

    // Method to download JSON template
    public function downloadTemplate()
    {
        $templatePath = public_path('templates/course_template.json');
        
        if (file_exists($templatePath)) {
            return response()->download($templatePath, 'course_template.json');
        }
        
        return redirect()->back()->with('error', 'Template file not found.');
    }
}
