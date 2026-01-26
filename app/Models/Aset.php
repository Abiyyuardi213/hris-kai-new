<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aset extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $table = 'assets';

    protected $fillable = [
        'code',
        'name',
        'category',
        'serial_number',
        'purchase_date',
        'condition',
        'description',
    ];
}
