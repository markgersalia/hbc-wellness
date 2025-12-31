<?php

use App\Livewire\ExternalBookingForm;
use App\Livewire\ReservationForm;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});


// Route::get('book',ReservationForm::class);