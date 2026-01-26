<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Divisi extends Model
{
    use HasFactory;

    protected $table = 'divisions';

    protected $fillable = ['code', 'name', 'description'];

    public function positions()
    {
        return $this->hasMany(Jabatan::class);
    }
}
