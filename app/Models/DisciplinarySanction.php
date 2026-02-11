<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DisciplinarySanction extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $fillable = [
        'employee_id',
        'type',
        'description',
        'start_date',
        'end_date',
        'document_path',
        'status',
    ];

    public function employee()
    {
        return $this->belongsTo(Pegawai::class, 'employee_id');
    }
}
