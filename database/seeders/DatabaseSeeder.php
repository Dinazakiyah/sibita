<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\StatusMahasiswa;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Buat Admin
        $admin = User::create([
            'name' => 'Admin Prodi Informatika',
            'email' => 'admin@unej.ac.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'nim_nip' => 'ADM001',
            'phone' => '081234567890',
        ]);

        echo "✓ Admin created\n";

        // 2. Buat Dosen
        $dosen1 = User::create([
            'name' => 'Dr. Budi Santoso, M.T.',
            'email' => 'budi@unej.ac.id',
            'password' => Hash::make('password'),
            'role' => 'dosen',
            'nim_nip' => '197001011998031001',
            'phone' => '081234567891',
        ]);

        $dosen2 = User::create([
            'name' => 'Dr. Siti Nurhaliza, M.Kom.',
            'email' => 'siti@unej.ac.id',
            'password' => Hash::make('password'),
            'role' => 'dosen',
            'nim_nip' => '197505102000032001',
            'phone' => '081234567892',
        ]);

        $dosen3 = User::create([
            'name' => 'Ir. Ahmad Yani, M.T.',
            'email' => 'ahmad.yani@unej.ac.id',
            'password' => Hash::make('password'),
            'role' => 'dosen',
            'nim_nip' => '198003152005011001',
            'phone' => '081234567893',
        ]);

        echo "✓ Dosen created (3)\n";

        // 3. Buat Mahasiswa
        $mahasiswa1 = User::create([
            'name' => 'Ahmad Fauzi',
            'email' => 'ahmad@students.unej.ac.id',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
            'nim_nip' => '202110001',
            'phone' => '081234567894',
        ]);

        $mahasiswa2 = User::create([
            'name' => 'Dewi Kusuma Wardani',
            'email' => 'dewi@students.unej.ac.id',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
            'nim_nip' => '202110002',
            'phone' => '081234567895',
        ]);

        $mahasiswa3 = User::create([
            'name' => 'Rizky Pratama',
            'email' => 'rizky@students.unej.ac.id',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
            'nim_nip' => '202110003',
            'phone' => '081234567896',
        ]);

        echo "✓ Mahasiswa created (3)\n";

        // 4. Assign Dosen Pembimbing ke Mahasiswa 1
        $mahasiswa1->dosenPembimbing()->attach([
            $dosen1->id => ['jenis_pembimbing' => 'pembimbing_1'],
            $dosen2->id => ['jenis_pembimbing' => 'pembimbing_2'],
        ]);

        // 5. Assign Dosen Pembimbing ke Mahasiswa 2
        $mahasiswa2->dosenPembimbing()->attach([
            $dosen1->id => ['jenis_pembimbing' => 'pembimbing_1'],
            $dosen3->id => ['jenis_pembimbing' => 'pembimbing_2'],
        ]);

        // 6. Assign Dosen Pembimbing ke Mahasiswa 3
        $mahasiswa3->dosenPembimbing()->attach([
            $dosen2->id => ['jenis_pembimbing' => 'pembimbing_1'],
            $dosen3->id => ['jenis_pembimbing' => 'pembimbing_2'],
        ]);

        echo "✓ Dosen pembimbing assigned\n";

        // 7. Buat Status Mahasiswa
        StatusMahasiswa::create([
            'mahasiswa_id' => $mahasiswa1->id,
            'layak_sempro' => false,
            'layak_sidang' => false,
        ]);

        StatusMahasiswa::create([
            'mahasiswa_id' => $mahasiswa2->id,
            'layak_sempro' => false,
            'layak_sidang' => false,
        ]);

        StatusMahasiswa::create([
            'mahasiswa_id' => $mahasiswa3->id,
            'layak_sempro' => false,
            'layak_sidang' => false,
        ]);

        echo "✓ Status mahasiswa created\n";

        // Tampilkan info login
        echo "\n";
        echo "========================================\n";
        echo "DATABASE SEEDING COMPLETED!\n";
        echo "========================================\n";
        echo "Login Credentials:\n";
        echo "----------------------------------------\n";
        echo "ADMIN:\n";
        echo "Email: admin@unej.ac.id\n";
        echo "Password: password\n";
        echo "----------------------------------------\n";
        echo "DOSEN:\n";
        echo "Email: budi@unej.ac.id\n";
        echo "Password: password\n";
        echo "----------------------------------------\n";
        echo "MAHASISWA:\n";
        echo "Email: ahmad@students.unej.ac.id\n";
        echo "Password: password\n";
        echo "========================================\n";
    }
}
