<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Offboarding extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $fillable = [
        'pegawai_id',
        'tipe_offboarding',
        'tanggal_efektif',
        'alasan_keluar',
        'saran_masukan',
        'clearance_id_card',
        'clearance_laptop',
        'clearance_dokumen',
        'uang_pesangon',
        'status',
        'catatan_admin',
        'processed_by',
    ];

    protected $casts = [
        'tanggal_efektif' => 'date',
        'clearance_id_card' => 'boolean',
        'clearance_laptop' => 'boolean',
        'clearance_dokumen' => 'boolean',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }

    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}
