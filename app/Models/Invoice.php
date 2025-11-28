<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    //
    protected $fillable = ['invoice_number','customer_id','booking_id','amount','invoice_date','due_date','status','items'];

    protected $casts = ['items'=>'array'];


    public function customer(){
        return $this->belongsTo(Customer::class);
    }

    public function booking(){
        return $this->belongsTo(Booking::class);
    }
}
