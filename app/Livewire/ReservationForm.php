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
    
    
     public $currentStep = 2;
    public $totalSteps = 4;

    // Step 1: Service Selection
    public $selectedService = '';
    public $services = [];

    // Step 2: Date & Time
    public $selectedDate = '';
    public $selectedTime = '';
    public $availableTimes = [];

    // Step 3: Personal Information
    public $name = '';
    public $email = '';
    public $phone = '';
    public $notes = '';

    // Step 4: Confirmation
    public $bookingConfirmed = false;

    protected $rules = [
        // 'selectedService' => 'required',
        'selectedDate' => 'required|date|after_or_equal:today',
        'selectedTime' => 'required',
        'name' => 'required|string|min:2',
        'email' => 'required|email',
        'phone' => 'required|string|min:10',
        'notes' => 'nullable|string|max:500',
    ];

    protected $messages = [
        'selectedService.required' => 'Please select a service',
        'selectedDate.required' => 'Please select a date',
        'selectedTime.required' => 'Please select a time slot',
        'name.required' => 'Your name is required',
        'email.required' => 'Your email is required',
        'email.email' => 'Please enter a valid email address',
        'phone.required' => 'Your phone number is required',
    ];

    public function mount()
    {
        // Load services from your database
        $this->services = [
            ['id' => 1, 'name' => 'Haircut', 'duration' => '30 min', 'price' => '$50'],
            ['id' => 2, 'name' => 'Hair Coloring', 'duration' => '90 min', 'price' => '$120'],
            ['id' => 3, 'name' => 'Styling', 'duration' => '45 min', 'price' => '$60'],
            ['id' => 4, 'name' => 'Treatment', 'duration' => '60 min', 'price' => '$80'],
        ];
    }

    public function updatedSelectedDate()
    {
        // Generate available time slots based on selected date
        $this->availableTimes = $this->generateTimeSlots();
    }

    private function generateTimeSlots()
    {
        $slots = [];
        $start = 9; // 9 AM
        $end = 17; // 5 PM

        for ($hour = $start; $hour < $end; $hour++) {
            $slots[] = sprintf('%02d:00', $hour);
            $slots[] = sprintf('%02d:30', $hour);
        }

        return $slots;
    }

    public function nextStep()
    {
        $this->validateCurrentStep();

        if ($this->currentStep < $this->totalSteps) {
            $this->currentStep++;
        }
    }

    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function goToStep($step)
    {
        if ($step <= $this->currentStep || $step == 1) {
            $this->currentStep = $step;
        }
    }

    private function validateCurrentStep()
    {
        if ($this->currentStep == 1) {
            $this->validate(['selectedService' => 'required']);
        } elseif ($this->currentStep == 2) {
            $this->validate([
                'selectedDate' => 'required|date|after_or_equal:today',
                'selectedTime' => 'required',
            ]);
        } elseif ($this->currentStep == 3) {
            $this->validate([
                'name' => 'required|string|min:2',
                'email' => 'required|email',
                'phone' => 'required|string|min:10',
            ]);
        }
    }

    public function submitBooking()
    {
        $this->validate();

        // Create appointment in database
        // Appointment::create([
        //     'service_id' => $this->selectedService,
        //     'appointment_date' => $this->selectedDate,
        //     'appointment_time' => $this->selectedTime,
        //     'customer_name' => $this->name,
        //     'customer_email' => $this->email,
        //     'customer_phone' => $this->phone,
        //     'notes' => $this->notes,
        //     'status' => 'pending',
        // ]);

        $this->bookingConfirmed = true;
        $this->currentStep = 4;

        // Send confirmation email (optional)
        // Mail::to($this->email)->send(new BookingConfirmation(...));
    }

    public function resetForm()
    {
        $this->reset();
        $this->currentStep = 1;
        $this->mount();
    }
    
    public function render(): View
    { 
        return view('livewire.home.reservation-form');
    }

    public function checkAvailability(){
        dd("sdasd");
    }
}
