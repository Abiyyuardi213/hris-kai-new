<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class KpiIndicator extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $table = 'kpi_indicators';

    protected $fillable = [
        'name',
        'description',
        'weight',
        'category',
    ];
}
