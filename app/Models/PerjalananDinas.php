<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class PerjalananDinas extends Model
{
    use HasFactory, SoftDeletes, HasUuids;
    use \App\Traits\HasOfficeScope;

    public $officeScopeType = 'relation';
    public $officeScopeRelation = 'pemohon';

    public static function generateNoSuratTugas()
    {
        $year = date('Y');
        $month = date('n');
        $romanMonths = ['', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
        $monthRoman = $romanMonths[$month];

        $latest = self::whereYear('created_at', $year)
            ->whereNotNull('no_surat_tugas')
            ->orderBy('no_surat_tugas', 'desc')
            ->first();

        $nextNumber = 1;
        if ($latest) {
            $parts = explode('/', $latest->no_surat_tugas);
            if (count($parts) > 1) {
                $nextNumber = (int)$parts[1] + 1;
            }
        }

        return sprintf("ST/%03d/KAI/%s/%d", $nextNumber, $monthRoman, $year);
    }

    protected $table = 'perjalanan_dinas';

    protected $fillable = [
        'pegawai_id',
        'no_surat_tugas',
        'tujuan',
        'keperluan',
        'tanggal_mulai',
        'tanggal_selesai',
        'jenis_transportasi',
        'estimasi_biaya',
        'realisasi_biaya',
        'status',
        'catatan_persetujuan',
        'disetujui_oleh',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'estimasi_biaya' => 'decimal:2',
        'realisasi_biaya' => 'decimal:2',
    ];

    public function pemohon()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }

    public function pengetuju()
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }

    public function peserta()
    {
        return $this->hasMany(PerjalananDinasPeserta::class, 'perjalanan_dinas_id');
    }

    // Many-to-many through peserta table
    public function pegawaiPeserta()
    {
        return $this->belongsToMany(Pegawai::class, 'perjalanan_dinas_peserta', 'perjalanan_dinas_id', 'pegawai_id');
    }
}
