<?php

use App\Http\Controllers\EventAttendeeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\SessionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('login', [SessionController::class, 'create'])->name('login');
Route::post('login', [SessionController::class, 'store'])->name('login.store');
Route::post('logout', [SessionController::class, 'destroy'])->name('logout');

Route::resource('events', EventController::class);
Route::post('events/{event}/attendees', [EventAttendeeController::class, 'store'])->name('events.attendees.store');
