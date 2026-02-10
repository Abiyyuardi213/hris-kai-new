<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShiftKerja extends Model
{
    protected $table = 'shifts';
    protected $fillable = [
        'name',
        'require_qr',
        'start_time',
        'end_time',
    ];

    protected $casts = [
        'require_qr' => 'boolean',
    ];
}
