<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CandidateEducation extends Model
{
    protected $table = 'candidate_educations';

    protected $fillable = [
        'candidate_id',
        'degree_level',
        'major',
        'institution',
        'city',
        'graduation_date',
        'score',
        'accreditation',
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }
}
