<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Therapist extends Model
{
    //
    protected $fillable = ['image','name','bio','availability','branch_id','email','phone','is_active'];
}
