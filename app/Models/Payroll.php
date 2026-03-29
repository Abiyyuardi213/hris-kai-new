<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Payroll extends Model
{
    use HasUuids;
    use \App\Traits\HasOfficeScope;

    public $officeScopeType = 'relation';
    public $officeScopeRelation = 'pegawai';

    protected $fillable = [
        'pegawai_id',
        'month',
        'year',
        'type',
        'jumlah_hadir',
        'gaji_pokok',
        'tunjangan_jabatan',
        'tunjangan_perumahan',
        'tunjangan_admin_bank',
        'tunjangan_jpk',
        'tunjangan_pajak',
        'er_jamsostek_jkk',
        'er_jamsostek_jht',
        'er_jamsostek_jkm',
        'tunjangan_jpk_pensiun',
        'tunjangan_jp_bpjs',
        'potongan_mandiri_inhealth',
        'thr_days',
        'thr',
        'bonus',
        'keterangan_bonus',
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
