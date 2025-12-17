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

class EditBooking extends EditRecord
{
    protected static string $resource = BookingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
             Action::make('Make Payment')
                ->schema(BookingPaymentResource::schema())
                ->action(function ($record, array $data): void {
                    // ...
                    $record->payments()->create($data);

                    $totalPaid = $data['amount'];
                    if ($totalPaid < $record->price) {
                        $record->update(['payment_status' => 'partially_paid']);
                    } 
                    else {
                        $record->update(['payment_status' => 'paid']);
                    }

                    })->after(function () {
                        $this->dispatch('paymentsRelationManager');
                    })
                    
                
        ];
    }
}
