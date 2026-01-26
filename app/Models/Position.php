<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'name', 'division_id', 'description'];

    public function division()
    {
        return $this->belongsTo(Division::class);
    }
}
