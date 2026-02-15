<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Divisi extends Model
{
    use HasFactory;

    protected $table = 'divisions';

    protected $fillable = ['directorate_id', 'code', 'name', 'description'];

    public function directorate()
    {
        return $this->belongsTo(Directorate::class);
    }

    public function employees()
    {
        return $this->hasMany(Pegawai::class, 'divisi_id');
    }

    public function mutationsFrom()
    {
        return $this->hasMany(MutasiPegawai::class, 'from_division_id');
    }

    public function mutationsTo()
    {
        return $this->hasMany(MutasiPegawai::class, 'to_division_id');
    }
}
