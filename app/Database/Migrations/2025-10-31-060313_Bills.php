<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Bills extends Migration
{
     public function up()
    {
        $this->forge->addField([
            'id'              => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'auto_increment' => true],
            'appointment_id'  => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true],
            'visit_id'        => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true],
            'patient_id'      => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true],
            'doctor_id'       => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true],
            'consultation_fee'=> ['type' => 'DECIMAL', 'constraint' => '10,2', 'null' => true],
            'total_amount'    => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'payment_status'  => ['type' => 'ENUM("Pending","Paid")', 'default' => 'Pending'],
            'payment_mode'    => ['type' => 'ENUM("Cash","Card","UPI","NetBanking","Insurance","Other")', 'null' => true],
            'payment_date'    => ['type' => 'DATETIME', 'null' => true],
            'created_at'      => ['type' => 'DATETIME', 'null' => true],
            'updated_at'      => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('appointment_id', 'appointments', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('visit_id', 'visit_history', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('patient_id', 'patients', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('doctor_id', 'doctors', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('bills');
    }

    public function down()
    {
        $this->forge->dropTable('bills');
    }
}
