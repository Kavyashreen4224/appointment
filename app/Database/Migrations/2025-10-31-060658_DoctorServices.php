<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DoctorServices extends Migration
{
   public function up()
    {
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'doctor_id'     => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'hospital_id'   => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'service_id'    => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'custom_fee'    => ['type' => 'DECIMAL', 'constraint' => '10,2', 'null' => true, 'comment' => 'Doctor-specific fee override'],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'updated_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('doctor_id', 'doctors', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('hospital_id', 'hospitals', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('service_id', 'services', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('doctor_services');
    }

    public function down()
    {
        $this->forge->dropTable('doctor_services');
    }
}
