<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AiToolDataController;
use App\Http\Controllers\BlogDataController;
use App\Http\Controllers\BlogCategoryController;
use App\Http\Controllers\AiToolCategoryController;
use App\Http\Controllers\CourseController;
// use App\Http\Controllers\CategoryController;


Route::get('/', function () {
    return view('welcome');
});


Route::get('/index', [HomeController::class, 'index']);




// Ai-tools-category
Route::resource('categories', AiToolCategoryController::class);
Route::get('/ai-tools-category/create', [AiToolCategoryController::class, 'create'])->name('categories.create');
Route::post('/ai-tools-category', [AiToolCategoryController::class, 'store'])->name('categories.store');
Route::get('/ai-tools-category/list', [AiToolCategoryController::class, 'showList'])->name('categories.list');
Route::get('/categories/{id}/edit', [AiToolCategoryController::class, 'edit'])->name('category.edit');
Route::put('categories/{id}', [AiToolCategoryController::class, 'update'])->name('categories.update');
Route::get('/categories/delete/{id}', [AiToolCategoryController::class, 'destroy'])->name('category.delete');
Route::get('categories/{id}', [AiToolCategoryController::class, 'show'])->name('categories.show');


// Ai-Tools
Route::resource('tools', AiToolDataController::class);

//Ai tool view
Route::get('/ai-tools', [AiToolDataController::class, 'index'])->name('ai-tools.index');
// Ai data store
Route::post('/ai-tools/store', [AiToolDataController::class, 'store']);
//List
Route::get('/ai-tools/list', [AiToolDataController::class, 'list'])->name('ai-tools.list');
//Delete
Route::get('/ai-tools/delete/{id}', [AiToolDataController::class, 'destroy'])->name('ai-tools.delete');
// View
Route::get('/ai-tools/{id}', [AiToolDataController::class, 'view'])->name('ai-tools.view');
// Edit & update
Route::get('/ai-tools/{id}/edit', [AiToolDataController::class, 'edit'])->name('tools.edit');
Route::put('/ai-tools/{id}', [AiToolDataController::class, 'update'])->name('tools.update');



// Blogs
Route::get('/blog', [BlogDataController::class, 'index'])->name('blog.index');
Route::post('/blog/store', [BlogDataController::class, 'store'])->name('blog.store');
Route::get('/blog/list', [BlogDataController::class, 'list'])->name('blog.blog-list');
Route::get('/blog/delete/{id}', [BlogDataController::class, 'destroy'])->name('blog.delete');
Route::get('/blog/{id}', [BlogDataController::class, 'view'])->name('blog.view');

Route::prefix('blog')->group(function () {

    // Route for showing the blog edit form
    Route::get('{id}/edit', [BlogDataController::class, 'edit'])->name('blog.edit');

    // Route for updating blog
    Route::put('{id}', [BlogDataController::class, 'update'])->name('blog.update');

});

// Blog Category
Route::resource('categories', BlogCategoryController::class);
Route::get('/blog-category/create', [BlogCategoryController::class, 'create'])->name('categories.create');
Route::post('/blog-category/store', [BlogCategoryController::class, 'store'])->name('categories.store');
Route::get('/blog-category/list', [BlogCategoryController::class, 'showList'])->name('categories.list');


Route::get('/categories/delete/{id}', [BlogCategoryController::class, 'destroy'])->name('category.delete');
// Route::get('categories/{id}', [BlogCategoryController::class, 'show'])->name('categories.show');


Route::prefix('categories')->group(function () {

    // Route for showing the blog category edit form
    Route::get('{id}/edit', [BlogCategoryController::class, 'edit'])->name('category.edit');

    // Route for updating blog category
    Route::put('{id}', [BlogCategoryController::class, 'update'])->name('categories.update');

});


// Courses
Route::resource('courses', CourseController::class);

// Category
// Route::resource('categories', CategoryController::class);
Route::get('/courses/delete/{id}', [CourseController::class, 'destroy'])->name('courses.delete');
