<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Divisi extends Model
{
    use HasFactory;

    protected $table = 'divisions';

    use \App\Traits\HasOfficeScope;

    public $officeScopeType = 'has_many_relation';
    public $officeScopeRelation = 'employees';

    protected $fillable = ['directorate_id', 'code', 'name', 'description'];

    public function directorate()
    {
        return $this->belongsTo(Directorate::class);
    }

    public function employees()
    {
        return $this->hasMany(Pegawai::class, 'divisi_id');
    }
}
