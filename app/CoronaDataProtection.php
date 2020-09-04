<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CoronaDataProtection extends Model
{
    protected $fillable = [
        'privacy_name', 'facility_name', 'facility_address', 'facility_location', 'owner_name', 'telephone_number', 'facility_id'
    ];
}
