<?php

namespace App\Filament\Resources\Bookings\Schemas;

use App\Filament\Resources\Customers\Schemas\CustomerForm;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Listing;
use Coolsam\Flatpickr\Forms\Components\Flatpickr;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

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
                ])->hidden(config('booking.has_listings')),
                Section::make([

                    Select::make('customer_id')
                        ->relationship(name: 'customer', titleAttribute: 'name')
                        ->options(Customer::query()->pluck('name', 'id'))
                        ->hidden($type == "customers")
                        ->searchable()
                        ->columnSpanFull()
                        ->createOptionForm(
                            CustomerForm::schema()
                        )
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
                    Group::make([
                        Flatpickr::make('start_time')
                            ->time(true)
                            ->time24hr(false)
                            ->minDate(fn() => today())
                            ->required(),
                        Flatpickr::make('end_time')

                            ->time(true)
                            ->time24hr(false)
                            ->minDate(fn() => today())
                            ->required(),
                    ])->columns(2)->columnSpanFull(),

                    TextInput::make('price')
                        ->label('Booking Price')

                        ->columnSpanFull()
                        ->numeric()
                        ->hidden(!config('booking.has_listings'))
                        ->helperText('Set the price for this booking. '),
                    Textarea::make('location')
                        ->columnSpanFull(),
                    Textarea::make('notes')
                        ->columnSpanFull(),
                ])->columns(2),

            ])->columnSpan(2),
            Group::make([

                Section::make([
                    TextInput::make('booking_number')
                        ->default(function () {
                            $latest = Booking::latest('id')->first();
                            $nextNumber = $latest ? $latest->id + 1 : 1;

                            return 'BK-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
                        })
                        ->readOnly(),
                    Select::make('status')
                        ->options([
                            'pending' => 'Pending',
                            'confirmed' => 'Confirmed',
                            'canceled' => 'Canceled',
                            'completed' => 'Completed',
                        ])
                        ->default('pending')
                        ->required(),
                ])
            ])->columnSpan(1)
        ];
    }
}
