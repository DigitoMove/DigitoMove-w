<?php

use App\Http\Controllers\Admin\ApplicationController as AdminApplicationController;
use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Admin\LessonController as AdminLessonController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\ProjectController as AdminProjectController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Content\InquiryController;
use App\Http\Controllers\Content\PageController;
use App\Http\Controllers\Content\PageSectionController;
use App\Http\Controllers\dashboard\Analytics;
use App\Http\Controllers\PublicSiteController;
use Illuminate\Support\Facades\Route;

Route::controller(PublicSiteController::class)->group(function () {
    Route::get('/', 'home')->name('home');
    Route::get('/about', 'about')->name('about-us');
    Route::get('/services', 'services')->name('services.index');
    Route::get('/work', 'work')->name('work');
    Route::get('/exhibition', 'work')->name('exhibition.index');
    Route::get('/work/{project:slug}', 'project')->name('projects.show');
    Route::get('/blog', 'insights')->name('blog.index');
    Route::get('/blog/{post:slug}', 'post')->name('posts.show');
    Route::get('/learning', 'learning')->name('learning');
    Route::get('/learning/{course:slug}', 'course')->name('courses.show');
    Route::post('/learning/{course:slug}/apply', 'apply')->name('courses.apply');
    Route::get('/privacy', 'privacy')->name('privacy');
    Route::get('/contact', 'contact')->name('contact');
    Route::post('/contact', 'submitInquiry')->name('contact.submit');
    Route::get('/p/{page:slug}', 'page')->name('public.page');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])->name('login.store');
});
Route::post('/logout', [AuthController::class, 'destroy'])->middleware('auth')->name('logout');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [Analytics::class, 'index'])->name('dashboard-analytics');
    Route::redirect('/admin', '/dashboard')->name('admin');

    Route::resource('/content/pages', PageController::class)->except('show')->names('content-pages');
    Route::post('/content/pages/{page}/sections', [PageSectionController::class, 'store'])->name('page-sections.store');
    Route::put('/content/sections/{section}', [PageSectionController::class, 'update'])->name('page-sections.update');
    Route::delete('/content/sections/{section}', [PageSectionController::class, 'destroy'])->name('page-sections.destroy');

    Route::get('/inquiries', [InquiryController::class, 'index'])->name('inquiries.index');
    Route::patch('/inquiries/{inquiry}', [InquiryController::class, 'update'])->name('inquiries.update');
    Route::delete('/inquiries/{inquiry}', [InquiryController::class, 'destroy'])->name('inquiries.destroy');

    Route::get('/admin/profile', [AdminProfileController::class, 'edit'])->name('admin.profile.edit');
    Route::put('/admin/profile', [AdminProfileController::class, 'update'])->name('admin.profile.update');
    Route::resource('/admin/users', AdminUserController::class)->except('show')->names('admin.users');
    Route::resource('/admin/projects', AdminProjectController::class)->except('show')->names('admin.projects');
    Route::resource('/admin/posts', AdminPostController::class)->except('show')->names('admin.posts');
    Route::resource('/admin/courses', AdminCourseController::class)->except('show')->names('admin.courses');

    Route::get('/admin/courses/{course}/lessons/create', [AdminLessonController::class, 'create'])->name('admin.lessons.create');
    Route::post('/admin/courses/{course}/lessons', [AdminLessonController::class, 'store'])->name('admin.lessons.store');
    Route::get('/admin/lessons/{lesson}/edit', [AdminLessonController::class, 'edit'])->name('admin.lessons.edit');
    Route::put('/admin/lessons/{lesson}', [AdminLessonController::class, 'update'])->name('admin.lessons.update');
    Route::delete('/admin/lessons/{lesson}', [AdminLessonController::class, 'destroy'])->name('admin.lessons.destroy');

    Route::get('/admin/applications', [AdminApplicationController::class, 'index'])->name('admin.applications.index');
    Route::get('/admin/applications/{application}', [AdminApplicationController::class, 'show'])->name('admin.applications.show');
    Route::patch('/admin/applications/{application}', [AdminApplicationController::class, 'update'])->name('admin.applications.update');
    Route::patch('/admin/payments/{payment}', [AdminApplicationController::class, 'updatePayment'])->name('admin.payments.update');
});
