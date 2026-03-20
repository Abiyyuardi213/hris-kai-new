<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ProjectPayroll extends Model
{
    use HasUuids;
    use \App\Traits\HasOfficeScope;

    public $officeScopeType = 'relation';
    public $officeScopeRelation = 'pegawai';

    protected $fillable = [
        'pegawai_id',
        'project_name',
        'total_pay',
        'keterangan',
        'month',
        'year',
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
