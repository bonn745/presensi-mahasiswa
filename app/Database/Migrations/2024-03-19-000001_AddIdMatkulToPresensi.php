<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIdMatkulToPresensi extends Migration
{
    public function up()
    {
        // Tambah kolom id_matkul
        // $this->forge->addColumn('presensi', [
        //     'id_matkul' => [
        //         'type'       => 'INT',
        //         'constraint' => 11,
        //         'unsigned'   => true,
        //         'null'      => true,
        //         'after'     => 'id_lokasi_presensi_keluar'
        //     ]
        // ]);

        // Tambah foreign key
        // $this->forge->addForeignKey('id_matkul', 'matkul', 'id', 'CASCADE', 'SET NULL');
    }

    public function down()
    {
        // Hapus foreign key terlebih dahulu
        // $this->forge->dropForeignKey('presensi', 'presensi_id_matkul_foreign');

        // Kemudian hapus kolom
        // $this->forge->dropColumn('presensi', 'id_matkul');
    }
}
