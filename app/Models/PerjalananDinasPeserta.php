<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerjalananDinasPeserta extends Model
{
    use HasFactory;

    protected $table = 'perjalanan_dinas_peserta';

    protected $fillable = [
        'perjalanan_dinas_id',
        'pegawai_id',
    ];

    public function perjalananDinas()
    {
        return $this->belongsTo(PerjalananDinas::class, 'perjalanan_dinas_id');
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }
}
