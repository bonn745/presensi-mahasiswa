<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UserFaceEncoding extends Migration
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
                'constraint' => '11',
                'null' => false,
            ],
            'face_encoding' => [
                'type' => 'LONGTEXT',
                'null' => false,
            ],
            'foto_path' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP')
            ],
            'is_active' => [
                'type' => 'tinyint',
                'constraint' => '1',
                'default' => '1',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('mahasiswa_face_encodings');
    }

    public function down()
    {
        $this->forge->dropTable('mahasiswa_face_encodings');
    }
}
