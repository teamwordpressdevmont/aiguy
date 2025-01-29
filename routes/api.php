<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ToolManagementController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


//Categories

Route::group(['prefix' => 'categories'], function () {

    Route::post( '/add', [ToolManagementController::class, 'addCategory'])->name('add.category');
    Route::post( '/edit', [ToolManagementController::class, 'editCategory'] )->name('edit.category');
    Route::get( '/delete', [ToolManagementController::class, 'deleteCategory'] )->name('delete.category');
    Route::get( '/get-tools', [ToolManagementController::class, 'fetchToolsCategory'] )->name( 'category.get.tools' );
    Route::get( '/get', [ToolManagementController::class, 'fetchCategories'] )->name( 'category.get' );
});

Route::group(['prefix' => 'platforms'], function () {

    Route::post( '/add', [ToolManagementController::class, 'addPlatform'])->name('add.platform');
    Route::post( '/edit', [ToolManagementController::class, 'editPlatform'] )->name('edit.platform');
    Route::get( '/delete', [ToolManagementController::class, 'deletePlatform'] )->name('delete.platform');
    Route::get( '/get-tools', [ToolManagementController::class, 'fetchToolsPlatform'] )->name( 'platform.get.tools' );
    Route::get( '/get', [ToolManagementController::class, 'fetchPlatforms'] )->name( 'platform.get' );
});

Route::group(['prefix' => 'tools'], function () {

    Route::post( '/add', [ToolManagementController::class, 'addTool'])->name('add.tool');
    Route::post( '/edit', [ToolManagementController::class, 'editTool'] )->name('edit.tool');
    Route::get( '/get-tools', [ToolManagementController::class, 'fetchTool'] )->name('fetch.tool');
    Route::get( '/delete', [ToolManagementController::class, 'deleteTool'] )->name('delete.tool');
});

Route::group(['prefix' => 'reviews'], function () {

    Route::post( '/add', [ToolManagementController::class, 'addReview'])->name('add.review');
    Route::get( '/get', [ToolManagementController::class, 'getReview'] )->name('get.review');
});

