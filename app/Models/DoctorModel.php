<?php

namespace App\Models;

use CodeIgniter\Model;

class DoctorModel extends Model
{
    protected $table            = 'doctors';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = ['user_id', 'hospital_id', 'age', 'gender', 'expertise', 'availability',
    'created_by','updated_by','deleted_by','created_at','updated_at','deleted_at'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];




public function getDoctorsByHospital($hospital_id)
{
    return $this->select('doctors.*, users.name, users.email')
                ->join('users', 'users.id = doctors.user_id')
                ->where('doctors.hospital_id', $hospital_id)
                ->findAll();
}


public function getPatientsCount($doctorId)
    {
        $db = \Config\Database::connect();

        $count = $db->table('appointments')
            ->where('doctor_id', $doctorId)
            ->where('deleted_at', null)
            ->countAllResults();

        return $count;
    }
}
