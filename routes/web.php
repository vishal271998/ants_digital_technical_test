<?php

use App\Http\Controllers\AttendanceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/attendance-report-with-sandwitch', [AttendanceController::class, 'reportWithSandwitch'])->name('attendance.reportWithSandwitch');

Route::get('/attendance-report-without-sandwitch', [AttendanceController::class, 'reportWithoutSandwitch'])->name('attendance.reportWithoutSandwitch');
