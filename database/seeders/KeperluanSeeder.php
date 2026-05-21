<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KeperluanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('keperluan')->truncate();
        
        $keperluan = [
            'Pengajuan Pinjaman Bank',
            'Kredit / KPR',
            'Arsip Pribadi',
            'Aktivasi BPJS',
            'Lainnya',
        ];

        $now = now();
        foreach ($keperluan as $index => $nama) {
            DB::table('keperluan')->insert([
                'nama' => $nama,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
        
        $this->command->info('Keperluan seeder berhasil! ' . count($keperluan) . ' data.');
    }
}
