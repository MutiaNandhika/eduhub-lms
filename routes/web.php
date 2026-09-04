<?php

use App\Http\Controllers\Admin\AdminAnalyticsController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminCourseController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminReviewController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Instructor\CourseBuilderController;
use App\Http\Controllers\Instructor\InstructorAnalyticsController;
use App\Http\Controllers\Instructor\InstructorDashboardController;
use App\Http\Controllers\Instructor\InstructorStudentController;
use App\Http\Controllers\Public\CertificateVerifyController;
use App\Http\Controllers\Public\CourseCatalogController;
use App\Http\Controllers\Public\CourseDetailController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Student\CertificateController;
use App\Http\Controllers\Student\DashboardController;
use App\Http\Controllers\Student\EnrollmentController;
use App\Http\Controllers\Student\LearningController;
use App\Http\Controllers\Student\MyCoursesController;
use App\Http\Controllers\Student\NotificationController;
use App\Http\Controllers\Student\QuizController;
use App\Http\Controllers\Student\ReviewController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', HomeController::class)->name('home');
Route::get('/courses', CourseCatalogController::class)->name('courses.index');
Route::get('/courses/{slug}', CourseDetailController::class)->name('courses.show');
Route::get('/verify/{certificateNumber}', CertificateVerifyController::class)->name('certificates.verify');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

/*
|--------------------------------------------------------------------------
| Authenticated Student Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::get('/my-courses', MyCoursesController::class)->name('my-courses');
    Route::post('/courses/{course}/enroll', [EnrollmentController::class, 'enroll'])->name('courses.enroll');

    // Learning Player
    Route::get('/learning/{course}/{lesson}', [LearningController::class, 'show'])->name('student.learning');
    Route::post('/learning/{lesson}/toggle-complete', [LearningController::class, 'toggleComplete'])->name('student.learning.complete');
    Route::post('/learning/{lesson}/progress', [LearningController::class, 'updateProgress'])->name('student.learning.progress');

    // Quizzes
    Route::get('/quiz/{quiz}', [QuizController::class, 'show'])->name('student.quiz.show');
    Route::post('/quiz/{quiz}/submit', [QuizController::class, 'submit'])->name('student.quiz.submit');
    Route::get('/quiz/{quiz}/results/{attempt}', [QuizController::class, 'result'])->name('student.quiz.result');

    // Certificates
    Route::get('/certificates', [CertificateController::class, 'index'])->name('student.certificates.index');
    Route::get('/certificates/{certificate}', [CertificateController::class, 'show'])->name('student.certificates.show');

    // Reviews
    Route::post('/courses/{course}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    // Profile
    Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [AuthController::class, 'updatePassword'])->name('profile.password');

    // Notifications API
    Route::get('/api/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
});

/*
|--------------------------------------------------------------------------
| Instructor Portal Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:instructor,admin'])->prefix('instructor')->name('instructor.')->group(function () {
    Route::get('/dashboard', InstructorDashboardController::class)->name('dashboard');
    Route::get('/analytics', InstructorAnalyticsController::class)->name('analytics');

    // Course Builder & Management
    Route::get('/courses', [CourseBuilderController::class, 'index'])->name('courses.index');
    Route::get('/courses/create', [CourseBuilderController::class, 'create'])->name('courses.create');
    Route::post('/courses', [CourseBuilderController::class, 'store'])->name('courses.store');
    Route::get('/courses/{course}/edit', [CourseBuilderController::class, 'edit'])->name('courses.edit');
    Route::put('/courses/{course}', [CourseBuilderController::class, 'update'])->name('courses.update');
    Route::delete('/courses/{course}', [CourseBuilderController::class, 'destroy'])->name('courses.destroy');
    Route::post('/courses/{course}/submit-review', [CourseBuilderController::class, 'submitForReview'])->name('courses.submit-review');
    Route::get('/courses/{course}/students', InstructorStudentController::class)->name('courses.students');

    // Module & Lesson Authoring
    Route::post('/courses/{course}/modules', [CourseBuilderController::class, 'storeModule'])->name('modules.store');
    Route::put('/modules/{module}', [CourseBuilderController::class, 'updateModule'])->name('modules.update');
    Route::delete('/modules/{module}', [CourseBuilderController::class, 'deleteModule'])->name('modules.destroy');

    Route::post('/modules/{module}/lessons', [CourseBuilderController::class, 'storeLesson'])->name('lessons.store');
    Route::put('/lessons/{lesson}', [CourseBuilderController::class, 'updateLesson'])->name('lessons.update');
    Route::delete('/lessons/{lesson}', [CourseBuilderController::class, 'deleteLesson'])->name('lessons.destroy');

    // Quiz Authoring
    Route::post('/quizzes/{quiz}', [CourseBuilderController::class, 'saveQuiz'])->name('quizzes.save');
});

/*
|--------------------------------------------------------------------------
| Admin Portal Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', AdminDashboardController::class)->name('dashboard');
    Route::get('/analytics', AdminAnalyticsController::class)->name('analytics');

    // Users
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::put('/users/{user}/role', [AdminUserController::class, 'updateRole'])->name('users.update-role');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

    // Courses Moderation
    Route::get('/courses', [AdminCourseController::class, 'index'])->name('courses.index');
    Route::post('/courses/{course}/approve', [AdminCourseController::class, 'approve'])->name('courses.approve');
    Route::post('/courses/{course}/reject', [AdminCourseController::class, 'reject'])->name('courses.reject');
    Route::post('/courses/{course}/archive', [AdminCourseController::class, 'archive'])->name('courses.archive');

    // Categories
    Route::get('/categories', [AdminCategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [AdminCategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{category}', [AdminCategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [AdminCategoryController::class, 'destroy'])->name('categories.destroy');

    // Reviews Moderation
    Route::get('/reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
    Route::delete('/reviews/{review}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');
});
