<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    use HasFactory;

    protected $table = 'positions';



    public $officeScopeType = 'has_many_relation';
    public $officeScopeRelation = 'employees';

    protected $fillable = ['code', 'name', 'description', 'gaji_per_hari', 'tunjangan'];

    // public function division() relationship removed

    public function employees()
    {
        return $this->hasMany(Pegawai::class, 'jabatan_id');
    }
}
