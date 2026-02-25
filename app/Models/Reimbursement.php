<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Reimbursement extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $fillable = [
        'pegawai_id',
        'tipe_reimbursement',
        'tanggal_pengajuan',
        'keterangan',
        'nominal',
        'lampiran',
        'status',
        'tanggal_approval',
        'catatan_approval',
        'approved_by',
    ];

    protected $casts = [
        'tanggal_pengajuan' => 'date',
        'tanggal_approval' => 'date',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
