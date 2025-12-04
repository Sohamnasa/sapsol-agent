<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResumeController;

Route::post('/resume', [ResumeController::class, 'upload']);
Route::post('/match', [ResumeController::class, 'match']);