<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MutasiPegawai extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;
    use \App\Traits\HasOfficeScope;

    public function applyOfficeScope($builder, $officeId)
    {
        $builder->where(function ($q) use ($officeId) {
            $q->where('from_office_id', $officeId)
                ->orWhere('to_office_id', $officeId);
        });
    }

    protected $table = 'employee_mutations';

    protected $fillable = [
        'employee_id',
        'type',
        'from_division_id',
        'from_position_id',
        'from_office_id',
        'to_division_id',
        'to_position_id',
        'to_office_id',
        'mutation_code',
        'mutation_date',
        'reason',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $latest = static::orderBy('id', 'desc')->first();
            $number = $latest ? intval(substr($latest->mutation_code, 4)) + 1 : 1;
            $model->mutation_code = 'MUT-' . str_pad($number, 3, '0', STR_PAD_LEFT);
        });
    }

    public function employee()
    {
        return $this->belongsTo(Pegawai::class);
    }
    public function fromDivision()
    {
        return $this->belongsTo(Divisi::class, 'from_division_id');
    }
    public function fromPosition()
    {
        return $this->belongsTo(Jabatan::class, 'from_position_id');
    }
    public function fromOffice()
    {
        return $this->belongsTo(Kantor::class, 'from_office_id');
    }

    public function toDivision()
    {
        return $this->belongsTo(Divisi::class, 'to_division_id');
    }
    public function toPosition()
    {
        return $this->belongsTo(Jabatan::class, 'to_position_id');
    }
    public function toOffice()
    {
        return $this->belongsTo(Kantor::class, 'to_office_id');
    }

    public function getSkNumberAttribute()
    {
        // 1. Sequence and Number
        $sequence = $this->mutation_code;
        if ($this->mutation_code && str_starts_with($this->mutation_code, 'MUT-')) {
            $sequence = substr($this->mutation_code, 4); // Get the number part, e.g. 001
        } elseif (!$this->mutation_code) {
            $sequence = str_pad($this->id, 3, '0', STR_PAD_LEFT);
        } else {
            // Handle existing slashes if any, usually just take first part
            $parts = explode('/', $this->mutation_code);
            $sequence = $parts[0];
        }

        // 2. Office & Signer Code
        // Logic: Try to determine issuer based on To Office (Destination) or From Office
        $officeName = $this->toOffice->office_name ?? ($this->fromOffice->office_name ?? '');
        $officeNameLower = strtolower($officeName);
        $officeCode = 'KP';
        $signerCode = 'DZ';

        if ($officeNameLower) {
            if (str_contains($officeNameLower, 'daop 1')) {
                $officeCode = 'D1';
                $signerCode = 'VP';
            } elseif (str_contains($officeNameLower, 'daop 2')) {
                $officeCode = 'D2';
                $signerCode = 'VP';
            } elseif (str_contains($officeNameLower, 'daop 3')) {
                $officeCode = 'D3';
                $signerCode = 'VP';
            } elseif (str_contains($officeNameLower, 'daop 4')) {
                $officeCode = 'D4';
                $signerCode = 'VP';
            } elseif (str_contains($officeNameLower, 'daop 5')) {
                $officeCode = 'D5';
                $signerCode = 'VP';
            } elseif (str_contains($officeNameLower, 'daop 6')) {
                $officeCode = 'D6';
                $signerCode = 'VP';
            } elseif (str_contains($officeNameLower, 'daop 7')) {
                $officeCode = 'D7';
                $signerCode = 'VP';
            } elseif (str_contains($officeNameLower, 'daop 8')) {
                $officeCode = 'D8';
                $signerCode = 'VP';
            } elseif (str_contains($officeNameLower, 'daop 9')) {
                $officeCode = 'D9';
                $signerCode = 'VP';
            } elseif (str_contains($officeNameLower, 'divre iii')) {
                $officeCode = 'DV3';
                $signerCode = 'DV';
            } elseif (str_contains($officeNameLower, 'divre ii')) {
                $officeCode = 'DV2';
                $signerCode = 'DV';
            } elseif (str_contains($officeNameLower, 'divre iv')) {
                $officeCode = 'DV4';
                $signerCode = 'DV';
            } elseif (str_contains($officeNameLower, 'divre i')) {
                $officeCode = 'DV1';
                $signerCode = 'DV';
            } elseif (str_contains($officeNameLower, 'lrt')) {
                $officeCode = 'LRT';
                $signerCode = 'VP';
            } elseif (str_contains($officeNameLower, 'pusat')) {
                $officeCode = 'KP';
                $signerCode = 'DZ';
            }
        }

        // 3. Month Roman
        $months = [
            1 => 'I',
            2 => 'II',
            3 => 'III',
            4 => 'IV',
            5 => 'V',
            6 => 'VI',
            7 => 'VII',
            8 => 'VIII',
            9 => 'IX',
            10 => 'X',
            11 => 'XI',
            12 => 'XII'
        ];
        $month = date('n', strtotime($this->mutation_date));
        $monthRoman = $months[$month];
        $year = date('Y', strtotime($this->mutation_date));

        return "{$sequence}/SK/{$signerCode}/{$officeCode}/{$monthRoman}/{$year}";
    }
}
