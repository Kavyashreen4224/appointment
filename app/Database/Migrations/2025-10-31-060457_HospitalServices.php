<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class HospitalServices extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'hospital_id'   => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'service_id'    => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'price'         => ['type' => 'DECIMAL', 'constraint' => '10,2', 'null' => true],
            'status'        => ['type' => 'ENUM("active","inactive")', 'default' => 'active'],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'updated_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('hospital_id', 'hospitals', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('service_id', 'services', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('hospital_services');
    }

    public function down()
    {
        $this->forge->dropTable('hospital_services');
    }
}
