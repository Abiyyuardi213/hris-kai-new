<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobFormation extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_vacancy_id',
        'formation_name',
        'education',
        'major',
        'gender',
        'document_requirements'
    ];

    public function vacancy()
    {
        return $this->belongsTo(JobVacancy::class, 'job_vacancy_id');
    }
}
