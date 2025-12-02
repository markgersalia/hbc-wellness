<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Bookings\Schemas\BookingForm;
use App\Models\Booking;
use Filament\Actions\Action;
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

class CalendarWidget extends FilamentCalendarWidget
{
    // protected CalendarViewType $calendarView = CalendarViewType::ListWeek;
    protected bool $eventClickEnabled = true;
    protected bool $dateClickEnabled  = true;
    // protected bool $dateSelectEnabled = true;
    // protected bool $eventResizeEnabled = true;


    protected bool $eventDragEnabled = true;


    // public function getHeaderActions(): array
    // {
    //     return [
    //         Action::make('Create Booking')
    //             ->schema(
    //                 BookingForm::schema()
    //             ) 
    //     ];
    // }  
    
public function createBookingAction(): CreateAction
{
    return $this->createAction(\App\Models\Booking::class)
        ->label('Create Booking')
         ->fillForm(function (?ContextualInfo $info) { 
            // You can now access contextual info from the calendar using the $info argument
            if ($info instanceof DateClickInfo) {
                   return [
                    'start_time' => $info->date->toDateTimeString(),
                    'end_time'   => $info->date->toDateTimeString(),
                ];
            }
  
        })
        ->after(fn () => $this->refreshRecords());
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
            $this->editAction(),
            $this->deleteAction(),
        ];
    }
}
