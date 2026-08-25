<?php

use App\Http\Controllers\EventAttendeeController;
use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('events', EventController::class);
Route::post('events/{event}/attendees', [EventAttendeeController::class, 'store'])->name('events.attendees.store');
