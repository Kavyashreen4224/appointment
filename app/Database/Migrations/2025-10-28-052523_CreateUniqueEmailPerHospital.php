<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUniqueEmailPerHospital extends Migration
{
      public function up()
    {
        // First drop existing unique index on `email`
        $this->db->query("ALTER TABLE users DROP INDEX email;");

        // Then add a composite unique key (email + hospital_id)
        $this->db->query("
            ALTER TABLE users 
            ADD UNIQUE KEY unique_email_per_hospital (email, hospital_id);
        ");
    }

    public function down()
    {
        // Reverse changes: drop the new composite key and restore the old one
        $this->db->query("ALTER TABLE users DROP INDEX unique_email_per_hospital;");
        $this->db->query("ALTER TABLE users ADD UNIQUE KEY email (email);");
    }
}
