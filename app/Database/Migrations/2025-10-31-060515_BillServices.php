<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class BillServices extends Migration
{
     public function up()
    {
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'bill_id'       => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'hospital_service_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'service_name'  => ['type' => 'VARCHAR', 'constraint' => 255],
            'price'         => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'quantity'      => ['type' => 'INT', 'default' => 1],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'updated_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('bill_id', 'bills', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('hospital_service_id', 'hospital_services', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('bill_services');
    }

    public function down()
    {
        $this->forge->dropTable('bill_services');
    }
}
