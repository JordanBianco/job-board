<?php

use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

Route::get('tags', [TagController::class, 'index']);