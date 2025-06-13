<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLokasiPresensiColumns extends Migration
{
    public function up()
    {
        // Tambah kolom id_lokasi_presensi dan id_lokasi_presensi_keluar
        $fields = [
            'id_lokasi_presensi' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'foto_masuk'
            ],
            'id_lokasi_presensi_keluar' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'foto_keluar'
            ]
        ];

        $this->forge->addColumn('presensi', $fields);

        // Tambah foreign key
        $this->forge->addForeignKey('id_lokasi_presensi', 'lokasi_presensi', 'id', '', 'CASCADE');
        $this->forge->addForeignKey('id_lokasi_presensi_keluar', 'lokasi_presensi', 'id', '', 'CASCADE');
    }

    public function down()
    {
        // Hapus foreign key terlebih dahulu
        $this->forge->dropForeignKey('presensi', 'presensi_id_lokasi_presensi_foreign');
        $this->forge->dropForeignKey('presensi', 'presensi_id_lokasi_presensi_keluar_foreign');

        // Hapus kolom
        $this->forge->dropColumn('presensi', ['id_lokasi_presensi', 'id_lokasi_presensi_keluar']);
    }
}
