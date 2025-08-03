<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\TaskController;

Route::get('/', [HomeController::class,'index']);

Route::get('/about',[PageController::class,'about']);
Route::get('/contact', [PageController::class, 'contact']);

Route::resource('tasks', TaskController::class);