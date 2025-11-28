<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['name', 'email', 'phone', 'address','isVip','image','created_by_id'];

    public function bookings(){
        return $this->hasMany(Booking::class);
    }
}
