<?php

use App\Livewire\ExternalBookingForm;
use App\Livewire\RegistrationForm;
use App\Livewire\ReservationForm;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('home');
});

Route::get('/book', ReservationForm::class)->name('book');

