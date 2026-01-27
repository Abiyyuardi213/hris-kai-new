<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Pegawai extends Authenticatable
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory, \Illuminate\Database\Eloquent\SoftDeletes, \Illuminate\Database\Eloquent\Concerns\HasUuids, Notifiable;

    protected $table = 'pegawais';

    protected $fillable = [
        'status_pegawai_id',
        'sisa_cuti',
        'shift_kerja_id',
        'divisi_id',
        'jabatan_id',
        'kantor_id',
        'nip',
        'nik',
        'nama_lengkap',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'agama',
        'status_pernikahan',
        'alamat_ktp',
        'alamat_domisili',
        'no_hp',
        'email_pribadi',
        'tanggal_masuk',
        'tanggal_keluar',
        'foto',
    ];


    public function statusPegawai()
    {
        return $this->belongsTo(StatusPegawai::class, 'status_pegawai_id');
    }
    public function shift()
    {
        return $this->belongsTo(ShiftKerja::class, 'shift_kerja_id');
    }
    public function divisi()
    {
        return $this->belongsTo(Divisi::class, 'divisi_id');
    }
    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_id');
    }
    public function kantor()
    {
        return $this->belongsTo(Kantor::class, 'kantor_id');
    }
}
