<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBills extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true
            ],
            'appointment_id' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'visit_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true
            ],
            'patient_id' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'doctor_id' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'hospital_id' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'consultation_fee' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0
            ],
            'services' => [
                'type' => 'TEXT', // store JSON array of services {name, price}
                'null' => true,
            ],
            'total_amount' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0
            ],
            'payment_status' => [
                'type' => 'ENUM',
                'constraint' => ['Pending','Paid'],
                'default' => 'Pending'
            ],
            'payment_mode' => [
                'type' => 'ENUM',
                'constraint' => ['Cash','Card','UPI','NetBanking','Insurance','Other'],
                'default' => 'Cash'
            ],
            'payment_date' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true
            ]
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('appointment_id', 'appointments', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('visit_id', 'visit_history', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('patient_id', 'patients', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('doctor_id', 'doctors', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('hospital_id', 'hospitals', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('bills');
    }

    public function down()
    {
        $this->forge->dropTable('bills');
    }
}
