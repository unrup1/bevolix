<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    protected $fillable = [
        'name', 'description', 'has_image', 'corona_hash', 'user_id'
    ];
}
