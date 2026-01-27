<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Payroll extends Model
{
    use HasUuids;

    protected $fillable = [
        'pegawai_id',
        'month',
        'year',
        'jumlah_hadir',
        'gaji_harian',
        'tunjangan_jabatan',
        'total_gaji',
        'status',
        'paid_at',
        'generated_by',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }
}
