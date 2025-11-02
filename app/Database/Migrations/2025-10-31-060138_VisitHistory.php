<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class VisitHistory extends Migration
{
        public function up()
    {
        $this->forge->addField([
            'id'               => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'auto_increment' => true],
            'appointment_id'   => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true],
            'patient_id'       => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true],
            'doctor_id'        => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true],
            'complaints'       => ['type' => 'TEXT', 'null' => true, 'comment' => 'List of patient complaints (e.g. fever, cough)'],
            'diagnosis'        => ['type' => 'TEXT', 'null' => true, 'comment' => 'Doctor diagnosis notes'],
            'weight'           => ['type' => 'DECIMAL', 'constraint' => '5,2', 'null' => true],
            'blood_pressure'   => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'doctor_comments'  => ['type' => 'TEXT', 'null' => true],
            'created_at'       => ['type' => 'TIMESTAMP', 'null' => true, 'default' => null],
            'updated_at'       => ['type' => 'TIMESTAMP', 'null' => true, 'default' => null],
            'deleted_at'       => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('appointment_id', 'appointments', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('patient_id', 'patients', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('doctor_id', 'doctors', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('visit_history');
    }

    public function down()
    {
        $this->forge->dropTable('visit_history');
    }
}
