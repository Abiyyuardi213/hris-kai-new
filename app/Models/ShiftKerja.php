<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShiftKerja extends Model
{
    protected $table = 'shifts';
    protected $fillable = [
        'name',
        'start_time',
        'end_time',
    ];
}
