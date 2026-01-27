<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aset extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $table = 'assets';

    protected $fillable = [
        'code',
        'name',
        'category',
        'serial_number',
        'purchase_date',
        'condition',
        'office_id',
        'division_id',
        'description',
    ];

    public function office()
    {
        return $this->belongsTo(Kantor::class, 'office_id');
    }

    public function division()
    {
        return $this->belongsTo(Divisi::class, 'division_id');
    }
}
