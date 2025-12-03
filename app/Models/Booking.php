<?php

namespace App\Models;

use App\Services\TimeslotService;
use Carbon\Carbon;
use Guava\Calendar\Contracts\Eventable;
use Guava\Calendar\ValueObjects\CalendarEvent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str as SupportStr;
use Psy\Util\Str;

class Booking extends Model implements Eventable
{
    use SoftDeletes;
    //
    protected $fillable = [
        'user_id',     // who booked
        'booking_number',     // who booked
        'customer_id',  // who bookedich listing
        'listing_id',  // which listing
        'therapist_id',
        'start_time',
        'end_time',
        'status',      // pending, confirmed, canceled, completed
        'notes',
        'title',
        'price',
        'type',
        'location',
    ];


    protected function getStatusColor(): string
    {
        return match ($this->status) {
            'pending'   => '#fbbf24', // amber
            'confirmed' => '#4ade80', // green
            'canceled'  => '#f87171', // red
            'completed' => '#60a5fa', // blue
            default     => '#9ca3af', // gray
        };
    }

    public $statuses = ['pending', 'confirmed', 'canceled', 'completed'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function therapist(){
        return $this->belongsTo(Therapist::class);
    }


    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }

    public function payments()
    {
        return $this->hasMany(BookingPayment::class);
    }


    public function totalPayment()
    {
        return $this->payments()->where('status', 'paid')->sum('amount');
    }

    public function balance()
    {
        return $this->listing->price - $this->totalPayment();
    }

    public function scopeConfirmed($q)
    {
        return $q->where('status', 'confirmed');
    }
    public function scopeCompleted($q)
    {
        return $q->where('status', 'completed');
    }

    public function toCalendarEvent(): CalendarEvent
    {
        return CalendarEvent::make($this)
            ->action('edit')
            ->title("{$this?->listing?->title} {$this?->title} {$this?->location} ")
            ->start($this->start_time)
            ->end($this->end_time)
            ->extendedProp('customer_name', $this->customer->name) 
            ->backgroundColor($this->getStatusColor()) 
        ;
    }


    /**
     * The "booted" method of the model.
     */
    protected static function boot(): void
    {
        parent::boot();

        // Before creating a new Customer
        static::creating(function ($booking) {
            // Generate a unique code
            $booking->user_id = auth()->user()->id;
        });

        // Before saving (both creating and updating)
        static::saving(function ($booking) {
            // Example: ensure name is title-cased
            $booking->user_id = auth()->user()->id;
        });
    }

    public static function availableTimeslots($date)
{
    $slots = TimeslotService::generateForDay($date);
    $bookings = self::whereDate('start_time', $date)->get();

    $available = [];

    foreach ($slots as $slot) {
        $overlap = false;

        foreach ($bookings as $booking) {
            $bStart = Carbon::parse($booking->start_time);
            $bEnd   = Carbon::parse($booking->end_time);

            if ($slot['start'] < $bEnd && $slot['end'] > $bStart) {
                $overlap = true;
                break;
            }
        }

        if (!$overlap) {
            $label = $slot['label'];
            $available[$label] = $label;
        }
    }

    return $available;
}

// public static function availableTimeslots($date)
// {
//     $slots = TimeslotService::generateForDay($date); // returns ['start' => Carbon, 'end' => Carbon, 'label' => '...']
//     $bookings = self::whereDate('start_time', $date)->get();

//     $timeslots = [];

//     foreach ($slots as $slot) {
//         $slotStart = Carbon::parse($slot['start']);
//         $slotEnd = Carbon::parse($slot['end']);

//         $overlap = false;

//         foreach ($bookings as $booking) {
//             $bStart = Carbon::parse($booking->start_time);
//             $bEnd = Carbon::parse($booking->end_time);

//             if ($slotStart < $bEnd && $slotEnd > $bStart) {
//                 $overlap = true;
//                 break;
//             }
//         }

//         // Convert to 12-hour format for display
//         $label = $slotStart->format('g:i A') . ' - ' . $slotEnd->format('g:i A');

//         $timeslots[$label] = [
//             'label' => $label,
//             'disabled' => $overlap, // mark overlap slots as disabled
//         ];
//     }

//     return $timeslots;
// }


}
