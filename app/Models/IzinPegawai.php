<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IzinPegawai extends Model
{
    protected $table = 'izin_pegawais';

    use \App\Traits\HasOfficeScope;

    public $officeScopeType = 'relation';
    public $officeScopeRelation = 'pegawai';

    protected $fillable = [
        'pegawai_id',
        'type',
        'start_date',
        'end_date',
        'reason',
        'attachment',
        'status',
        'approved_by',
        'admin_note',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
