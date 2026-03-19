<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobVacancy extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul_lowongan',
        'start_date',
        'end_date',
        'status',
    ];

    public function detail()
    {
        return $this->hasOne(JobVacancyDetail::class, 'job_vacancy_id');
    }

    public function formations()
    {
        return $this->hasMany(JobFormation::class, 'job_vacancy_id');
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}
