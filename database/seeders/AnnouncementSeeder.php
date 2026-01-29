<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AnnouncementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure there is at least one user to be the author
        $user = User::first();
        if (!$user) {
            $this->command->info('No users found. Skipping AnnouncementSeeder.');
            return;
        }

        $announcements = [
            [
                'title' => 'Pengumuman Libur Nasional Idul Fitri',
                'content' => '<p>Diberitahukan kepada seluruh karyawan bahwa sehubungan dengan Hari Raya Idul Fitri, maka kantor akan diliburkan mulai tanggal 10 April hingga 12 April 2024. Mohon menyelesaikan pekerjaan yang mendesak sebelum tanggal tersebut.</p><p>Terima kasih atas perhatiannya.</p>',
                'category' => 'Pengumuman',
                'is_active' => true,
                'published_at' => now()->subDays(2),
            ],
            [
                'title' => 'Update Kebijakan WFH',
                'content' => '<p>Mulai bulan depan, kebijakan Work From Home (WFH) akan disesuaikan kembali. Karyawan diwajibkan untuk hadir di kantor minimal 3 hari dalam seminggu.</p><p>Detail lebih lanjut akan dikirimkan melalui email masing-masing.</p>',
                'category' => 'Berita',
                'is_active' => true,
                'published_at' => now()->subDay(),
            ],
            [
                'title' => 'Maintenance Server Internal',
                'content' => '<p>Akan dilakukan pemeliharaan server internal pada hari Sabtu depan mulai pukul 22:00 hingga 06:00. Mohon maaf atas ketidaknyamanan yang ditimbulkan selama proses maintenance berlangsung.</p>',
                'category' => 'Teknis',
                'is_active' => false, // Not active yet
                'published_at' => now()->addDays(5),
            ],
            [
                'title' => 'Selamat Datang Karyawan Baru',
                'content' => '<p>Mari kita sambut karyawan baru yang bergabung di divisi IT dan HR minggu ini. Semoga dapat berkontribusi positif bagi perusahaan.</p>',
                'category' => 'Umum',
                'is_active' => true,
                'published_at' => now(),
            ],
        ];

        foreach ($announcements as $data) {
            Announcement::create(array_merge($data, [
                'author_id' => $user->id,
            ]));
        }
    }
}
