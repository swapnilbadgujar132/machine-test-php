<?php

use App\Http\Controllers\MaterialCategoryController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\MaterialMovementController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MaterialController::class, 'index'])->name('home');

Route::resource('materials', MaterialController::class)->except('show');
Route::resource('material-categories', MaterialCategoryController::class)->except('show');
Route::resource('material-movements', MaterialMovementController::class)->only(['index', 'create', 'store']);
