<?php

use App\Http\Controllers\ActivityController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/activities');
Route::resource('activities', ActivityController::class);