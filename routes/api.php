<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ToolManagementController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\BookmarkController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


//User Management
Route::group(['prefix'  => 'user'], function() {
    Route::post( '/add', [UserController::class, 'addUser'] )->name( 'add.user' );
});

//Admin Management
Route::group(['prefix'  => 'admin'], function() {
    Route::get( '/get-users', [AdminController::class, 'getAllUsers'] )->name( 'admin.get.users' );
    Route::get( '/user-action', [AdminController::class, 'userAction'] )->name( 'admin.user.action' );
    Route::post( '/update-settings', [AdminController::class, 'updateAdminSettings'] )->name( 'admin.update.settings' );
    Route::get( '/get-pending-reviews', [AdminController::class, 'getPendingReviews'] )->name( 'admin.get.pending.reviews' );
    Route::get( '/get-pending-comments', [AdminController::class, 'getPendingComments'] )->name( 'admin.get.pending.comments' );
    Route::get( '/admin-reviews-actions', [AdminController::class, 'adminReviewsActions'] )->name( 'admin.reviews.actions' );
    Route::get( '/admin-comments-actions', [AdminController::class, 'adminCommentsActions'] )->name( 'admin.comments.actions' );
    Route::get( '/clicks-analytics', [AdminController::class, 'getClicksAnalytics'] )->name( 'admin.get.click.analytics' );

});

//Tool Management

//Categories
Route::group(['prefix' => 'categories'], function () {

    Route::post( '/add', [ToolManagementController::class, 'addCategory'])->name('add.category');
    Route::post( '/edit', [ToolManagementController::class, 'editCategory'] )->name('edit.category');
    Route::get( '/delete', [ToolManagementController::class, 'deleteCategory'] )->name('delete.category');
    Route::get( '/get-tools', [ToolManagementController::class, 'fetchToolsCategory'] )->name( 'category.get.tools' );
    Route::get( '/get', [ToolManagementController::class, 'fetchCategories'] )->name( 'category.get' );
});

//Platforms
Route::group(['prefix' => 'platforms'], function () {

    Route::post( '/add', [ToolManagementController::class, 'addPlatform'])->name('add.platform');
    Route::post( '/edit', [ToolManagementController::class, 'editPlatform'] )->name('edit.platform');
    Route::get( '/delete', [ToolManagementController::class, 'deletePlatform'] )->name('delete.platform');
    Route::get( '/get-tools', [ToolManagementController::class, 'fetchToolsPlatform'] )->name( 'platform.get.tools' );
    Route::get( '/get', [ToolManagementController::class, 'fetchPlatforms'] )->name( 'platform.get' );
});

//Tools
Route::group(['prefix' => 'tools'], function () {

    Route::post( '/add', [ToolManagementController::class, 'addTool'])->name('add.tool');
    Route::post( '/edit', [ToolManagementController::class, 'editTool'] )->name('edit.tool');
    Route::get( '/get-tools', [ToolManagementController::class, 'fetchTool'] )->name('fetch.tool');
    Route::get( '/delete', [ToolManagementController::class, 'deleteTool'] )->name('delete.tool');
});

//Reviews
Route::group(['prefix' => 'reviews'], function () {

    Route::post( '/add', [ToolManagementController::class, 'addReview'])->name('add.review');
    Route::get( '/get', [ToolManagementController::class, 'getReview'] )->name('get.review');
});

Route::group(['prefix' => 'comments'], function () {

    Route::post( '/add', [ToolManagementController::class, 'addComments'])->name('add.tool.comment');
    Route::get( '/get', [ToolManagementController::class, 'fetchAllComments'])->name('get.tool.comments');
});

//User Anayltics

//Interactions ( tool, courses, video, affiliate ) and retrieve.
Route::group(['prefix' => 'analytics'], function () {

    Route::post( '/tool-interaction', [UserController::class, 'addToolInteraction'])->name('add.tool.interaction');
    Route::post( '/course-interaction', [UserController::class, 'addCourseInteraction'])->name('add.course.interaction');
    Route::post( '/video-interaction', [UserController::class, 'addVideoInteraction'])->name('add.video.interaction');
    Route::post( '/affiliate-interaction', [UserController::class, 'addAffiliateInteraction'])->name('add.affiliate.interaction');
    Route::get( '/recent-interactions', [UserController::class, 'getRecentInteractionsData'] )->name( 'get.recent.interactions' );
});


// Video and Course Management
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


Route::prefix('bookmark')->group(function() {
    Route::post('/folder', [BookmarkController::class, 'createBookmarkFolder']); // Create Folder
    Route::post('/folder/{folderId}', [BookmarkController::class, 'updateBookmarkFolder']); //Update Folder
    Route::post('/folder/{folderId}/tool', [BookmarkController::class, 'addToolToFolder']); // Add Tool
    Route::delete('/folder/{folderId}/tool/{toolId}', [BookmarkController::class, 'removeToolFromFolder']); // Remove Tool
    Route::get('/folders', [BookmarkController::class, 'listFolders']); // List Folders
    Route::get('/folder/{folderId}/share', [BookmarkController::class, 'shareFolder']); // Share Folder
});

