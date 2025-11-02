<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Prescriptions extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'visit_id'      => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'doctor_id'     => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'patient_id'    => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'notes'         => ['type' => 'TEXT', 'null' => true, 'comment' => 'General prescription notes'],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'updated_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('visit_id', 'visit_history', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('doctor_id', 'doctors', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('patient_id', 'patients', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('prescriptions');
    }

    public function down()
    {
        $this->forge->dropTable('prescriptions');
    }
}
