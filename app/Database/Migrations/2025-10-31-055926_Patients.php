<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Patients extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'               => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'auto_increment' => true],
            'user_hospital_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'age'              => ['type' => 'INT', 'null' => true],
            'gender'           => ['type' => 'ENUM("male","female","other")', 'null' => true],
            'created_at'       => ['type' => 'TIMESTAMP', 'null' => true],
            'updated_at'       => ['type' => 'TIMESTAMP', 'null' => true],
            'deleted_at'       => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_hospital_id', 'hospital_users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('patients');
    }

    public function down()
    {
        $this->forge->dropTable('patients');
    }
}
