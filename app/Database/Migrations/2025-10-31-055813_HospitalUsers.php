<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class HospitalUsers extends Migration
{
   public function up()
    {
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'user_id'     => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'hospital_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'role'        => ['type' => "ENUM('admin','doctor','patient')", 'null' => false],
            'status'      => ['type' => "ENUM('active','inactive')", 'default' => 'active'],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('hospital_id', 'hospitals', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('hospital_users');
    }

    public function down()
    {
        $this->forge->dropTable('hospital_users');
    }
}
