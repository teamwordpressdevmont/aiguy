<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AiToolDataController;
use App\Http\Controllers\BlogDataController;


Route::get('/', function () {
    return view('welcome');
});


Route::get('/index', [HomeController::class, 'index']);

Route::resource('tools', AiToolDataController::class);


Route::get('/ai-tools', [AiToolDataController::class, 'index'])->name('ai-tools.index');
Route::post('/ai-tools/store', [AiToolDataController::class, 'store']);
Route::get('/ai-tools/list', [AiToolDataController::class, 'view'])->name('ai-tools.list');

Route::prefix('ai-tools')->group(function () {
    // Route for showing the edit form
    Route::get('{id}/edit', [AiToolDataController::class, 'edit'])->name('tools.edit');

    // Route for updating the AI tool
    Route::put('{id}', [AiToolDataController::class, 'update'])->name('tools.update');

    // Route for deleting the AI tool
    Route::delete('{id}/destroy', [AiToolDataController::class, 'destroy'])->name('tools.destroy');
});


Route::get('/blog', [BlogDataController::class, 'index'])->name('blog.blog');
Route::post('/blog/store', [BlogDataController::class, 'store']);
Route::get('/blog/list', [BlogDataController::class, 'view'])->name('blog.blog-list');


