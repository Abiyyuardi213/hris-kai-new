<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Presensi extends Model
{
    use HasFactory, HasUuids, SoftDeletes;
    use \App\Traits\HasOfficeScope;

    public $officeScopeType = 'relation';
    public $officeScopeRelation = 'pegawai';

    protected $fillable = [
        'pegawai_id',
        'shift_kerja_id',
        'tanggal',
        'jam_masuk',
        'jam_pulang',
        'foto_masuk',
        'foto_pulang',
        'lokasi_masuk',
        'lokasi_pulang',
        'status',
        'terlambat',
        'pulang_cepat',
        'keterangan'
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function shift()
    {
        return $this->belongsTo(ShiftKerja::class, 'shift_kerja_id');
    }
}
