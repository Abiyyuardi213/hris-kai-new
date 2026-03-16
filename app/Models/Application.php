<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_vacancy_id',
        'candidate_id',
        'status',
        'interview_date',
        'admin_notes',
    ];

    public function jobVacancy()
    {
        return $this->belongsTo(JobVacancy::class);
    }

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }
}
