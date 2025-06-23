# 📚 Student Peer Review System

A comprehensive Laravel-based web application designed for educational institutions to facilitate peer review processes between students. This system enables teachers to create courses, manage assessments, and students to submit and review each other's work in a structured, organized manner.

## ✨ Features

### 👨‍🏫 For Teachers
- **Course Management**: Create and manage courses with automatic enrollment
- **Assessment Creation**: Design peer review assessments with customizable parameters
- **Bulk Course Creation**: Upload multiple courses via JSON template files
- **Student Management**: Add students to courses and monitor their progress
- **Grading System**: Assign scores to students based on their peer review participation
- **Progress Tracking**: Monitor student review completion and quality
- **Teacher Dashboard**: Overview of all taught courses and assessment statistics

### 👨‍🎓 For Students
- **Course Enrollment**: Automatic enrollment in assigned courses
- **Peer Review Submission**: Submit reviews for assigned or selected peers
- **Review Management**: View received reviews and provide feedback ratings
- **Score Tracking**: Monitor personal scores across different assessments
- **Assessment Dashboard**: Clear overview of all course assessments and deadlines
- **Interactive Feedback**: Rate and provide feedback on received reviews

### 🔄 Review System
- **Two Review Types**:
  - **Student-Select**: Students choose their own reviewees
  - **Teacher-Assign**: Teachers assign specific review partnerships
- **Rating System**: 1-5 star rating system for review quality
- **Score Assignment**: Reviewers can assign scores (0-100) to reviewees
- **Feedback Loop**: Students can provide feedback on reviews they receive
- **Review Tracking**: Complete audit trail of all review activities

## 🏗️ Architecture

### Technology Stack
- **Backend**: Laravel 11 (PHP)
- **Frontend**: Blade Templates with Tailwind CSS
- **Database**: SQLite (easily configurable to other databases)
- **Authentication**: Laravel Breeze
- **UI Framework**: Bootstrap 5 + Tailwind CSS
- **File Processing**: JSON template system for bulk operations



## 🚀 Installation


## 📁 Project Structure

```
Laravel/
├── app/
│   ├── Http/Controllers/     # Route controllers
│   ├── Models/              # Eloquent models
│   └── ...
├── database/
│   ├── migrations/          # Database schema
│   ├── seeders/            # Sample data
│   └── factories/          # Model factories
├── resources/
│   ├── views/              # Blade templates
│   ├── css/                # Stylesheets
│   └── js/                 # JavaScript files
├── routes/
│   └── web.php             # Web routes
└── public/
    └── templates/          # JSON template files
```


## 📝 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).



**Built with ❤️ using Laravel, Tailwind CSS, and modern web technologies.**