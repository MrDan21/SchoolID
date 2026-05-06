<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AttendanceController;

Route::post('/attendances', [AttendanceController::class, 'recordAttendance'])
    ->middleware(\App\Http\Middleware\VerifyEsp32Token::class);
