<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MutasiPegawai extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $table = 'employee_mutations';

    protected $fillable = [
        'employee_id',
        'type',
        'from_division_id',
        'from_position_id',
        'from_office_id',
        'to_division_id',
        'to_position_id',
        'to_office_id',
        'mutation_date',
        'reason',
        'file_sk',
    ];

    public function employee()
    {
        return $this->belongsTo(Pegawai::class);
    }
    public function fromDivision()
    {
        return $this->belongsTo(Divisi::class, 'from_division_id');
    }
    public function fromPosition()
    {
        return $this->belongsTo(Jabatan::class, 'from_position_id');
    }
    public function fromOffice()
    {
        return $this->belongsTo(Kantor::class, 'from_office_id');
    }

    public function toDivision()
    {
        return $this->belongsTo(Divisi::class, 'to_division_id');
    }
    public function toPosition()
    {
        return $this->belongsTo(Jabatan::class, 'to_position_id');
    }
    public function toOffice()
    {
        return $this->belongsTo(Kantor::class, 'to_office_id');
    }
}
