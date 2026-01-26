<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HariLibur extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $table = 'holidays';

    protected $fillable = [
        'name',
        'date',
        'description',
        'is_recurring',
    ];
}
