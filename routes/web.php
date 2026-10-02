<?php

use App\Http\Controllers\ActivityController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/activities');

// Route trash/restore harus dideklarasikan sebelum resource agar tidak tertangkap activities/{activity}.
Route::get('activities/trash', [ActivityController::class, 'trash'])->name('activities.trash');
Route::patch('activities/{activity}/restore', [ActivityController::class, 'restore'])
	->withTrashed()
	->name('activities.restore');

Route::resource('activities', ActivityController::class);