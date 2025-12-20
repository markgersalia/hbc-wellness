<?php

namespace App\Filament\Actions;

use App\Filament\Resources\BookingPayments\BookingPaymentResource as BookingPaymentsBookingPaymentResource;
use App\Models\Booking;
use App\Models\BookingPaymentResource; // Ensure this is the correct path to your schema
use Filament\Actions\Action;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;

class BookingActions
{
    public static function confirm(): Action
    {
        return Action::make('confirmBooking')
            ->label('Confirm Booking')
            ->color('primary')
            ->visible(fn(Booking $record) => $record->status === 'pending')
            ->action(function (Booking $record) {
                $record->update(['status' => 'confirmed']);
            })
            ->requiresConfirmation();
    }
    public static function complete(): Action
    {
        return Action::make('completeBooking')
            ->label('Complete Booking')
            ->color('success')
            ->icon(Heroicon::Check)
            ->visible(fn(Booking $record) => $record->canComplete())
            ->action(function (Booking $record) {
                $record->update(['status' => 'completed']);
            })
            ->requiresConfirmation();
    }

    public static function cancel(): Action
    {
        return Action::make('cancelBooking')
            ->label('Cancel Booking')
            ->color('gray')
            ->visible(fn(Booking $record) => in_array($record->status, ['pending', 'confirmed']))
            ->action(function (Booking $record) {
                $record->update(['status' => 'canceled']);
            })
            ->requiresConfirmation();
    }

    public static function makePayment(): Action
    {
        return Action::make('makePayment')
            ->label('Make Payment')
            ->color('gray')
            ->schema(BookingPaymentsBookingPaymentResource::schema()) // Reusing your schema
            ->visible(fn(Booking $record) => $record->canAddPayment())
            ->action(function (Booking $record, array $data) {
                $data['payment_status'] = 'paid';
                $record->payments()->create($data);

                $status = ($record->totalPayment() < $record->price)
                    ? 'partially_paid'
                    : 'paid';

                $record->update(['payment_status' => $status]);
            });
    }
}
