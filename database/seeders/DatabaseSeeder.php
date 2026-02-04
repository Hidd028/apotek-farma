<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'nama' => 'Nurwahid',
            'email' => 'wahid@gmail.com',
            'nomor_hp' => '082115700260',
            'jabatan' => 'Admin',
            'password' => Hash::make('123123123'),
        ]);

        User::create([
            'nama' => 'Ayu',
            'email' => 'ayu@gmail.com',
            'nomor_hp' => '082115700260',
            'jabatan' => 'Karyawan',
            'password' => Hash::make('123123123'),
        ]);
    }
}
