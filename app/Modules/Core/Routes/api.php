<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Core\Controllers\CampusController;

Route::prefix('api/core')->middleware('auth:sanctum')->group(function () {
    Route::apiResource('campuses', CampusController::class);
});
