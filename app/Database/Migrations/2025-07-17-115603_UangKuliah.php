<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UangKuliah extends Migration
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
            'npm' => [
                'type'       => 'VARCHAR',
                'constraint' => '25',
            ],
            'jenis_pembayaran' => [
                'type'           => 'ENUM',
                'constraint'     => ['bertahap', 'lunas'],
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('uang_kuliah');
    }

    public function down()
    {
        $this->forge->dropTable('uang_kuliah');
    }
}
