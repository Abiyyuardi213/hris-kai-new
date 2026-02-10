<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kantor extends Model
{
    use HasFactory;

    protected $table = 'offices';

    use \App\Traits\HasOfficeScope;

    public $officeScopeType = 'direct';
    public $officeScopeColumn = 'id';

    protected $fillable = [
        'office_code',
        'office_name',
        'office_address',
        'city_id',
        'phone_number',
        'email',
    ];

    public function city()
    {
        return $this->belongsTo(Kota::class);
    }

    public function employees()
    {
        return $this->hasMany(Pegawai::class, 'kantor_id');
    }
}
