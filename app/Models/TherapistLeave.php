<?php

namespace App\Models;

use Guava\Calendar\Contracts\Eventable;
use Guava\Calendar\ValueObjects\CalendarEvent;
use Illuminate\Database\Eloquent\Model;

class TherapistLeave extends Model implements Eventable
{


    public function therapist(){
        return $this->belongsTo(Therapist::class);
    }
    //
    public function toCalendarEvent(): CalendarEvent
    {
        return CalendarEvent::make($this)
            ->action('edit')
            ->title("{$this?->therapist?->name} {$this?->reason} ")
            ->start($this->start_date)
            ->end($this->end_date)
            ->backgroundColor('#000')
        ;
    }

}
