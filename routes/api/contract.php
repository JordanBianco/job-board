<?php

use App\Http\Controllers\ContractController;
use Illuminate\Support\Facades\Route;

Route::get('contracts', [ContractController::class, 'index']);