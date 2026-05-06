<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {
    Route::resource('students', App\Http\Controllers\StudentController::class);
    Route::get('attendances', [App\Http\Controllers\AttendanceLogController::class, 'index'])->name('attendances.index');
});
