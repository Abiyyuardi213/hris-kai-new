<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CandidateDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidate_id',
        'document_type',
        'file_path',
        'file_name',
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }
}
