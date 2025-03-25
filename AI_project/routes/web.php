<?php

use App\Http\Controllers\AssignmentsController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScheduleController;
use Illuminate\Support\Facades\Route;

Route::get('/', [Controller::class, 'welcome'])->name('welcome');
Route::get('/readme', [Controller::class, 'readme'])->name('readme');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard', [DashboardController::class, 'postMessage'])->name('dashboard.postMessage');

    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
    Route::post('/courses/{course}/register', [CourseController::class, 'register'])->name('courses.register');
    Route::post('/courses/{course}/unregister', [CourseController::class, 'unregister'])->name('courses.unregister');

    Route::get('/assignments', [AssignmentsController::class, 'index'])->name('assignments.index');
    Route::post('/assignments/{assignment}/complete', [AssignmentsController::class, 'complete'])->name('assignments.complete');

    Route::get('/calendar', [AssignmentsController::class, 'calendar'])->name('assignments.calendar');

    Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedule.index');
    Route::get('/schedule/create', [ScheduleController::class, 'create'])->name('schedule.create');
    Route::post('/schedule/store', [ScheduleController::class, 'store'])->name('schedule.store');
    Route::post('/schedule/{schedule}', [ScheduleController::class, 'destroy'])->name('schedule.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
