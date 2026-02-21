<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KhqrController;
use App\Http\Controllers\Api\FrontendApiController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Frontend API Routes
Route::prefix('frontend')->group(function () {
    Route::get('/slides', [FrontendApiController::class, 'slides']);
    Route::get('/settings', [FrontendApiController::class, 'settings']);
    Route::get('/amenities', [FrontendApiController::class, 'amenities']); // Temporarily keeping if used generally, otherwise remove
    Route::get('/translations/{locale}', [FrontendApiController::class, 'translations']);
    
    // Blog API
    Route::get('/blog-posts', [FrontendApiController::class, 'blogPosts']);
    Route::get('/blog-posts/{slug}', [FrontendApiController::class, 'blogPost']);
    Route::get('/blog-categories', [FrontendApiController::class, 'blogCategories']);
    
    // Pages API
    Route::get('/pages/footer', [FrontendApiController::class, 'footerPages']);
    Route::get('/pages/header', [FrontendApiController::class, 'headerPages']);
    Route::get('/pages/{slug}', [FrontendApiController::class, 'page']);
    Route::get('/programs', [FrontendApiController::class, 'programs']);
    Route::get('/programs-filters', [FrontendApiController::class, 'programsFilters']);
    Route::get('/faculties', [FrontendApiController::class, 'faculties']);
    Route::get('/faculties/{slug}', [FrontendApiController::class, 'faculty']);
    Route::get('/departments/{slug}', [FrontendApiController::class, 'department']);
    Route::get('/campuses', [FrontendApiController::class, 'campuses']);
    Route::get('/campuses/{slug}', [FrontendApiController::class, 'campusDetail']);
});
