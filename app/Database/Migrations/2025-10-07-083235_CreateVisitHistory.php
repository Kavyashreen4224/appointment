<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateVisitHistory extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'             => ['type'=>'INT','unsigned'=>true,'auto_increment'=>true],
            'appointment_id' => ['type'=>'INT','unsigned'=>true],
            'patient_id'     => ['type'=>'INT','unsigned'=>true],
            'doctor_id'      => ['type'=>'INT','unsigned'=>true],
            'reason'         => ['type'=>'TEXT','null'=>true],
            'weight'         => ['type'=>'DECIMAL','constraint'=>'5,2','null'=>true],
            'blood_pressure' => ['type'=>'VARCHAR','constraint'=>20,'null'=>true],
            'doctor_comments'=> ['type'=>'TEXT','null'=>true],
            'created_at'     => ['type'=>'TIMESTAMP','null'=>true],
            'updated_at'     => ['type'=>'TIMESTAMP','null'=>true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('appointment_id','appointments','id','CASCADE','CASCADE');
        $this->forge->addForeignKey('patient_id','patients','id','CASCADE','CASCADE');
        $this->forge->addForeignKey('doctor_id','doctors','id','CASCADE','CASCADE');

        $this->forge->createTable('visit_history');
    }

    public function down()
    {
        $this->forge->dropTable('visit_history');
    }
}
