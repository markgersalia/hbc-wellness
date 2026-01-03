<?php

namespace App\Livewire;

use App\Filament\Resources\Bookings\Schemas\BookingForm;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Illuminate\Contracts\View\View;
use Filament\Schemas\Schema;
use Livewire\Component;

class ReservationForm extends Component {
    
    
    public ?array $data = [];
    
    public ?string $selected_date = null;
    public ?string $time_slot = null;
     public function getAvailableTimeSlotsProperty()
    {
        if (! $this->selected_date) {
            return [];
        }

        return \App\Models\Booking::availableTimeslots($this->selected_date);
    }

    
    public function render(): View
    {
        return view('livewire.reservation-form');
    }

    public function checkAvailability(){
        dd("sdasd");
    }
}
