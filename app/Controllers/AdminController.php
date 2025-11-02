<?php

namespace App\Controllers;

use App\Models\HospitalModel;
use App\Models\HospitalUserModel;
use App\Models\DoctorModel;
use App\Models\PatientModel;
use App\Models\AppointmentModel;
use App\Models\UserModel;
use CodeIgniter\Controller;

class AdminController extends Controller
{
    public function dashboard()
    {
        $session = session();

        // ✅ Ensure user is logged in and role is admin
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Access denied.');
        }

        $db = \Config\Database::connect();
        $userId = $session->get('id');

        // ✅ Step 1: Get admin's hospital_id
        $hospitalUser = $db->table('hospital_users')
            ->where(['user_id' => $userId, 'role' => 'admin'])
            ->get()
            ->getRowArray();

        if (!$hospitalUser) {
            return redirect()->to('/login')->with('error', 'Hospital association not found.');
        }

        $hospitalId = $hospitalUser['hospital_id'];

        // ✅ Step 2: Count doctors under this hospital
        $doctorCount = $db->query("
            SELECT COUNT(*) AS total
            FROM doctors d
            JOIN hospital_users hu ON d.user_hospital_id = hu.id
            WHERE hu.hospital_id = ?
        ", [$hospitalId])->getRow()->total;

        // ✅ Step 3: Count patients under this hospital
        $patientCount = $db->query("
            SELECT COUNT(*) AS total
            FROM patients p
            JOIN hospital_users hu ON p.user_hospital_id = hu.id
            WHERE hu.hospital_id = ?
        ", [$hospitalId])->getRow()->total;

        // ✅ Step 4: Count appointments for this hospital
        $appointmentCount = $db->query("
            SELECT COUNT(*) AS total
            FROM appointments a
            WHERE a.hospital_id = ?
        ", [$hospitalId])->getRow()->total ?? 0;

        // ✅ Step 5: Fetch hospital info
        $hospital = $db->table('hospitals')->where('id', $hospitalId)->get()->getRowArray();

        // ✅ Step 6: Pass all data to view
        return view('admin/dashboard', [
            'hospital' => $hospital,
            'doctorCount' => $doctorCount,
            'patientCount' => $patientCount,
            'appointmentCount' => $appointmentCount,
        ]);
    }


   // ---------------------
// DOCTOR MANAGEMENT
// ---------------------
public function listDoctors()
{
    $session = session();
    $userId = $session->get('id');

    $db = \Config\Database::connect();

    // Get admin's hospital_id
    $hospitalUser = $db->table('hospital_users')
        ->where(['user_id' => $userId, 'role' => 'admin'])
        ->get()
        ->getRowArray();

    if (!$hospitalUser) {
        return redirect()->to('/login')->with('error', 'Hospital association not found.');
    }

    $hospitalId = $hospitalUser['hospital_id'];

    // Fetch all doctors in that hospital
    $query = $db->query("
        SELECT d.id AS doctor_id, u.name, u.email, d.age, d.gender, d.expertise, d.availability_type, hu.status
        FROM doctors d
        JOIN hospital_users hu ON d.user_hospital_id = hu.id
        JOIN users u ON hu.user_id = u.id
        WHERE hu.hospital_id = ?
    ", [$hospitalId]);

    $doctors = $query->getResultArray();

    return view('admin/listDoctors', ['doctors' => $doctors]);
}

public function addDoctor()
{
    return view('admin/add_doctor');
}

public function saveDoctor()
{
    $session = session();
    $userId = $session->get('id');
    $db = \Config\Database::connect();
    $db->transStart();

    // Get admin hospital
    $hospitalUser = $db->table('hospital_users')->where(['user_id' => $userId, 'role' => 'admin'])->get()->getRowArray();
    $hospitalId = $hospitalUser['hospital_id'];

    // Step 1: Insert user
    $userData = [
        'name' => $this->request->getPost('name'),
        'email' => $this->request->getPost('email'),
        'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
        'role' => 'doctor',
    ];
    $db->table('users')->insert($userData);
    $newUserId = $db->insertID();

    // Step 2: hospital_users
    $hospitalUserData = [
        'user_id' => $newUserId,
        'hospital_id' => $hospitalId,
        'role' => 'doctor',
        'status' => 'active',
        'created_at' => date('Y-m-d H:i:s'),
    ];
    $db->table('hospital_users')->insert($hospitalUserData);
    $userHospitalId = $db->insertID();

    // Step 3: doctors table
    $doctorData = [
        'user_hospital_id' => $userHospitalId,
        'age' => $this->request->getPost('age'),
        'gender' => $this->request->getPost('gender'),
        'expertise' => $this->request->getPost('expertise'),
        'availability_type' => $this->request->getPost('availability_type'),
    ];
    $db->table('doctors')->insert($doctorData);

    $db->transComplete();

    return redirect()->to('admin/listDoctors')->with('success', 'Doctor added successfully!');
}

public function editDoctor($id)
{
    $db = \Config\Database::connect();
    $query = $db->query("
        SELECT d.id AS doctor_id, u.*, d.age, d.gender, d.expertise, d.availability_type
        FROM doctors d
        JOIN hospital_users hu ON d.user_hospital_id = hu.id
        JOIN users u ON hu.user_id = u.id
        WHERE d.id = ?
    ", [$id]);

    $doctor = $query->getRowArray();

    return view('admin/edit_doctor', ['doctor' => $doctor]);
}

public function updateDoctor($id)
{
    $db = \Config\Database::connect();

    // Update user
    $userData = [
        'name' => $this->request->getPost('name'),
        'email' => $this->request->getPost('email'),
        'updated_at' => date('Y-m-d H:i:s'),
    ];
    $db->table('users')->where('id', $this->request->getPost('user_id'))->update($userData);

    // Update doctor details
    $doctorData = [
        'age' => $this->request->getPost('age'),
        'gender' => $this->request->getPost('gender'),
        'expertise' => $this->request->getPost('expertise'),
        'availability_type' => $this->request->getPost('availability_type'),
        'updated_at' => date('Y-m-d H:i:s'),
    ];
    $db->table('doctors')->where('id', $id)->update($doctorData);

    return redirect()->to('admin/listDoctors')->with('success', 'Doctor updated successfully.');
}

public function deleteDoctor($id)
{
    $db = \Config\Database::connect();

    // Find doctor and related user
    $doctor = $db->table('doctors')->where('id', $id)->get()->getRowArray();
    if (!$doctor) {
        return redirect()->back()->with('error', 'Doctor not found.');
    }

    $hospitalUser = $db->table('hospital_users')->where('id', $doctor['user_hospital_id'])->get()->getRowArray();
    $userId = $hospitalUser['user_id'];

    // Delete from all related tables
    $db->table('doctors')->where('id', $id)->delete();
    $db->table('hospital_users')->where('id', $doctor['user_hospital_id'])->delete();
    $db->table('users')->where('id', $userId)->delete();

    return redirect()->to('admin/listDoctors')->with('success', 'Doctor deleted successfully.');
}


public function viewDoctor($doctorId)
{
    $db = \Config\Database::connect();

    // ✅ Fetch doctor & hospital info
    $doctor = $db->query("
        SELECT 
            u.name AS doctor_name,
            u.email AS doctor_email,
            d.id AS doctor_id,
            d.age,
            d.gender,
            d.expertise,
            d.availability_type,
            h.name AS hospital_name,
            h.address AS hospital_address,
            h.contact AS hospital_contact,
            h.email AS hospital_email,
            d.created_at,
            d.updated_at
        FROM doctors d
        JOIN hospital_users hu ON d.user_hospital_id = hu.id
        JOIN users u ON hu.user_id = u.id
        JOIN hospitals h ON hu.hospital_id = h.id
        WHERE d.id = ?
    ", [$doctorId])->getRowArray();

    if (!$doctor) {
        return redirect()->back()->with('error', 'Doctor not found.');
    }

    // ✅ Fetch all appointments related to this doctor
    $appointments = $db->query("
        SELECT 
            a.id,
            a.start_datetime,
            a.end_datetime,
            a.status,
            p.id AS patient_id,
            u.name AS patient_name,
            u.email AS patient_email
        FROM appointments a
        JOIN patients p ON a.patient_id = p.id
        JOIN hospital_users hu_p ON p.user_hospital_id = hu_p.id
        JOIN users u ON hu_p.user_id = u.id
        WHERE a.doctor_id = ?
        ORDER BY a.start_datetime DESC
    ", [$doctorId])->getResultArray();

    return view('admin/viewDoctor', [
        'doctor' => $doctor,
        'appointments' => $appointments
    ]);
}


public function viewPatient($id)
{
    $db = \Config\Database::connect();

    // ✅ Get patient basic info + user + hospital
    $patient = $db->query("
        SELECT 
            u.id AS user_id,
            u.name AS patient_name,
            u.email,
            p.id AS patient_id,
            p.age,
            p.gender,
            h.name AS hospital_name,
            h.address AS hospital_address,
            hu.status AS hospital_status
        FROM patients p
        JOIN hospital_users hu ON p.user_hospital_id = hu.id
        JOIN users u ON hu.user_id = u.id
        JOIN hospitals h ON hu.hospital_id = h.id
        WHERE p.id = ?
    ", [$id])->getRowArray();

    if (!$patient) {
        return redirect()->to('/admin/dashboard')->with('error', 'Patient not found.');
    }

    // ✅ Fetch visit/appointment history
    $appointments = $db->query("
        SELECT 
            a.id,
            a.start_datetime,
            a.end_datetime,
            a.status,
            d.id AS doctor_id,
            ud.name AS doctor_name
        FROM appointments a
        JOIN doctors d ON a.doctor_id = d.id
        JOIN hospital_users hud ON d.user_hospital_id = hud.id
        JOIN users ud ON hud.user_id = ud.id
        WHERE a.patient_id = ?
        ORDER BY a.start_datetime DESC
    ", [$id])->getResultArray();

    return view('admin/viewPatient', [
        'patient' => $patient,
        'appointments' => $appointments
    ]);
}




public function listPatients()
{
    $session = session();
    if (!$session->get('isLoggedIn') || $session->get('role') !== 'admin') {
        return redirect()->to('/login')->with('error', 'Access denied.');
    }

    $db = \Config\Database::connect();
    $userId = $session->get('id');

    // Find admin’s hospital
    $hospitalUser = $db->table('hospital_users')->where(['user_id' => $userId, 'role' => 'admin'])->get()->getRowArray();
    if (!$hospitalUser) {
        return redirect()->to('/admin/dashboard')->with('error', 'Hospital association not found.');
    }

    $hospitalId = $hospitalUser['hospital_id'];

    // Fetch patients of this hospital
    $patients = $db->query("
        SELECT 
            p.id AS patient_id,
            u.name,
            u.email,
            p.age,
            p.gender
        FROM patients p
        JOIN hospital_users hu ON p.user_hospital_id = hu.id
        JOIN users u ON hu.user_id = u.id
        WHERE hu.hospital_id = ? AND hu.role = 'patient'
        ORDER BY u.name ASC
    ", [$hospitalId])->getResultArray();

    return view('admin/listPatients', ['patients' => $patients]);
}

public function addPatient()
{
    return view('admin/addPatient');
}

public function addPatientPost()
{
    $session = session();
    $db = \Config\Database::connect();

    $userModel = new UserModel();
    $hospitalUserModel = new HospitalUserModel();
    $patientModel = new PatientModel();

    $userId = $session->get('id');
    $hospitalUser = $db->table('hospital_users')->where(['user_id' => $userId, 'role' => 'admin'])->get()->getRowArray();
    $hospitalId = $hospitalUser['hospital_id'];

    $db->transStart();

    // Step 1: Insert into users
    $userData = [
        'name' => $this->request->getPost('name'),
        'email' => $this->request->getPost('email'),
        'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
        'role' => 'patient',
    ];
    $userModel->insert($userData);
    $newUserId = $userModel->getInsertID();

    // Step 2: Insert into hospital_users
    $hospitalUserModel->insert([
        'user_id' => $newUserId,
        'hospital_id' => $hospitalId,
        'role' => 'patient',
        'status' => 'active',
    ]);
    $userHospitalId = $hospitalUserModel->getInsertID();

    // Step 3: Insert into patients
    $patientModel->insert([
        'user_hospital_id' => $userHospitalId,
        'age' => $this->request->getPost('age'),
        'gender' => $this->request->getPost('gender'),
    ]);

    $db->transComplete();

    return redirect()->to('/admin/listPatients')->with('success', 'Patient added successfully.');
}

public function editPatient($id)
{
    $db = \Config\Database::connect();
    $patient = $db->query("
        SELECT 
            u.id AS user_id, u.name, u.email,
            p.id AS patient_id, p.age, p.gender
        FROM patients p
        JOIN hospital_users hu ON p.user_hospital_id = hu.id
        JOIN users u ON hu.user_id = u.id
        WHERE p.id = ?
    ", [$id])->getRowArray();

    return view('admin/editPatient', ['patient' => $patient]);
}

public function updatePatient($id)
{
    $db = \Config\Database::connect();
    $userModel = new UserModel();
    $patientModel = new PatientModel();

    $patient = $db->query("
        SELECT u.id AS user_id FROM patients p
        JOIN hospital_users hu ON p.user_hospital_id = hu.id
        JOIN users u ON hu.user_id = u.id
        WHERE p.id = ?
    ", [$id])->getRowArray();

    if (!$patient) {
        return redirect()->back()->with('error', 'Patient not found.');
    }

    // Update user & patient
    $userModel->update($patient['user_id'], [
        'name' => $this->request->getPost('name'),
        'email' => $this->request->getPost('email'),
    ]);

    $patientModel->update($id, [
        'age' => $this->request->getPost('age'),
        'gender' => $this->request->getPost('gender'),
    ]);

    return redirect()->to('/admin/listPatients')->with('success', 'Patient updated successfully.');
}

public function deletePatient($id)
{
    $db = \Config\Database::connect();

    // Get linked user & hospital_user
    $data = $db->query("
        SELECT u.id AS user_id, hu.id AS hu_id
        FROM patients p
        JOIN hospital_users hu ON p.user_hospital_id = hu.id
        JOIN users u ON hu.user_id = u.id
        WHERE p.id = ?
    ", [$id])->getRowArray();

    if ($data) {
        $db->table('patients')->delete(['id' => $id]);
        $db->table('hospital_users')->delete(['id' => $data['hu_id']]);
        $db->table('users')->delete(['id' => $data['user_id']]);
    }

    return redirect()->to('/admin/listPatients')->with('success', 'Patient deleted successfully.');
}




public function listAppointments()
{
    $session = session();

    if (!$session->get('isLoggedIn') || $session->get('role') !== 'admin') {
        return redirect()->to('/login')->with('error', 'Access denied.');
    }

    $db = \Config\Database::connect();
    $userId = $session->get('id');

    // ✅ Find the admin's hospital_id
    $hospitalUser = $db->table('hospital_users')
        ->where(['user_id' => $userId, 'role' => 'admin'])
        ->get()
        ->getRowArray();

    if (!$hospitalUser) {
        return redirect()->to('/login')->with('error', 'Hospital not found for this admin.');
    }

    $hospitalId = $hospitalUser['hospital_id'];

    // ✅ Filters
    $doctorId = $this->request->getGet('doctor_id');
    $patientId = $this->request->getGet('patient_id');
    $status = $this->request->getGet('status');

    // ✅ Main query: appointments + doctor + patient + users
    $builder = $db->table('appointments a')
        ->select("
            a.*, 
            d.id AS doctor_id, 
            du.name AS doctor_name, du.email AS doctor_email,
            p.id AS patient_id, 
            pu.name AS patient_name, pu.email AS patient_email
        ")
        ->join('doctors d', 'a.doctor_id = d.id')
        ->join('hospital_users dhu', 'd.user_hospital_id = dhu.id')
        ->join('users du', 'dhu.user_id = du.id')

        ->join('patients p', 'a.patient_id = p.id')
        ->join('hospital_users phu', 'p.user_hospital_id = phu.id')
        ->join('users pu', 'phu.user_id = pu.id')

        ->where('a.hospital_id', $hospitalId);

    // ✅ Apply filters
    if (!empty($doctorId)) {
        $builder->where('a.doctor_id', $doctorId);
    }

    if (!empty($patientId)) {
        $builder->where('a.patient_id', $patientId);
    }

    if (!empty($status)) {
        $builder->where('a.status', $status);
    }

    $appointments = $builder->orderBy('a.start_datetime', 'DESC')->get()->getResultArray();

    // ✅ Fetch dropdown lists for filters
    $doctors = $db->table('doctors d')
        ->select('d.id, u.name')
        ->join('hospital_users hu', 'd.user_hospital_id = hu.id')
        ->join('users u', 'hu.user_id = u.id')
        ->where('hu.hospital_id', $hospitalId)
        ->get()->getResultArray();

    $patients = $db->table('patients p')
        ->select('p.id, u.name')
        ->join('hospital_users hu', 'p.user_hospital_id = hu.id')
        ->join('users u', 'hu.user_id = u.id')
        ->where('hu.hospital_id', $hospitalId)
        ->get()->getResultArray();

    return view('admin/listAppointments', [
        'appointments' => $appointments,
        'doctors' => $doctors,
        'patients' => $patients,
        'selectedDoctor' => $doctorId,
        'selectedPatient' => $patientId,
        'selectedStatus' => $status,
    ]);
}



}
