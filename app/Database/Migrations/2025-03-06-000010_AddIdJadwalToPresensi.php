<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIdJadwalToPresensi extends Migration
{
    public function up()
    {
        // Tambah kolom id_jadwal
        $this->forge->addColumn('presensi', [
            'id_jadwal' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'      => true,
                'after'     => 'id_lokasi_presensi_keluar'
            ]
        ]);

        // Tambah foreign key
        $this->forge->addForeignKey('id_jadwal', 'jadwal_kuliah', 'id', 'CASCADE', 'SET NULL');
    }

    public function down()
    {
        // Hapus foreign key terlebih dahulu
        $this->forge->dropForeignKey('presensi', 'presensi_id_jadwal_foreign');

        // Kemudian hapus kolom
        $this->forge->dropColumn('presensi', 'id_jadwal');
    }
}
