<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Bimbingan;
use App\Models\StatusMahasiswa;

class BimbinganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get users
        $mahasiswa = User::where('role', 'mahasiswa')->first();
        $dosen = User::where('role', 'dosen')->first();

        if (!$mahasiswa || !$dosen) {
            return;
        }

        // Create relationship between mahasiswa and dosen
        $mahasiswa->dosenPembimbing()->attach($dosen->id);

        // Set mahasiswa status to layak sempro
        StatusMahasiswa::updateOrCreate(
            ['mahasiswa_id' => $mahasiswa->id],
            ['layak_sempro' => true]
        );

        // Create sample bimbingan
        Bimbingan::create([
            'mahasiswa_id' => $mahasiswa->id,
            'dosen_id' => $dosen->id,
            'fase' => 'sempro',
            'judul' => 'Sistem Informasi Akademik Berbasis Web',
            'deskripsi' => 'Pengembangan sistem informasi untuk manajemen akademik mahasiswa',
            'file_path' => 'bimbingan/sample_proposal.pdf',
            'status' => 'pending',
            'tanggal_upload' => now(),
        ]);

        Bimbingan::create([
            'mahasiswa_id' => $mahasiswa->id,
            'dosen_id' => $dosen->id,
            'fase' => 'sempro',
            'judul' => 'Aplikasi Mobile untuk Monitoring Kesehatan',
            'deskripsi' => 'Aplikasi mobile untuk tracking aktivitas kesehatan pengguna',
            'file_path' => 'bimbingan/sample_proposal2.pdf',
            'status' => 'approved',
            'komentar_dosen' => 'Proposal sudah baik, silakan lanjut ke tahap implementasi',
            'percentage' => 85.5,
            'tanggal_upload' => now()->subDays(7),
            'tanggal_revisi' => now()->subDays(3),
        ]);

        Bimbingan::create([
            'mahasiswa_id' => $mahasiswa->id,
            'dosen_id' => $dosen->id,
            'fase' => 'sempro',
            'judul' => 'Analisis Big Data untuk Prediksi Penjualan',
            'deskripsi' => 'Penggunaan machine learning untuk analisis data penjualan',
            'file_path' => 'bimbingan/sample_proposal3.pdf',
            'status' => 'revisi',
            'komentar_dosen' => 'Perlu diperbaiki bagian metodologi dan literatur review',
            'percentage' => 70.0,
            'tanggal_upload' => now()->subDays(5),
            'tanggal_revisi' => now()->subDays(1),
        ]);
    }
}
