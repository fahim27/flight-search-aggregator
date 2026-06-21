<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $guarded = ['id'];
    protected $hidden  = ['id'];

    protected $casts = [
        'flight_details' => 'object',
    ];
}
