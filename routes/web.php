<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\HolidayController;

$id = 10;
Route::get('/', function () {
    return view('holidayCalendar');
});

Route::get('/', [CountryController::class, 'countryList']);
Route::get('holidayCalendarList/{iso_3166}/{year}', [HolidayController::class, 'holiday']);
Route::get('insertEvent/{eventName}/{eventDate}/{eventType}/{iso_3166}', [HolidayController::class, 'insertEvent']);
Route::get('updateEvent/{eventId}/{eventName}/{eventDate}/{eventType}', [HolidayController::class, 'updateEvent']);
Route::get('deleteEvent/{eventId}', [HolidayController::class, 'deleteEvent']);