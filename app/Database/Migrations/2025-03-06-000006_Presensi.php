<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Presensi extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_mahasiswa' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'       => true,
            ],
            'tanggal' => [
                'type' => 'DATE',
            ],
            'jam_masuk' => [
                'type' => 'TIME',
            ],
            'foto_masuk' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'id_lokasi_presensi' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            // 'tanggal_keluar' => [
            //     'type' => 'DATE',
            // ],
            'jam_keluar' => [
                'type' => 'TIME',
            ],
            'foto_keluar' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'id_lokasi_presensi_keluar' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'id_matkul' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'pertemuan_ke' => [
                'type'       => 'INT',
                'constraint' => 2,
            ],
        ]);
        $this->forge->addKey('id', true);
        // $this->forge->addForeignKey('id_mahasiswa', 'mahasiswa', 'id');
        // $this->forge->addForeignKey('id_lokasi_presensi', 'lokasi_presensi', 'id');
        // $this->forge->addForeignKey('id_lokasi_presensi_keluar', 'lokasi_presensi', 'id');
        // $this->forge->addForeignKey('id_matkul', 'matkul', 'id');
        $this->forge->createTable('Presensi');
    }

    public function down()
    {
        $this->forge->dropTable('Presensi');
    }
}
