<?php

use App\Http\Controllers\JobController;
use Illuminate\Support\Facades\Route;

Route::get('jobs', [JobController::class, 'index']);
Route::get('jobs/{job:slug}', [JobController::class, 'show']);

Route::middleware('auth:sanctum')->group(function() {
    Route::post('jobs', [JobController::class, 'store']);
    Route::patch('jobs/{job:id}/update', [JobController::class, 'update']);
    Route::delete('jobs/{job:id}/delete', [JobController::class, 'destroy']);
});