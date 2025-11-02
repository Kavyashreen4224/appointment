<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Admins extends Migration
{
   public function up()
    {
        $this->forge->addField([
            'id'               => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'auto_increment' => true],
            'user_hospital_id' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true],
            'created_by'       => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'null' => true],
            'updated_by'       => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'null' => true],
            'deleted_by'       => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'null' => true],
            'created_at'       => ['type' => 'DATETIME', 'null' => true],
            'updated_at'       => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'       => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_hospital_id', 'hospital_users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('admins');
    }

    public function down()
    {
        $this->forge->dropTable('admins');
    }
}
