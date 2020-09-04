<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    protected $fillable = [
        'name', 'street', 'postalCode', 'location',
        'telephoneNumber',
        'startTime',
        'endTime',
        'facility_id'
    ];
}
