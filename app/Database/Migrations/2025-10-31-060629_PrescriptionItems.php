<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class PrescriptionItems extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                 => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'prescription_id'    => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'medicine_name'      => ['type' => 'VARCHAR', 'constraint' => 255],
            'dosage'             => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true, 'comment' => 'e.g. 500mg'],
            'frequency'          => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true, 'comment' => 'e.g. Morning, Night'],
            'duration'           => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true, 'comment' => 'e.g. 5 days'],
            'usage_instruction'  => ['type' => 'TEXT', 'null' => true, 'comment' => 'e.g. After food'],
            'related_diagnosis'  => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true, 'comment' => 'e.g. Fever, Cough'],
            'created_at'         => ['type' => 'DATETIME', 'null' => true],
            'updated_at'         => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('prescription_id', 'prescriptions', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('prescription_items');
    }

    public function down()
    {
        $this->forge->dropTable('prescription_items');
    }
}
