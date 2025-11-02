<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\HospitalUserModel;
use App\Models\DoctorModel;
use App\Models\AppointmentModel;
use App\Models\PatientModel;
use App\Models\UserModel;

class DoctorController extends Controller
{
    public function dashboard()
    {
        $session = session();

        // 🔒 Role-based access check
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'doctor') {
            return redirect()->to('/login')->with('error', 'Access denied.');
        }

        $db = \Config\Database::connect();
        $userId = $session->get('id');

        // ✅ Step 1: Find hospital_user for this doctor
        $hospitalUser = $db->table('hospital_users')
            ->where(['user_id' => $userId, 'role' => 'doctor'])
            ->get()
            ->getRowArray();

        if (!$hospitalUser) {
            return redirect()->to('/login')->with('error', 'Doctor-hospital association not found.');
        }

        $hospitalId = $hospitalUser['hospital_id'];

        // ✅ Step 2: Fetch doctor record joined with users (to get doctor name)
        $doctor = $db->table('doctors d')
            ->select('d.*, u.name AS doctor_name, u.email AS doctor_email')
            ->join('hospital_users hu', 'd.user_hospital_id = hu.id')
            ->join('users u', 'hu.user_id = u.id')
            ->where('d.user_hospital_id', $hospitalUser['id'])
            ->get()
            ->getRowArray();

        if (!$doctor) {
            return redirect()->to('/login')->with('error', 'Doctor record not found.');
        }

        // ✅ Step 3: Count total appointments for this doctor
        $appointmentCount = $db->table('appointments')
            ->where('doctor_id', $doctor['id'])
            ->countAllResults();

        // ✅ Step 4: Count unique patients seen
        $patientCount = $db->table('appointments')
            ->select('COUNT(DISTINCT patient_id) AS total')
            ->where('doctor_id', $doctor['id'])
            ->get()
            ->getRow()->total ?? 0;

        // ✅ Step 5: Get upcoming appointments (next 5)
        $upcomingAppointments = $db->table('appointments a')
            ->select('a.*, u.name AS patient_name')
            ->join('patients p', 'a.patient_id = p.id')
            ->join('hospital_users hu', 'p.user_hospital_id = hu.id')
            ->join('users u', 'hu.user_id = u.id')
            ->where('a.doctor_id', $doctor['id'])
            ->where('a.start_datetime >=', date('Y-m-d H:i:s'))
            ->orderBy('a.start_datetime', 'ASC')
            ->limit(5)
            ->get()
            ->getResultArray();

        // ✅ Step 6: Fetch hospital info
        $hospital = $db->table('hospitals')->where('id', $hospitalId)->get()->getRowArray();

        // ✅ Step 7: Return view
        return view('doctor/dashboard', [
            'doctor' => $doctor,
            'hospital' => $hospital,
            'appointmentCount' => $appointmentCount,
            'patientCount' => $patientCount,
            'upcomingAppointments' => $upcomingAppointments,
        ]);
    }


  public function patientsList()
    {
        $session = session();
        $db = \Config\Database::connect();

        if (!$session->get('isLoggedIn') || $session->get('role') !== 'doctor') {
            return redirect()->to('/login')->with('error', 'Access denied.');
        }

        // Get hospital id
        $hospitalUser = $db->table('hospital_users')
            ->where('user_id', $session->get('id'))
            ->where('role', 'doctor')
            ->get()->getRowArray();

        $hospitalId = $hospitalUser['hospital_id'];

        // Fetch all patients in the same hospital
        $patients = $db->table('patients p')
            ->select('p.id AS patient_id, u.name, u.email, p.age, p.gender')
            ->join('hospital_users hu', 'p.user_hospital_id = hu.id')
            ->join('users u', 'hu.user_id = u.id')
            ->where('hu.hospital_id', $hospitalId)
            ->get()->getResultArray();

        return view('doctor/patients_list', ['patients' => $patients]);
    }

  public function addPatient()
    {
        return view('doctor/add_patient');
    }

    // ✅ Save Patient to all tables
    public function savePatient()
    {
        $session = session();
        $hospital_id = $session->get('hospital_id'); // doctor’s hospital id

        $userModel = new UserModel();
        $hospitalUserModel = new HospitalUserModel();
        $patientModel = new PatientModel();

        $name = $this->request->getPost('name');
        $email = $this->request->getPost('email');
        $password = password_hash('123456', PASSWORD_DEFAULT); // default password for patient
        $age = $this->request->getPost('age');
        $gender = $this->request->getPost('gender');

        $db = \Config\Database::connect();
        $db->transStart();

        // Step 1: Insert into users
        $userModel->insert([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'role' => 'patient',
            'created_at' => date('Y-m-d H:i:s')
        ]);
        $user_id = $userModel->getInsertID();

        // Step 2: Insert into hospital_users
        $hospitalUserModel->insert([
            'user_id' => $user_id,
            'hospital_id' => $hospital_id,
            'role' => 'patient',
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s')
        ]);
        $hospital_user_id = $hospitalUserModel->getInsertID();

        // Step 3: Insert into patients
        $patientModel->insert([
            'user_hospital_id' => $hospital_user_id,
            'age' => $age,
            'gender' => $gender,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        $db->transComplete();

        if ($db->transStatus() === FALSE) {
            return redirect()->back()->with('error', 'Failed to add patient.');
        }

        return redirect()->to('doctor/listPatients')->with('success', 'Patient added successfully.');
    }


}

