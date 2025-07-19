<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class JadwalPembayaran extends Migration
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
            'nama_jadwal' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'tanggal_pembayaran' => [
                'type' => 'DATE',
            ],
            'tanggal_notifikasi' => [
                'type' => 'DATE',
            ],
            'jam_notifikasi' => [
                'type' => 'TIME',
            ],
            'status_notifikasi' => [
                'type' => 'ENUM',
                'constraint' => ['Pending', 'Dikirim'],
                'default' => 'Pending',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('jadwal_pembayaran');
    }

    public function down()
    {
        $this->forge->dropTable('jadwal_pembayaran');
    }
}
