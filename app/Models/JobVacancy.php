<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobVacancy extends Model
{
    use HasFactory;

    protected $fillable = [
        'position_id',
        'title',
        'description',
        'requirements',
        'quantity',
        'status',
        'deadline',
    ];

    public function position()
    {
        return $this->belongsTo(Jabatan::class, 'position_id');
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}
