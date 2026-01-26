<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    use HasFactory;

    protected $table = 'positions';

    protected $fillable = ['code', 'name', 'division_id', 'description'];

    public function division()
    {
        return $this->belongsTo(Divisi::class);
    }
}
