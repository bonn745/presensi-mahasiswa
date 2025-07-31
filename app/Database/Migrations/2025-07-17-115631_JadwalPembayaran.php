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
            'tanggal_pembayaran_tahap_1' => [
                'type' => 'DATE',
            ],
            'tanggal_pembayaran_tahap_2' => [
                'type' => 'DATE',
            ],
            'tanggal_pembayaran_tahap_3' => [
                'type' => 'DATE',
            ],
            'tanggal_notifikasi_tahap_1' => [
                'type' => 'DATE',
            ],
            'tanggal_notifikasi_tahap_2' => [
                'type' => 'DATE',
            ],
            'tanggal_notifikasi_tahap_3' => [
                'type' => 'DATE',
            ],
            'jam_notifikasi_tahap_1' => [
                'type' => 'TIME',
            ],
            'jam_notifikasi_tahap_2' => [
                'type' => 'TIME',
            ],
            'jam_notifikasi_tahap_3' => [
                'type' => 'TIME',
            ],
            'status_notifikasi_tahap_1' => [
                'type' => 'ENUM',
                'constraint' => ['Pending', 'Dikirim'],
                'default' => 'Pending',
            ],
            'status_notifikasi_tahap_2' => [
                'type' => 'ENUM',
                'constraint' => ['Pending', 'Dikirim'],
                'default' => 'Pending',
            ],
            'status_notifikasi_tahap_3' => [
                'type' => 'ENUM',
                'constraint' => ['Pending', 'Dikirim'],
                'default' => 'Pending',
            ],
            'status_jadwal' => [
                'type' => 'ENUM',
                'constraint' => ['Aktif', 'Nonaktif'],
                'default' => 'Aktif',
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
