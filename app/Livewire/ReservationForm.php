<?php

namespace App\Livewire;

use Livewire\Component;
use Carbon\Carbon;
use App\Models\Booking;
use App\Services\BookingAvailabilityService;

class ReservationForm extends Component
{
    public $selected_date;
    public $time_slot;

    public $start_time;
    public $end_time;

    public $branch_id = 1; // or pass via mount()

    /**
     * Computed property
     */
    public function getAvailableTimeSlotsProperty()
    {
        if (! $this->selected_date) {
            return [];
        }

        $slots = Booking::availableTimeslots($this->selected_date);

        return collect($slots)->filter(function ($label, $value) {
            [$start, $end] = explode(' - ', $value);

            return BookingAvailabilityService::hasAvailableTherapist(
                date: $this->selected_date,
                slotStart: $start,
                slotEnd: $end,
                branchId: $this->branch_id
            );
        });
    }

    /**
     * When user selects a time slot
     */
    public function updatedTimeSlot($value)
    {
        if (! $value || ! $this->selected_date) {
            $this->reset(['start_time', 'end_time']);
            return;
        }

        [$start, $end] = explode(' - ', $value);

        $this->start_time = Carbon::parse("{$this->selected_date} {$start}");
        $this->end_time   = Carbon::parse("{$this->selected_date} {$end}");
    }

    public function submit()
    {
        $this->validate([
            'selected_date' => 'required|date',
            'time_slot'     => 'required',
        ]);

        // You can now create booking / inquiry here
        // start_time & end_time are already prepared

        session()->flash('success', 'Booking request submitted!');
    }

    public function render()
    {
        return view('livewire.reservation-form');
    }
}
