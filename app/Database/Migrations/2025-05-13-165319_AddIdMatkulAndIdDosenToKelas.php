<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIdMatkulAndIdDosenToKelas extends Migration
{
    public function up()
    {
        $this->forge->addColumn('kelas', [
            'id_matkul' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null' => false,
            ],
        ]);

        // Menambahkan foreign key
        $this->forge->addForeignKey('id_matkul', 'matkul', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        // $this->forge->dropForeignKey('kelas', 'kelas_id_matkul_foreign');
        // $this->forge->dropForeignKey('kelas', 'kelas_id_dosen_foreign');
        $this->forge->dropColumn('kelas', 'id_matkul');
    }
}
