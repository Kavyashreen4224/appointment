<?php

namespace App\Models;

use CodeIgniter\Model;

class AppointmentModel extends Model
{
    protected $table            = 'appointments';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [ 'hospital_id' ,'doctor_id', 'patient_id', 'start_datetime', 'end_datetime',
        'status', 'created_by', 'updated_by',
        'created_at', 'updated_at'];

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

    public function getAppointmentsByHospital($hospital_id)
{
    return $this->select('appointments.*, patients.id as patient_id, patient_users.name as patient_name, patient_users.email as patient_email, doctors.id as doctor_id, doctor_users.name as doctor_name')
                ->join('patients', 'patients.id = appointments.patient_id')
                ->join('users as patient_users', 'patient_users.id = patients.user_id')
                ->join('doctors', 'doctors.id = appointments.doctor_id')
                ->join('users as doctor_users', 'doctor_users.id = doctors.user_id')
                ->where('doctors.hospital_id', $hospital_id)
                ->findAll();
}

}
