<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Directorate extends Model
{
    protected $fillable = ['code', 'name', 'description'];

    public function divisions()
    {
        return $this->hasMany(Divisi::class);
    }
}
