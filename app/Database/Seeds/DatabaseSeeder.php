<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Jalankan seeder untuk tabel users
        $this->call(UsersSeeder::class);
    }
}
