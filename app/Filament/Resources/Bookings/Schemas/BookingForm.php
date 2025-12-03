<?php

namespace App\Filament\Resources\Bookings\Schemas;

use App\Filament\Resources\Customers\Schemas\CustomerForm;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Listing;
use App\Models\Therapist;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon; 
use Illuminate\Support\Facades\Storage;

class BookingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components(self::schema())->columns(3);
    }


    public static function schema($type = null): array
    {
        return [
            Group::make([
                Section::make('Booking Information')->schema([
                    TextInput::make('booking_number')
                        ->label('Booking Number')
                        ->readOnly()
                        ->afterStateHydrated(function ($state, callable $set) {
                            // Only populate if the field is empty
                            if (!$state) {
                                $latest = \App\Models\Booking::latest('id')->first();
                                $nextNumber = $latest ? $latest->id + 1 : 1;

                                $set('booking_number', 'BK-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT));
                            }
                        }),
                    Select::make('status')
                        ->options([
                            'pending' => 'Pending',
                            'confirmed' => 'Confirmed',
                            'canceled' => 'Canceled',
                            'completed' => 'Completed',
                        ])
                        ->afterStateHydrated(function ($state, callable $set) {
                            if (!$state) {
                                $set('status', 'pending');
                            }
                        })
                        ->required(),
                 
                        
                    Group::make([
                        TextInput::make('title')
                            ->label('Booking Title')
                            ->helperText('Enter a short descriptive title for this booking.')
                            ->required(),

                        TextInput::make('type')
                            ->label('Booking Type')
                            ->helperText('Start typing and you will see suggestions, but you can type a custom type too.')
                            ->datalist([
                                'Room',
                                'Service',
                                'Event',
                                'Meeting',
                                'Consultation',
                                'Appointment',
                            ])
                            ->required(),
                        TextInput::make('price')
                            ->label('Booking Price')
                            ->columnSpanFull()
                            ->numeric()
                            ->helperText('Set the price for this booking. '),
                    ])->columns(2)
                    ->hidden(config('booking.has_listings')),
                ]),
                Section::make([

                    Select::make('customer_id')
                        ->relationship(name: 'customer', titleAttribute: 'name')
                        ->options(Customer::query()->pluck('name', 'id'))
                        ->hidden($type == "customers")
                        ->searchable() 
                        ->createOptionForm(
                            CustomerForm::schema()
                        )
                        ->columnSpanFull()
                        ->required(),
                   
                    Select::make('listing_id')
                        ->hidden($type == "listings")
                        ->relationship(name: 'listing', titleAttribute: 'title')
                        ->searchable()
                        ->hidden(!config('booking.has_listings'))
                        ->options(Listing::query()->pluck('title', 'id'))
                        ->loadingMessage('Loading listings...')
                        ->reactive() // make it reactive to trigger callbacks
                        ->afterStateUpdated(function ($state, callable $set) {
                            $listing = Listing::find($state);
                            if ($listing) {
                                $set('price', $listing->price); // update the price field dynamically
                            } else {
                                $set('price', null);
                            }
                        })
                        ->columnSpanFull(), 
                        Hidden::make('start_time'),
                        Hidden::make('end_time'), 

                    TextInput::make('price')
                        ->label('Booking Price')

                        ->columnSpanFull()
                        ->numeric()
                        ->hidden(!config('booking.has_listings'))
                        ->helperText('Set the price for this booking. '),
                    Textarea::make('notes')
                        ->columnSpanFull(),
                ])->columns(2),

            ])->columnSpan(2),
            Group::make([
                Section::make([
                    DatePicker::make('selected_date')
                        ->label('Select Date')
                        ->afterStateHydrated(function ($state, callable $set, callable $get) {
                            $startTime = $get('start_time');

                            if ($startTime) {
                                $date = $startTime instanceof Carbon ? $startTime : Carbon::parse($startTime);
                                $set('selected_date', $date->toDateString());
                            }
                        })
                        ->required()
                        ->reactive(),

                    ToggleButtons::make('available_timeslots')
                        ->hidden(function(callable $get){
                            return $get('selected_date') == null;
                        })
                        ->options(function (callable $get) {
                            $date = $get('selected_date');

                            if (!$date) return [];

                            return Booking::availableTimeslots($date);
                        })
                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                            if (!$state) {
                                return;
                            }

                            [$start, $end] = explode(' - ', $state);

                            // ✔ Correct: use $get() to read the selected date
                            $date = $get('selected_date');

                            // ✔ Correct: use $set() with 2 arguments to save datetime values
                            $set('start_time', "$date $start");
                            $set('end_time', "$date $end");
                        })
                        ->inline()
                        ->reactive(),
                         Select::make('therapist_id')
                         ->required()
                        ->label('Assign Therapist')
                        ->hidden(function(callable $get){
                            return $get('available_timeslots') == null;
                        })
                        ->options(function (callable $get) {
                            $date = $get('date');
                            $start = $get('start_time');
                            $end = $get('end_time');

                            if (!$date || !$start || !$end) {
                                return Therapist::pluck('name', 'id');
                            }

                            return Therapist::all()
                                ->filter(fn ($t) => $t->isAvailable($date, $start, $end))
                                ->pluck('name', 'id');
                        })
                        ->preload()
                        ->columnSpan(1),

                ]),

            ])->columnSpan(1)
        ];
    }
}
