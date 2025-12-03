<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Therapist extends Model
{
    //
    protected $fillable = ['image','name','bio','availability','branch_id','email','phone','is_active'];



    public function booking(){
        return $this->hasMany(Booking::class);
    }
public function isAvailable($date, $startTime, $endTime)
{
    return !$this->bookings()
        ->where('date', $date)
        ->where(function ($q) use ($startTime, $endTime) {
            $q->whereBetween('start_time', [$startTime, $endTime])
              ->orWhereBetween('end_time', [$startTime, $endTime])
              ->orWhere(function ($q2) use ($startTime, $endTime) {
                  $q2->where('start_time', '<=', $startTime)
                     ->where('end_time', '>=', $endTime);
              });
        })
        ->exists();
}
}
