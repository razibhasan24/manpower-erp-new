<?php

use App\Http\Controllers\TrainingCourseController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'permission:training-course.view'])->group(function () {
    Route::get('training-courses', [TrainingCourseController::class, 'index'])->name('training-courses.index');
});
Route::middleware(['auth', 'permission:training-course.create'])->group(function () {
    Route::get('training-courses/create', [TrainingCourseController::class, 'create'])->name('training-courses.create');
    Route::post('training-courses', [TrainingCourseController::class, 'store'])->name('training-courses.store');
});
Route::middleware(['auth', 'permission:training-course.edit'])->group(function () {
    Route::get('training-courses/{trainingCourse}/edit', [TrainingCourseController::class, 'edit'])->whereNumber('trainingCourse')->name('training-courses.edit');
    Route::put('training-courses/{trainingCourse}', [TrainingCourseController::class, 'update'])->whereNumber('trainingCourse')->name('training-courses.update');
});
Route::middleware(['auth', 'permission:training-course.delete'])->group(function () {
    Route::delete('training-courses/{trainingCourse}', [TrainingCourseController::class, 'destroy'])->whereNumber('trainingCourse')->name('training-courses.destroy');
});
