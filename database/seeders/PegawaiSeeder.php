<?php

namespace Database\Seeders;

use App\Models\Pegawai;
use App\Models\Divisi;
use App\Models\Jabatan;
use App\Models\Kantor;
use App\Models\ShiftKerja;
use App\Models\StatusPegawai;
use Illuminate\Database\Seeder;

class PegawaiSeeder extends Seeder
{
    public function run(): void
    {
        // Get or Create necessary related data
        $divisi = Divisi::first() ?? Divisi::create(['name' => 'IT Department', 'code' => 'IT']);
        $jabatan = Jabatan::first() ?? Jabatan::create(['name' => 'Software Engineer', 'code' => 'SE', 'division_id' => $divisi->id]);
        $kantor = Kantor::first() ?? Kantor::create(['office_name' => 'KAI HQ', 'office_address' => 'Bandung', 'office_code' => 'HQ']);
        $shift = ShiftKerja::first() ?? ShiftKerja::create(['name' => 'Regular', 'start_time' => '08:00', 'end_time' => '17:00']);
        $status = StatusPegawai::first() ?? StatusPegawai::create(['name' => 'Pegawai Tetap', 'code' => 'PT']);

        Pegawai::create([
            'nama_lengkap' => 'Budi Santoso',
            'nip' => '123456',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '1990-01-01',
            'nik' => '327100000000001',
            'jenis_kelamin' => 'L',
            'email_pribadi' => 'budi@gmail.com',
            'no_hp' => '08123456789',
            'tanggal_masuk' => '2020-01-15',
            'divisi_id' => $divisi->id,
            'jabatan_id' => $jabatan->id,
            'kantor_id' => $kantor->id,
            'shift_kerja_id' => $shift->id,
            'status_pegawai_id' => $status->id,
            'sisa_cuti' => 12,
            'alamat_domisili' => 'Jl. Merdeka No. 1, Bandung'
        ]);

        Pegawai::create([
            'nama_lengkap' => 'Ani Wijaya',
            'nip' => '654321',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1995-05-20',
            'nik' => '327100000000002',
            'jenis_kelamin' => 'P',
            'email_pribadi' => 'ani@gmail.com',
            'no_hp' => '08987654321',
            'tanggal_masuk' => '2021-03-01',
            'divisi_id' => $divisi->id,
            'jabatan_id' => $jabatan->id,
            'kantor_id' => $kantor->id,
            'shift_kerja_id' => $shift->id,
            'status_pegawai_id' => $status->id,
            'sisa_cuti' => 15,
            'alamat_domisili' => 'Jl. Asia Afrika No. 12, Bandung'
        ]);
    }
}
