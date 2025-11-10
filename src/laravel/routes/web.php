<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\TaskController;

Route::get('/',function(){
    return view('home');
});

Route::get('/home', [HomeController::class,'index'])->name('home');

Route::get('/about',[PageController::class,'about']);
Route::get('/contact', [PageController::class, 'contact']);

Route::resource('tasks', TaskController::class);
