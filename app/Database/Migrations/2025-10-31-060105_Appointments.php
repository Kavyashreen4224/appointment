<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Appointments extends Migration
{
   public function up()
    {
        $this->forge->addField([
            'id'             => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'auto_increment' => true],
            'doctor_id'      => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true],
            'patient_id'     => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true],
            'start_datetime' => ['type' => 'DATETIME'],
            'end_datetime'   => ['type' => 'DATETIME'],
            'status'         => ['type' => 'ENUM("pending","completed","cancelled")', 'default' => 'pending'],
            'created_by'     => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'null' => true],
            'updated_by'     => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'null' => true],
            'created_at'     => ['type' => 'TIMESTAMP', 'null' => true],
            'updated_at'     => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('doctor_id', 'doctors', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('patient_id', 'patients', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('appointments');
    }

    public function down()
    {
        $this->forge->dropTable('appointments');
    }
}
