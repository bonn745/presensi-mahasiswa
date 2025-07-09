<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Kelas extends Migration
{
    public function up()
    {
        // Membuat tabel kelas
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'ruangan' => [
                'type'           => 'VARCHAR',
                'constraint'     => '100',
            ],
            'hari' => [
                'type'           => 'ENUM',
                'constraint'     => ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
            ],
            'jam_masuk' => [
                'type'           => 'TIME',
            ],
            'jam_pulang' => [
                'type'           => 'TIME',
            ],
            'zona_waktu' => [
                'type' => 'VARCHAR',
                'constraint' => '4',
            ],
            'jenis_kelas' => [
                'type'           => 'ENUM',
                'constraint'     => ['Daring', 'Luring'],
            ],
        ]);

        // Menambahkan primary key
        $this->forge->addKey('id', true);

        // Membuat tabel kelas
        $this->forge->createTable('kelas');
    }

    public function down()
    {
        // Menghapus tabel kelas jika rollback migrasi
        $this->forge->dropTable('kelas');
    }
}
