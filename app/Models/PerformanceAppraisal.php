<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class PerformanceAppraisal extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $table = 'performance_appraisals';

    protected $fillable = [
        'pegawai_id',
        'appraiser_id',
        'periode_mulai',
        'periode_selesai',
        'tahun',
        'total_score',
        'rating',
        'catatan_reviewer',
        'status',
    ];

    protected $casts = [
        'periode_mulai' => 'date',
        'periode_selesai' => 'date',
        'total_score' => 'decimal:2',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }

    public function appraiser()
    {
        return $this->belongsTo(User::class, 'appraiser_id');
    }

    public function items()
    {
        return $this->hasMany(PerformanceAppraisalItem::class);
    }

    public function calculateRating($score)
    {
        if ($score >= 90) return 'A+';
        if ($score >= 80) return 'A';
        if ($score >= 70) return 'B';
        if ($score >= 60) return 'C';
        return 'D';
    }
}
