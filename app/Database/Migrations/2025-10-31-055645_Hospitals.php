<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Hospitals extends Migration
{
 public function up()
    {
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'name'       => ['type' => 'VARCHAR', 'constraint' => 100],
            'address'    => ['type' => 'TEXT'],
            'contact'    => ['type' => 'VARCHAR', 'constraint' => 20],
            'email'      => ['type' => 'VARCHAR', 'constraint' => 100],
            'status'     => ['type' => "ENUM('active','inactive')", 'default' => 'active'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('hospitals');
    }

    public function down()
    {
        $this->forge->dropTable('hospitals');
    }
}
