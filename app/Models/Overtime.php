<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Overtime extends Model
{
    use HasUuids;
    use \App\Traits\HasOfficeScope;

    public $officeScopeType = 'relation';
    public $officeScopeRelation = 'pegawai';

    protected $fillable = [
        'pegawai_id',
        'date',
        'start_time',
        'end_time',
        'reason',
        'status',
        'type',
        'admin_note',
        'approved_by',
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
