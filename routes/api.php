<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AffiliateLinkController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AIToolsController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\AcademyController;




Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/affiliate-links', [AffiliateLinkController::class, 'index']);
Route::get('/affiliate-links/{id}', [AffiliateLinkController::class, 'show']);
Route::post('/affiliate-links', [AffiliateLinkController::class, 'store']); 
Route::patch('/affiliate-links/{id}', [AffiliateLinkController::class, 'update']);
Route::delete('/affiliate-links/{id}', [AffiliateLinkController::class, 'destroy']);

Route::group(['middleware' => 'auth:sanctum'], function(){

Route::post('/tools/{toolId}/comments', [CommentController::class, 'postComment']);
Route::post('/comments/{commentId}/replies', [CommentController::class, 'replyToComment']);
Route::get('/tools/{toolId}/comments', [CommentController::class, 'listComments']);

});


Route::get('/notifications', [NotificationController::class, 'index']);
Route::post('/notifications', [NotificationController::class, 'store']);
Route::post('/notifications/assign', [NotificationController::class, 'assignToUser']);
Route::get('/user-notifications/{userId}', [NotificationController::class, 'userNotifications']);
Route::patch('/user-notifications/{id}/seen', [NotificationController::class, 'markAsSeen']);


Route::post('/admin/announcement', [AnnouncementController::class, 'sendAnnouncement']);
Route::post('/track-announcement', [AnnouncementController::class, 'trackAnnouncement']);



Route::post("login",[UserController::class,'index']);




//Categories
Route::group(['prefix' => 'categories'], function () {
    Route::get( '/get', [AIToolsController::class, 'fetchCategories1'] )->name( 'category.get' );
    Route::get( '/get2', [AIToolsController::class, 'fetchCategories2'] )->name( 'category.get2' );
});

Route::group( ['prefix' => 'blogs'], function() {
    Route::get( '/get-blogs', [BlogController::class, 'getBlogs'] )->name( 'get.all.blogs' );
    Route::get( '/get-single-blog', [BlogController::class, 'getSingleBlog'] )->name( 'get.single.blogs' );
} );

Route::group( ['prefix' => 'academy'], function() {
    Route::get( '/get', [AcademyController::class, 'getAcademies'] )->name( 'get.all.academy' );
} );

//AI Tools
Route::get('/ai-tools', [AIToolsController::class, 'getTools'])->name('aitools.gettools');
Route::get( '/single-ai-tool', [AIToolsController::class, 'getSingleTool'] )->name('aitools.getsingletool');