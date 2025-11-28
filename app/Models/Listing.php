<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    //
     use HasFactory;

    protected $fillable = [
        'images',
        'title',
        'description',
        'type',    // e.g., room, service, event
        'price',   // optional
        'available_from', // optional JSON or boolean
        'available_to', // optional JSON or boolean
        'is_always_available', // optional JSON or boolean
    ];

    protected $appends = ['availability'];

    protected $casts = [
        'images' => 'array'
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function getAvailabilityAttribute(){
        if($this->is_always_available){
            return "Always Available";
        }
        return "Available on:" . $this->available_from . " to " . $this->available_to;
    }
} 
