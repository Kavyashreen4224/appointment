<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddHospitalIdToAppoitments extends Migration
{
     public function up()
    {
        // Add hospital_id column
        $fields = [
            'hospital_id' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'after'      => 'id', // optional: position in table
                'null'       => false,
            ],
        ];

        // Add column to appointments table
        $this->forge->addColumn('appointments', $fields);

        // Add foreign key constraint
        $this->db->query('
            ALTER TABLE `appointments`
            ADD CONSTRAINT `fk_appointments_hospital`
            FOREIGN KEY (`hospital_id`)
            REFERENCES `hospitals`(`id`)
            ON DELETE CASCADE
            ON UPDATE CASCADE
        ');
    }

    public function down()
    {
        // Drop foreign key and column
        $this->db->query('ALTER TABLE `appointments` DROP FOREIGN KEY `fk_appointments_hospital`');
        $this->forge->dropColumn('appointments', 'hospital_id');
    }
}
