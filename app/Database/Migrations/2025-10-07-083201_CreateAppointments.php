<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAppointments extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'             => ['type'=>'INT','unsigned'=>true,'auto_increment'=>true],
            'doctor_id'      => ['type'=>'INT','unsigned'=>true],
            'patient_id'     => ['type'=>'INT','unsigned'=>true],
            'start_datetime' => ['type'=>'DATETIME'],
            'end_datetime'   => ['type'=>'DATETIME'],
            'status'         => ['type'=>'ENUM','constraint'=>['pending','completed','cancelled'],'default'=>'pending'],
            'created_by'     => ['type'=>'INT','unsigned'=>true,'null'=>true],
            'updated_by'     => ['type'=>'INT','unsigned'=>true,'null'=>true],
            'deleted_by'     => ['type'=>'INT','unsigned'=>true,'null'=>true],
            'created_at'     => ['type'=>'TIMESTAMP','null'=>true],
            'updated_at'     => ['type'=>'TIMESTAMP','null'=>true],
            'deleted_at'     => ['type'=>'TIMESTAMP','null'=>true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('doctor_id','doctors','id','CASCADE','CASCADE');
        $this->forge->addForeignKey('patient_id','patients','id','CASCADE','CASCADE');
        $this->forge->addForeignKey('created_by','users','id','SET NULL','CASCADE');
        $this->forge->addForeignKey('updated_by','users','id','SET NULL','CASCADE');
        $this->forge->addForeignKey('deleted_by','users','id','SET NULL','CASCADE');

        $this->forge->createTable('appointments');
    }

    public function down()
    {
        $this->forge->dropTable('appointments');
    }
}
