<?php

namespace App\Filament\Actions;

use App\BookingStatus;
use App\Filament\Resources\BookingPayments\BookingPaymentResource as BookingPaymentsBookingPaymentResource;
use App\Filament\Resources\Bookings\BookingResource;
use App\Filament\Resources\Bookings\Schemas\BookingForm;
use App\Models\Booking;
use App\Models\BookingPaymentResource; // Ensure this is the correct path to your schema
use App\Models\CustomerPostAssesment;
use Filament\Actions\Action;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Size;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;

class BookingActions
{
    public static function confirm(): Action
    {
        return Action::make('confirmBooking')
            ->label('Confirm Booking')
            ->color('primary')
            ->visible(fn(Booking $record) => $record->canConfirm())
            ->action(function (Booking $record) { 
                $record->update(['status' => 'confirmed']);
            })
            ->after(function ($livewire) {
                if (
                    $livewire &&
                    method_exists($livewire, 'refreshRecords')
                ) {
                    $livewire->refreshRecords();
                }
            })
            ->requiresConfirmation();
    }

    public static function complete($ref = 'calendar'): Action
    {
        return Action::make('completeBooking')
            ->visible(function ($record) {
                return $record->canComplete();
            })
            ->size(Size::Small)
            ->action(function ($record, $livewire, $data) use ($ref) {
                if (! $record->canComplete()) {
                    // Show notification and stop execution
                    Notification::make()
                        ->title('Cannot complete booking')
                        ->body('Either payment is not yet complete or conditions are not met.')
                        ->danger()
                        ->send();

                    return; // stop the action
                }
                $data['booking_id'] = $record->id;
                $data['customer_id'] = $record->customer_id;

                $record->update(['status' => 'completed']);
                CustomerPostAssesment::create($data);
                Notification::make()
                    ->title('Booking successfully completed')
                    ->success()
                    ->send();
                // do whatever (save assessment, update booking, etc.)
                // Send data to the next modal
                if ($data['require_followup']) {

                    if ($ref == 'form') {
                         BookingResource::getUrl('create', 
                         [
                                'customer_id' => $record->customer_id,
                                'listing_id' => $record->listing_id,
                                'therapist_id' => $record->therapist_id,
                                'price' => $record->price,
                                'selected_date' => $data['next_session_date']
                            ]
                        );
                        
                    }

                    $livewire->replaceMountedAction('createFollowupBookingAction', [
                        'customer_id' => $record->customer_id,
                        'listing_id' => $record->listing_id,
                        'therapist_id' => $record->therapist_id,
                        'price' => $record->price,
                        'selected_date' => $data['next_session_date']
                    ]);
                }
            })
            ->icon(Heroicon::Check)
            // ->color('success')
            ->after(function ($livewire) {
                if (
                    $livewire &&
                    method_exists($livewire, 'refreshRecords')
                ) {
                    $livewire->refreshRecords();
                }
            })
            ->steps(BookingForm::postAssessmentWizard());
    }
    // public static function complete(): Action
    // {
    //     return Action::make('completeBooking')
    //         ->label('Complete Booking')
    //         ->color('success')
    //         ->icon(Heroicon::Check)
    //         ->visible(function($record){
    //             return $record->canComplete(); 
    //         })
    //         // ->visible(fn(Booking $record) => $record->canComplete())
    //         ->action(function (Booking $record,$livewire) {
    //             if (! $record->canComplete()) {
    //                 // Show notification and stop execution
    //                 Notification::make()
    //                     ->title('Cannot complete booking')
    //                     ->body('Either payment is not yet complete or conditions are not met.')
    //                     ->danger()
    //                     ->send();

    //                 return; // stop the action
    //             }
    //             $record->update(['status' => 'completed']);


    //         })
    //         ->after(function ($livewire) {
    //             $livewire->refreshRecords();
    //         })

    //         ->requiresConfirmation();
    // }


    public static function cancel(): Action
    {
        return Action::make('cancelBooking')
            ->label('Cancel Booking')
            ->color('gray')
            ->visible(fn(Booking $record) => in_array($record->status, ['pending', 'confirmed']))
            ->action(function (Booking $record) {
                $record->update(['status' => 'canceled']);
            })
            ->after(function ($livewire) {
                if (
                    $livewire &&
                    method_exists($livewire, 'refreshRecords')
                ) {
                    $livewire->refreshRecords();
                }
            })
            ->requiresConfirmation();
    }

    public static function makePayment(): Action
    {
        return Action::make('makePayment')
            // ->modalHeading(function ($record) {
            //     return "P{$record->balance()} left to pay";
            // })
            // ->modalDescription('Make a payment for this booking')
            ->label('Make Payment')
            ->color('gray')
            ->schema(fn($record)=>BookingPaymentsBookingPaymentResource::schema($record->balance())) // Reusing your schema
            ->visible(fn(Booking $record) => $record->canAddPayment())
            ->action(function (Booking $record, array $data) {
                $data['payment_status'] = 'paid';
                $record->payments()->create($data);

                $payment_status = ($record->totalPayment() < $record->price)
                    ? 'partially_paid'
                    : 'paid';

                    

                $record->update([
                    'payment_status' => $payment_status,
                    'status'=>BookingStatus::Confirmed->value
                ]);

                
                 Notification::make()
                        ->title('Payment Successful')
                        ->body('Payment was successful applied')
                        ->success()
                        ->send();
            })
            ->after(function ($livewire) {

                if (
                    $livewire &&
                    method_exists($livewire, 'refreshRecords')
                ) {
                    $livewire->refreshRecords();
                }
            });
    }
}
