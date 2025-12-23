<?php

namespace App\Filament\Widgets;

use App\Filament\Actions\BookingActions;
use App\Filament\Resources\BookingPayments\BookingPaymentResource;
use App\Filament\Resources\Bookings\Schemas\BookingForm;
use App\Models\Booking;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;
use Filament\Widgets\Widget;
use Guava\Calendar\Contracts\ContextualInfo;
use Guava\Calendar\Enums\CalendarViewType;
use Guava\Calendar\Filament\Actions\CreateAction;
use Guava\Calendar\Filament\CalendarWidget as FilamentCalendarWidget;
use Guava\Calendar\ValueObjects\DateClickInfo;
use Guava\Calendar\ValueObjects\FetchInfo;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use Guava\Calendar\Enums\Context;
use Guava\Calendar\ValueObjects\DateSelectInfo;
use Guava\Calendar\ValueObjects\EventClickInfo;
use Guava\Calendar\ValueObjects\EventDropInfo;
use Illuminate\Database\Eloquent\Model;
use Livewire\Form;

class CalendarWidget extends FilamentCalendarWidget
{
    // protected CalendarViewType $calendarView = CalendarViewType::ListDay;
    protected bool $eventClickEnabled = true;
    protected bool $dateClickEnabled  = true;
    // protected bool $dateSelectEnabled = true;
    // protected bool $eventResizeEnabled = true;


    // protected bool $eventDragEnabled = true;


    // public function getHeaderActions(): array
    // {
    //     return [
    //         Action::make('Create Booking')
    //             ->schema(
    //                 BookingForm::schema()
    //             ) 
    //     ];
    // }  
    protected CalendarViewType $calendarView = CalendarViewType::ListDay;

       
public function mount(): void
{
    $this->calendarView = CalendarViewType::tryFrom(
        session('calendar_view')
    ) ?? CalendarViewType::ListDay;
}

protected $listeners = ['updateUserOverview' => '$refresh'];

public function setView(CalendarViewType $view)
{
    session([
        'calendar_view' => $view->value,
    ]);
 
    // full page reload
    return redirect(request()->header('Referer'));
}

        public function getHeaderActions(): array
        {
            return [
                Action::make('month')
                    ->label('Month')
                    ->action(fn () => $this->setView(CalendarViewType::DayGridMonth))
                    ->color(fn () =>
                        $this->calendarView === CalendarViewType::DayGridMonth
                            ? 'primary'
                            : 'gray'
                    ),

                Action::make('day')
                    ->label('Day')
                    ->action(fn () => $this->setView(CalendarViewType::TimeGridDay))
                    ->color(fn () =>
                        $this->calendarView === CalendarViewType::TimeGridDay
                            ? 'primary'
                            : 'gray'
                    ),
            ];
        }
       
protected function getCalendarConfig(): array
{
    return [
        'initialView' => $this->calendarView->value,
        'headerToolbar' => false,
    ];
}



    public function editBookingAction(): EditAction
    {
        return $this->editAction(\App\Models\Booking::class)
            ->label('Edit Booking')
           
            ->extraModalFooterActions([
                Action::make('saveAndCreateAnother')
                    ->label('Save & Add Another')
                    ->color('gray')
                    ->action(function (array $data) {
                        // Custom logic here
                    }),
            ])
            ->extraModalFooterActions([ 
                    BookingActions::complete(), 
                    BookingActions::confirm(),
                    BookingActions::cancel(),
                    BookingActions::makePayment() 
            ])
            ->after(fn() => $this->refreshRecords());
    }

    public function createFollowupBookingAction():CreateAction{
         return $this->createAction(\App\Models\Booking::class)
            ->label('Add followup Booking')
            ->extraModalFooterActions([
                Action::make('saveAndCreateAnother')
                    ->label('Save & Add Another')
                    ->color('gray')
                    ->action(function (array $data) {
                        // Custom logic here
                    }),
            ])->mountUsing(function (Schema $form,$arguments) {
                $form->fill($arguments);
                // ...
            })
            ->after(fn() => $this->refreshRecords());
    }

    public function createBookingAction(): CreateAction
    {
        return $this->createAction(\App\Models\Booking::class)
            ->label('Create Booking')
             ->mountUsing(function (array $arguments, Form $form) {
                    $form->fill([
                        'customer_id'  => $arguments['customer_id'] ?? null,
                        'listing_id'   => $arguments['listing_id'] ?? null,
                        'therapist_id' => $arguments['therapist_id'] ?? null,
                    ]);
                })
            ->fillForm(function (?ContextualInfo $info) {
                // You can now access contextual info from the calendar using the $info argument
                if ($info instanceof DateClickInfo) {
                    return [
                        'start_time' => $info?->date?->toDateTimeString(),
                        'end_time'   => $info?->date?->toDateTimeString(),
                    ];
                }
            })
            ->extraModalFooterActions([
                Action::make('saveAndCreateAnother')
                    ->label('Save & Add Another')
                    ->color('gray')
                    ->action(function (array $data) {
                        // Custom logic here
                    }),
            ])
            // ->extraModalFooterActions([
            //     Action::make('Confirm Booking')
            //     ->color(Color::Blue)
            //     ->visible(function($record){
            //         return $record->status == 'pending';
            //     })->action(function($record){
            //         $record->status = 'confirmed';
            //         $record->save();
            //     }),
            //     Action::make('Cancel Booking')
            //     ->visible(function($record){
            //         return $record->status == 'pending' || $record->status == 'confirmed';
            //     })->action(function($record){
            //         $record->status = 'canceled';
            //         $record->save();
            //     })
            //     ->color('danger'),
            //     // ViewAction::make(),
            //     Action::make('Make Payment')
            //         ->schema(BookingPaymentResource::schema())
            //         ->visible(function ($record) {
            //             return $record->canAddPayment();
            //         })
            //         ->action(function ($record, array $data): void {
            //             // ...
            //             $data['payment_status'] = 'paid';
            //             $record->payments()->create($data);

            //             $totalPaid = $record->totalPayment();
            //             $bookingPrice = $record->price;

            //             if ($totalPaid < $bookingPrice) {
            //                 $record->update(['payment_status' => 'partially_paid']);
            //             } else {
            //                 $record->update(['payment_status' => 'paid']);
            //             }
            //         })->after(function () {
            //             $this->dispatch('paymentsRelationManager');
            //         }),


            //     DeleteAction::make(),
            // ])
            ->after(fn() => $this->refreshRecords());
    }
    protected function getDateClickContextMenuActions(): array
    {
        return [
            $this->createBookingAction(),
            // Any other action you want
        ];
    }

    public function createFooAction(): CreateAction
    {
        // You can use our helper method
        // return $this->createAction(Booking::class);

        // Or you can add it manually, both variants are equivalent:
        return CreateAction::make('createFoo')
            ->model(Booking::class);
    }


    protected function onEventDrop(EventDropInfo $info, Model $event): bool
    {
        // Access the updated dates using getter methods
        $newStart = $info->event->getStart();
        $newEnd = $info->event->getEnd();

        // Update the event with the new start/end dates to persist the drag & drop
        $event->update([
            'start_time' => $newStart,
            'end_time' => $newEnd,
        ]);
        // Return true to accept the drop and keep the event in the new position
        return true;
    }
    protected function getEvents(FetchInfo $info): Collection | array | Builder
    {
        // The simplest way:
        // return Booking::query();

        // You probably want to query only visible events:
        return Booking::query()
            // ->confirmed()
            ->whereDate('end_time', '>=', $info->start)
            ->whereDate('start_time', '<=', $info->end);
    }


    protected function getEventClickContextMenuActions(): array
    {
        return [
             
            $this->editBookingAction(),
            $this->deleteAction(),
        ];
    }
}
