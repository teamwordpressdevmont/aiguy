<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AiToolDataController;
use App\Http\Controllers\BlogDataController;
use App\Http\Controllers\AiToolCategoryController;



Route::get('/', function () {
    return view('welcome');
});


Route::get('/index', [HomeController::class, 'index']);

Route::resource('tools', AiToolDataController::class);

Route::resource('categories', AiToolCategoryController::class);

Route::get('/ai-tools-category/create', [AiToolCategoryController::class, 'create'])->name('categories.create');
Route::post('/ai-tools-category', [AiToolCategoryController::class, 'store'])->name('categories.store');



Route::get('/ai-tools', [AiToolDataController::class, 'index'])->name('ai-tools.index');
Route::post('/ai-tools/store', [AiToolDataController::class, 'store']);
Route::get('/ai-tools/list', [AiToolDataController::class, 'list'])->name('ai-tools.list');
Route::get('/ai-tools/delete/{id}', [AiToolDataController::class, 'destroy'])->name('ai-tools.delete');
Route::get('/ai-tools/{id}', [AiToolDataController::class, 'view'])->name('ai-tools.view');

Route::prefix('ai-tools')->group(function () {

    // Route for showing the edit form
    Route::get('{id}/edit', [AiToolDataController::class, 'edit'])->name('tools.edit');

    // Route for updating the AI tool
    Route::put('{id}', [AiToolDataController::class, 'update'])->name('tools.update');
  
});


Route::get('/blog', [BlogDataController::class, 'index'])->name('blog.blog');
Route::post('/blog/store', [BlogDataController::class, 'store'])->name('blog.store');
Route::get('/blog/list', [BlogDataController::class, 'view'])->name('blog.blog-list');
