<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Masjid extends Model
{
    protected $fillable = [
        'name','address','area','city','sect','latitude','longitude','fajr','zuhr','asr','maghrib','isha','juma_time','eid_time','phone','description','is_featured'
    ];
}
