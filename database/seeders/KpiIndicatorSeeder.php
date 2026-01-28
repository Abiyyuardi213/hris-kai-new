<?php

namespace Database\Seeders;

use App\Models\KpiIndicator;
use Illuminate\Database\Seeder;

class KpiIndicatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $indicators = [
            [
                'name' => 'Kualitas Hasil Kerja',
                'description' => 'Akurasi, kelengkapan, dan kerapihan hasil kerja.',
                'weight' => 20,
                'category' => 'Kinerja',
            ],
            [
                'name' => 'Kedisiplinan & Absensi',
                'description' => 'Kepatuhan terhadap jam kerja dan prosedur absensi.',
                'weight' => 20,
                'category' => 'Perilaku',
            ],
            [
                'name' => 'Inisiatif & Inovasi',
                'description' => 'Kemampuan memberikan ide baru dan solusi masalah.',
                'weight' => 15,
                'category' => 'Kinerja',
            ],
            [
                'name' => 'Kerjasama Tim',
                'description' => 'Kemampuan berkolaborasi dengan rekan kerja.',
                'weight' => 15,
                'category' => 'Perilaku',
            ],
            [
                'name' => 'Penguasaan Bidang Teknis',
                'description' => 'Tingkat pemahaman terhadap jobdesc dan alat kerja.',
                'weight' => 30,
                'category' => 'Kompetensi',
            ],
        ];

        foreach ($indicators as $indicator) {
            KpiIndicator::create($indicator);
        }
    }
}
