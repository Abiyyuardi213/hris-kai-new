<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'name', 'description'];

    public function positions()
    {
        return $this->hasMany(Position::class);
    }
}
