<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    protected $fillable = [
        'url', 'ip', 'country', 'country_name', 'city',
        'latitude', 'longitude', 'user_agent', 'referer',
        'device_type', 'browser', 'os',
    ];
}