<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DoctorHospitalAvailability extends Migration
{
   public function up()
    {
        $this->forge->addField([
            'id'                => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'hospital_user_id'  => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true], // link to hospital_users
            'day_of_week'       => ['type' => "ENUM('Mon','Tue','Wed','Thu','Fri','Sat','Sun')"],
            'start_time'        => ['type' => 'TIME', 'null' => true],
            'end_time'          => ['type' => 'TIME', 'null' => true],
            'is_available'      => ['type' => 'BOOLEAN', 'default' => true],
            'created_at'        => ['type' => 'DATETIME', 'null' => true],
            'updated_at'        => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('hospital_user_id', 'hospital_users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('doctor_hospital_availability');
    }

    public function down()
    {
        $this->forge->dropTable('doctor_hospital_availability');
    }
}
