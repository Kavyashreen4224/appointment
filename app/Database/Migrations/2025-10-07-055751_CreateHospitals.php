<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class CreateHospitals extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false
            ],
            'address' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'contact' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true
            ],
            'status' => [
                'type' => 'ENUM("active","inactive")',
                'default' => 'active',
                'null' => false
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ]
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('hospitals', true);
    }

    public function down()
    {
        $this->forge->dropTable('hospitals', true);
    }
}
