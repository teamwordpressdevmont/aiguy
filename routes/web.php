<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AiToolDataController;
use App\Http\Controllers\BlogDataController;


Route::get('/', function () {
    return view('welcome');
});


Route::get('/index', [HomeController::class, 'index']);


Route::get('/ai-tools', [AiToolDataController::class, 'index'])->name('ai-tools.index');
Route::post('/ai-tools/store', [AiToolDataController::class, 'store']);
Route::get('/ai-tools/view', [AiToolDataController::class, 'view'])->name('ai-tools.view');

Route::get('/blog', [BlogDataController::class, 'index'])->name('blog.blog');
Route::post('/blog/store', [BlogDataController::class, 'store'])->name('blog.store')->middleware('auth');
Route::get('/blog/view', [BlogDataController::class, 'view'])->name('blog.blog-view');
