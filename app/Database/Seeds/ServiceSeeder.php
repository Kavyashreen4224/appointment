<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ServiceSeeder extends Seeder
{
        public function run()
    {
        // --- SERVICES TABLE ---
        $services = [
            ['name' => 'Consultation', 'description' => 'General doctor consultation', 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'Blood Test', 'description' => 'Basic blood test for diagnostics', 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'X-Ray', 'description' => 'Radiology imaging service', 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'ECG', 'description' => 'Electrocardiogram heart test', 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'MRI Scan', 'description' => 'Magnetic Resonance Imaging', 'created_at' => date('Y-m-d H:i:s')],
        ];

        $this->db->table('services')->insertBatch($services);

        // --- HOSPITAL SERVICES TABLE ---
        // ⚠️ Assume hospital_id = 1 for now
        $hospitalServices = [
            ['hospital_id' => 1, 'service_id' => 1, 'price' => 300.00, 'status' => 'active', 'created_at' => date('Y-m-d H:i:s')],
            ['hospital_id' => 1, 'service_id' => 2, 'price' => 500.00, 'status' => 'active', 'created_at' => date('Y-m-d H:i:s')],
            ['hospital_id' => 1, 'service_id' => 3, 'price' => 800.00, 'status' => 'active', 'created_at' => date('Y-m-d H:i:s')],
            ['hospital_id' => 1, 'service_id' => 4, 'price' => 1000.00, 'status' => 'active', 'created_at' => date('Y-m-d H:i:s')],
            ['hospital_id' => 1, 'service_id' => 5, 'price' => 3000.00, 'status' => 'active', 'created_at' => date('Y-m-d H:i:s')],
        ];

        $this->db->table('hospital_services')->insertBatch($hospitalServices);

        // --- DOCTOR SERVICES TABLE ---
        // ⚠️ Assume doctor_id = 1 (the logged-in doctor) and hospital_id = 1
        $doctorServices = [
            ['doctor_id' => 1, 'hospital_id' => 1, 'service_id' => 1, 'custom_fee' => 400.00, 'created_at' => date('Y-m-d H:i:s')],
            ['doctor_id' => 1, 'hospital_id' => 1, 'service_id' => 2, 'custom_fee' => 550.00, 'created_at' => date('Y-m-d H:i:s')],
            ['doctor_id' => 1, 'hospital_id' => 1, 'service_id' => 3, 'custom_fee' => NULL, 'created_at' => date('Y-m-d H:i:s')],
            ['doctor_id' => 1, 'hospital_id' => 1, 'service_id' => 4, 'custom_fee' => NULL, 'created_at' => date('Y-m-d H:i:s')],
        ];

        $this->db->table('doctor_services')->insertBatch($doctorServices);
    }

}
