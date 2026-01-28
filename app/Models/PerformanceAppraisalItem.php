<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerformanceAppraisalItem extends Model
{
    use HasFactory;

    protected $table = 'performance_appraisal_items';

    protected $fillable = [
        'performance_appraisal_id',
        'kpi_indicator_id',
        'score',
        'comment',
    ];

    public function appraisal()
    {
        return $this->belongsTo(PerformanceAppraisal::class, 'performance_appraisal_id');
    }

    public function indicator()
    {
        return $this->belongsTo(KpiIndicator::class, 'kpi_indicator_id');
    }
}
