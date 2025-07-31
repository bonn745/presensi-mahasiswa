<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class NotifikasiRekapPresensi extends Migration
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
            'tanggal' => [
                'type' => 'TIMESTAMP',
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['Gagal', 'Dikirim'],
                'default' => 'Dikirim',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('log_notifikasi_rekap_presensi');
    }

    public function down()
    {
        $this->forge->dropTable('log_notifikasi_rekap_presensi');
    }
}
