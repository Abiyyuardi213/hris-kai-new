<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShiftPegawai extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $table = 'employee_shifts';

    protected $fillable = [
        'employee_id',
        'shift_id',
        'start_date',
        'end_date',
    ];

    public function employee()
    {
        return $this->belongsTo(Pegawai::class);
    }
    public function shift()
    {
        return $this->belongsTo(ShiftKerja::class);
    }
}
