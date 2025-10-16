<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePatients extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'         => ['type'=>'INT','unsigned'=>true,'auto_increment'=>true],
            'user_id'    => ['type'=>'INT','unsigned'=>true],
            'hospital_id'=> ['type'=>'INT','unsigned'=>true],
            'age'        => ['type'=>'INT','null'=>true],
            'gender'     => ['type'=>'ENUM','constraint'=>['male','female','other']],
            'created_by' => ['type'=>'INT','unsigned'=>true,'null'=>true],
            'updated_by' => ['type'=>'INT','unsigned'=>true,'null'=>true],
            'deleted_by' => ['type'=>'INT','unsigned'=>true,'null'=>true],
            'created_at' => ['type'=>'TIMESTAMP','null'=>true],
            'updated_at' => ['type'=>'TIMESTAMP','null'=>true],
            'deleted_at' => ['type'=>'TIMESTAMP','null'=>true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id','users','id','CASCADE','CASCADE');
        $this->forge->addForeignKey('hospital_id','hospitals','id','CASCADE','CASCADE');
        $this->forge->addForeignKey('created_by','users','id','SET NULL','CASCADE');
        $this->forge->addForeignKey('updated_by','users','id','SET NULL','CASCADE');
        $this->forge->addForeignKey('deleted_by','users','id','SET NULL','CASCADE');
        $this->forge->createTable('patients');
    }

    public function down()
    {
        $this->forge->dropTable('patients');
    }
}
