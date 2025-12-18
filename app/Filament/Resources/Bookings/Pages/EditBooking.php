<?php

namespace App\Filament\Resources\Bookings\Pages;

use App\Filament\Resources\BookingPayments\BookingPaymentResource;
use App\Filament\Resources\Bookings\BookingResource;
use App\Models\BookingPayment;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Colors\Color;

class EditBooking extends EditRecord
{
    protected static string $resource = BookingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Confirm Booking')
            ->color(Color::Blue)
            ->visible(function($record){
                return $record->status == 'pending';
            }),
            Action::make('Cancel Booking')
            ->visible(function($record){
                return $record->status == 'pending' || $record->status == 'confirmed';
            })
            ->color('danger'),
            // ViewAction::make(),
            Action::make('Make Payment')
                ->schema(BookingPaymentResource::schema())
                ->hidden(function ($record) {
                    return $record->payment_status == 'paid';
                })
                ->action(function ($record, array $data): void {
                    // ...
                    $data['payment_status'] = 'paid';
                    $record->payments()->create($data);

                    $totalPaid = $data['amount'];
                    $balance = $record->balance();

                    if ($totalPaid < $balance) {
                        $record->update(['payment_status' => 'partially_paid']);
                    } else {
                        $record->update(['payment_status' => 'paid']);
                    }
                })->after(function () {
                    $this->dispatch('paymentsRelationManager');
                })
                ->color(Color::Green),


            DeleteAction::make(),
        ];
    }
}
