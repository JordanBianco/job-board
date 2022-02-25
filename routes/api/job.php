<?php

use App\Http\Controllers\JobController;
use Illuminate\Support\Facades\Route;

Route::get('jobs', [JobController::class, 'index']);
Route::get('jobs/{job:slug}', [JobController::class, 'show']);