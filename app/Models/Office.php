<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    use HasFactory;

    protected $fillable = [
        'office_code',
        'office_name',
        'office_address',
        'city_id',
        'phone_number',
        'email',
    ];

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
