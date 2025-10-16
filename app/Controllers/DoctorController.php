<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\DoctorModel;
use App\Models\PatientModel;
use App\Models\AppointmentModel;
use App\Models\VisitHistoryModel;
use App\Models\HospitalModel;

class DoctorController extends BaseController
{
    protected $userModel;
    protected $doctorModel;
    protected $patientModel;
    protected $appointmentModel;
    protected $visitHistoryModel;
    protected $hospitalModel;
    protected $session;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->doctorModel = new DoctorModel();
        $this->patientModel = new PatientModel();
        $this->appointmentModel = new AppointmentModel();
        $this->visitHistoryModel = new VisitHistoryModel();
        $this->hospitalModel = new HospitalModel();
        $this->session = session();
    }

    // --------------------------------------------------------
    // Dashboard - Overview for Doctor
    // --------------------------------------------------------
public function dashboard()
{
    $user_id = session()->get('user_id');
    $hospital_id = session()->get('hospital_id'); // the hospital selected during login

    $db = \Config\Database::connect();

    // Fetch doctor record matching the logged-in user and hospital
    $doctor = $db->table('doctors')
        ->select('doctors.*, users.name, users.email, hospitals.name AS hospital_name')
        ->join('users', 'users.id = doctors.user_id', 'left')
        ->join('hospitals', 'hospitals.id = doctors.hospital_id', 'left')
        ->where('doctors.user_id', $user_id)
        ->where('doctors.hospital_id', $hospital_id)
        ->get()
        ->getRowArray();

    if (!$doctor) {
        return redirect()->to('/login')->with('error', 'Doctor not found for this hospital.');
    }

    // Count total patients
    $total_patients = $db->table('patients')
        ->where('hospital_id', $hospital_id)
        ->countAllResults();

    // Count total appointments for this doctor
    $total_appointments = $db->table('appointments')
        ->where('doctor_id', $doctor['id'])
        ->countAllResults();

    // Fetch recent appointments for display
    $appointments = $db->table('appointments')
        ->select('appointments.*, users.name AS patient_name')
        ->join('patients', 'patients.id = appointments.patient_id', 'left')
        ->join('users', 'users.id = patients.user_id', 'left')
        ->where('appointments.doctor_id', $doctor['id'])
        ->orderBy('appointments.start_datetime', 'DESC')
        ->get()
        ->getResultArray();

    return view('doctor/dashboard', [
        'doctor' => $doctor,
        'total_patients' => $total_patients,
        'total_appointments' => $total_appointments,
        'appointments' => $appointments
    ]);
}




    // --------------------------------------------------------
    // List all patients under this hospital
    // --------------------------------------------------------
    public function patients()
    {
        $hospitalId = $this->session->get('hospital_id');
        $patients = $this->patientModel
            ->select('patients.*, users.name, users.email')
            ->join('users', 'users.id = patients.user_id', 'left')
            ->where('patients.hospital_id', $hospitalId)
            ->findAll();

        return view('doctor/patients', ['patients' => $patients]);
    }

    // --------------------------------------------------------
    // Add Patient (form)
    // --------------------------------------------------------
    public function addPatient()
    {
        return view('doctor/add_patient');
    }

    // --------------------------------------------------------
    // Save new patient (with user entry)
    // --------------------------------------------------------
    public function savePatient()
    {
        $hospitalId = $this->session->get('hospital_id');
        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role' => 'patient',
            'hospital_id' => $hospitalId,
        ];

        // 1. Create user entry
        $this->userModel->insert($data);
        $userId = $this->userModel->getInsertID();

        // 2. Create patient entry
        $this->patientModel->insert([
            'user_id' => $userId,
            'hospital_id' => $hospitalId,
            'age' => $this->request->getPost('age'),
            'gender' => $this->request->getPost('gender'),
            'created_by' => $this->session->get('user_id'),
        ]);

        return redirect()->to('doctor/patients')->with('success', 'Patient registered successfully!');
    }

    // --------------------------------------------------------
    // Edit patient form
    // --------------------------------------------------------
    public function editPatient($id)
    {
        $patient = $this->patientModel
            ->select('patients.*, users.name, users.email')
            ->join('users', 'users.id = patients.user_id', 'left')
            ->where('patients.id', $id)
            ->first();

        return view('doctor/edit_patient', ['patient' => $patient]);
    }

    // --------------------------------------------------------
    // Update patient info
    // --------------------------------------------------------
    public function updatePatient($id)
    {
        $patient = $this->patientModel->find($id);
        if (!$patient) {
            return redirect()->back()->with('error', 'Patient not found');
        }

        $userId = $patient['user_id'];

        // Update user table
        $this->userModel->update($userId, [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
        ]);

        // Update patient table
        $this->patientModel->update($id, [
            'age' => $this->request->getPost('age'),
            'gender' => $this->request->getPost('gender'),
            'updated_by' => $this->session->get('user_id'),
        ]);

        return redirect()->to('doctor/patients')->with('success', 'Patient updated successfully!');
    }

    // --------------------------------------------------------
    // Appointments list
    // --------------------------------------------------------
    public function appointments()
    {
        $doctor = $this->doctorModel->where('user_id', $this->session->get('user_id'))->first();

        $appointments = $this->appointmentModel
            ->select('appointments.*, u.name AS patient_name')
            ->join('patients p', 'p.id = appointments.patient_id', 'left')
            ->join('users u', 'u.id = p.user_id', 'left')
            ->where('appointments.doctor_id', $doctor['id'])
            ->orderBy('appointments.start_datetime', 'DESC')
            ->findAll();

        return view('doctor/appointments', ['appointments' => $appointments]);
    }

    // --------------------------------------------------------
    // View appointment and its visit history
    // --------------------------------------------------------
    public function appointment($id)
    {
        $appointment = $this->appointmentModel
            ->select('appointments.*, u.name AS patient_name')
            ->join('patients p', 'p.id = appointments.patient_id', 'left')
            ->join('users u', 'u.id = p.user_id', 'left')
            ->where('appointments.id', $id)
            ->first();

        if (!$appointment) {
            return redirect()->back()->with('error', 'Appointment not found.');
        }

        $visits = $this->visitHistoryModel
            ->where('appointment_id', $id)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        return view('doctor/view_appointment', [
            'appointment' => $appointment,
            'visits' => $visits
        ]);
    }

    // --------------------------------------------------------
    // Add Visit Record
    // --------------------------------------------------------
    public function addVisit($appointmentId)
    {
        $appointment = $this->appointmentModel->find($appointmentId);
        if (!$appointment) {
            return redirect()->back()->with('error', 'Appointment not found');
        }

        $this->visitHistoryModel->insert([
            'appointment_id' => $appointmentId,
            'patient_id' => $appointment['patient_id'],
            'doctor_id' => $appointment['doctor_id'],
            'reason' => $this->request->getPost('reason'),
            'weight' => $this->request->getPost('weight'),
            'blood_pressure' => $this->request->getPost('blood_pressure'),
            'doctor_comments' => $this->request->getPost('doctor_comments'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->back()->with('success', 'Visit record added successfully!');
    }

     public function viewAppointment($appointment_id)
    {
        $db = \Config\Database::connect();

        // Get appointment info
        $appointment = $db->table('appointments')
            ->select('appointments.*, patients.user_id as patient_id, users.name as patient_name, users.email as patient_email')
            ->join('patients', 'patients.user_id = appointments.patient_id')
            ->join('users', 'users.id = patients.user_id')
            ->where('appointments.id', $appointment_id)
            ->get()
            ->getRowArray();

        if (!$appointment) {
            return redirect()->back()->with('error', 'Appointment not found');
        }

        // Get visit history for this patient
        $visit_history = $this->visitHistoryModel
            ->where('patient_id', $appointment['patient_id'])
            ->orderBy('created_at', 'DESC')
            ->findAll();

        return view('doctor/view_appointment', [
            'appointment' => $appointment,
            'visit_history' => $visit_history
        ]);
    }

 
}
