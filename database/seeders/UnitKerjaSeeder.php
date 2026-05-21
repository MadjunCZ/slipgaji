<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitKerjaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kosongkan tabel terlebih dahulu
        DB::table('unit_kerja')->truncate();
        
        $unitKerja = [
            // Sub Bagian / Seksi Kantor Kemenag Nganjuk
            ['kode' => 'Sub Bagian Tata Usaha', 'nama' => 'Sub Bagian Tata Usaha'],
            ['kode' => 'Seksi Pendidikan Agama Islam', 'nama' => 'Seksi Pendidikan Agama Islam'],
            ['kode' => 'Seksi Bimbingan Masyarakat Islam', 'nama' => 'Seksi Bimbingan Masyarakat Islam'],
            ['kode' => 'Seksi Pendidikan Diniyah dan Pondok Pesantren', 'nama' => 'Seksi Pendidikan Diniyah dan Pondok Pesantren'],
            ['kode' => 'Seksi Pendidikan Madrasah', 'nama' => 'Seksi Pendidikan Madrasah'],
            ['kode' => 'Penyelenggara Zakat dan Wakaf', 'nama' => 'Penyelenggara Zakat dan Wakaf'],
            
            // KUA Kecamatan
            ['kode' => 'Kantor Urusan Agama Bagor', 'nama' => 'Kantor Urusan Agama Bagor'],
            ['kode' => 'Kantor Urusan Agama Baron', 'nama' => 'Kantor Urusan Agama Baron'],
            ['kode' => 'Kantor Urusan Agama Berbek', 'nama' => 'Kantor Urusan Agama Berbek'],
            ['kode' => 'Kantor Urusan Agama Gondang', 'nama' => 'Kantor Urusan Agama Gondang'],
            ['kode' => 'Kantor Urusan Agama Jatikalen', 'nama' => 'Kantor Urusan Agama Jatikalen'],
            ['kode' => 'Kantor Urusan Agama Kertosono', 'nama' => 'Kantor Urusan Agama Kertosono'],
            ['kode' => 'Kantor Urusan Agama Lengkong', 'nama' => 'Kantor Urusan Agama Lengkong'],
            ['kode' => 'Kantor Urusan Agama Loceret', 'nama' => 'Kantor Urusan Agama Loceret'],
            ['kode' => 'Kantor Urusan Agama Nganjuk', 'nama' => 'Kantor Urusan Agama Nganjuk'],
            ['kode' => 'Kantor Urusan Agama Ngetos', 'nama' => 'Kantor Urusan Agama Ngetos'],
            ['kode' => 'Kantor Urusan Agama Ngluyu', 'nama' => 'Kantor Urusan Agama Ngluyu'],
            ['kode' => 'Kantor Urusan Agama Ngronggot', 'nama' => 'Kantor Urusan Agama Ngronggot'],
            ['kode' => 'Kantor Urusan Agama Pace', 'nama' => 'Kantor Urusan Agama Pace'],
            ['kode' => 'Kantor Urusan Agama Patianrowo', 'nama' => 'Kantor Urusan Agama Patianrowo'],
            ['kode' => 'Kantor Urusan Agama Prambon', 'nama' => 'Kantor Urusan Agama Prambon'],
            ['kode' => 'Kantor Urusan Agama Rejoso', 'nama' => 'Kantor Urusan Agama Rejoso'],
            ['kode' => 'Kantor Urusan Agama Sawahan', 'nama' => 'Kantor Urusan Agama Sawahan'],
            ['kode' => 'Kantor Urusan Agama Sukomoro', 'nama' => 'Kantor Urusan Agama Sukomoro'],
            ['kode' => 'Kantor Urusan Agama Tanjunganom', 'nama' => 'Kantor Urusan Agama Tanjunganom'],
            ['kode' => 'Kantor Urusan Agama Wilangan', 'nama' => 'Kantor Urusan Agama Wilangan'],
            
            // MAN
            ['kode' => 'MAN 1 Nganjuk', 'nama' => 'MAN 1 Nganjuk'],
            ['kode' => 'MAN 2 Nganjuk', 'nama' => 'MAN 2 Nganjuk'],
            ['kode' => 'MAN 3 Nganjuk', 'nama' => 'MAN 3 Nganjuk'],
            
            // MIN
            ['kode' => 'MIN 1 Nganjuk', 'nama' => 'MIN 1 Nganjuk'],
            ['kode' => 'MIN 2 Nganjuk', 'nama' => 'MIN 2 Nganjuk'],
            ['kode' => 'MIN 3 Nganjuk', 'nama' => 'MIN 3 Nganjuk'],
            ['kode' => 'MIN 4 Nganjuk', 'nama' => 'MIN 4 Nganjuk'],
            ['kode' => 'MIN 5 Nganjuk', 'nama' => 'MIN 5 Nganjuk'],
            ['kode' => 'MIN 6 Nganjuk', 'nama' => 'MIN 6 Nganjuk'],
            ['kode' => 'MIN 7 Nganjuk', 'nama' => 'MIN 7 Nganjuk'],
            ['kode' => 'MIN 8 Nganjuk', 'nama' => 'MIN 8 Nganjuk'],
            ['kode' => 'MIN 9 Nganjuk', 'nama' => 'MIN 9 Nganjuk'],
            ['kode' => 'MIN 10 Nganjuk', 'nama' => 'MIN 10 Nganjuk'],
            ['kode' => 'MIN 11 Nganjuk', 'nama' => 'MIN 11 Nganjuk'],
            
            // MTsN
            ['kode' => 'MTsN 1 Nganjuk', 'nama' => 'MTsN 1 Nganjuk'],
            ['kode' => 'MTsN 2 Nganjuk', 'nama' => 'MTsN 2 Nganjuk'],
            ['kode' => 'MTsN 3 Nganjuk', 'nama' => 'MTsN 3 Nganjuk'],
            ['kode' => 'MTsN 4 Nganjuk', 'nama' => 'MTsN 4 Nganjuk'],
            ['kode' => 'MTsN 5 Nganjuk', 'nama' => 'MTsN 5 Nganjuk'],
            ['kode' => 'MTsN 6 Nganjuk', 'nama' => 'MTsN 6 Nganjuk'],
            ['kode' => 'MTsN 7 Nganjuk', 'nama' => 'MTsN 7 Nganjuk'],
            ['kode' => 'MTsN 8 Nganjuk', 'nama' => 'MTsN 8 Nganjuk'],
            ['kode' => 'MTsN 9 Nganjuk', 'nama' => 'MTsN 9 Nganjuk'],
            ['kode' => 'MTsN 10 Nganjuk', 'nama' => 'MTsN 10 Nganjuk'],
        ];

        // Insert data ke tabel unit_kerja
        $now = now();
        foreach ($unitKerja as $index => $unit) {
            $jenis = $this->getJenis($unit['kode']);
            DB::table('unit_kerja')->insert([
                'kode' => $unit['kode'],
                'nama' => $unit['nama'],
                'jenis' => $jenis,
                'kategori' => $this->getKategori($jenis),
                'aktif' => true,
                'urutan' => $index + 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
        
        $this->command->info('Unit Kerja seeder berhasil! ' . count($unitKerja) . ' data.');
    }
    
    private function getJenis(string $kode): ?string
    {
        if (str_contains($kode, 'Sub Bagian')) return 'sub_bagian';
        if (str_contains($kode, 'Seksi')) return 'seksi';
        if (str_contains($kode, 'Kantor Urusan Agama')) return 'kua';
        if (str_contains($kode, 'MAN')) return 'man';
        if (str_contains($kode, 'MIN')) return 'min';
        if (str_contains($kode, 'MTsN')) return 'mtsn';
        return null;
    }
    
    private function getKategori(?string $jenis): string
    {
        $kategori = [
            'sub_bagian' => 'Kantor Kemenag',
            'seksi' => 'Kantor Kemenag',
            'kua' => 'KUA Kecamatan',
            'man' => 'Madrasah Aliyah Negeri',
            'min' => 'Madrasah Ibtidaiyah Negeri',
            'mtsn' => 'Madrasah Tsanawiyah Negeri',
        ];
        return $kategori[$jenis] ?? 'Lainnya';
    }
}
