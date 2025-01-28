<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\VideoController;
use App\Http\Controllers\CourseController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::prefix('videos')->group(function () {
    Route::get('/', [VideoController::class, 'index']); 
    Route::get('{id}', [VideoController::class, 'show']); 
    Route::post('/', [VideoController::class, 'store']); 
    Route::put('{id}', [VideoController::class, 'update']); 
    Route::delete('{id}', [VideoController::class, 'destroy']);
});

Route::prefix('courses')->group(function () {
    Route::get('free', [CourseController::class, 'listFreeCourses']); 
    Route::get('paid', [CourseController::class, 'listPaidCourses']);
});

