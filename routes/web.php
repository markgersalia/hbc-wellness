<?php

use App\Livewire\ExternalBookingForm;
use App\Livewire\RegistrationForm;
use App\Livewire\ReservationForm;
use Illuminate\Support\Facades\Route;


Route::get('/test-home', function () {
    return view('home');
});

Route::redirect('/', '/admin');

 
Route::get('/book', ReservationForm::class)->name('book');

