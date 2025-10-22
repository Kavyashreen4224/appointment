<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\DoctorModel;
use App\Models\PatientModel;
use App\Models\AppointmentModel;
use App\Models\VisitHistoryModel;
use App\Models\HospitalModel;
use App\Models\PrescriptionModel;

class DoctorController extends BaseController
{
    protected $userModel;
    protected $doctorModel;
    protected $patientModel;
    protected $appointmentModel;
    protected $visitHistoryModel;
    protected $hospitalModel;
    protected $session;
     protected $prescriptionModel; 

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->doctorModel = new DoctorModel();
        $this->patientModel = new PatientModel();
        $this->appointmentModel = new AppointmentModel();
        $this->visitHistoryModel = new VisitHistoryModel();
        $this->hospitalModel = new HospitalModel();
        $this->prescriptionModel = new PrescriptionModel(); 
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

    public function patientProfile($patient_id)
{
    $hospitalId = $this->session->get('hospital_id');

    // Fetch patient info
    $patient = $this->patientModel
        ->select('patients.*, users.name, users.email, users.role')
        ->join('users', 'users.id = patients.user_id')
        ->where('patients.id', $patient_id)
        ->where('patients.hospital_id', $hospitalId)
        ->first();

    if (!$patient) {
        return redirect()->back()->with('error', 'Patient not found.');
    }

    // Fetch visit history with doctor names and prescriptions
    $visits = $this->visitHistoryModel
        ->select('visit_history.*, doctors.id as doctor_id, users.name as doctor_name, prescriptions.id as prescription_id, prescriptions.prescription_text')
        ->join('doctors', 'doctors.id = visit_history.doctor_id', 'left')
        ->join('users', 'users.id = doctors.user_id', 'left')
        ->join('prescriptions', 'prescriptions.visit_id = visit_history.id', 'left')
        ->where('visit_history.patient_id', $patient_id)
        ->orderBy('visit_history.created_at', 'DESC')
        ->findAll();

    return view('doctor/patient_profile', [
        'patient' => $patient,
        'visits' => $visits
    ]);
}


    // --------------------------------------------------------
    // Appointments list
    // --------------------------------------------------------
    public function appointments()
    {
        $doctor = $this->doctorModel->where('user_id', $this->session->get('user_id'))->first();

       $appointments = $this->appointmentModel
    ->select('appointments.*, u.name AS patient_name, p.id AS patient_id, pr.id AS prescription_id')
    ->join('patients p', 'p.id = appointments.patient_id', 'left')
    ->join('users u', 'u.id = p.user_id', 'left')
    ->join('visit_history vh', 'vh.appointment_id = appointments.id', 'left')
    ->join('prescriptions pr', 'pr.visit_id = vh.id', 'left')
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
// Add Appointment Form
// --------------------------------------------------------
public function addAppointment()
{
    $hospitalId = $this->session->get('hospital_id');
    $doctor = $this->doctorModel->where('user_id', $this->session->get('user_id'))->first();

    // Fetch patients in this hospital
    $patients = $this->patientModel
        ->select('patients.*, users.name, users.email')
        ->join('users', 'users.id = patients.user_id')
        ->where('patients.hospital_id', $hospitalId)
        ->findAll();

    return view('doctor/add_appointment', [
        'patients' => $patients,
        'doctor' => $doctor
    ]);
}

// --------------------------------------------------------
// Save Appointment
// --------------------------------------------------------
public function saveAppointment()
{
    $doctor = $this->doctorModel->where('user_id', $this->session->get('user_id'))->first();
    if (!$doctor) {
        return redirect()->back()->with('error', 'Doctor not found.');
    }

    $this->appointmentModel->insert([
        'doctor_id' => $doctor['id'],
        'patient_id' => $this->request->getPost('patient_id'),
        'start_datetime' => $this->request->getPost('start_datetime'),
        'end_datetime' => $this->request->getPost('end_datetime'),
        'status' => 'pending',
        'created_by' => $this->session->get('user_id'),
        'created_at' => date('Y-m-d H:i:s')
    ]);

    return redirect()->to('doctor/appointments')->with('success', 'Appointment added successfully!');
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

public function addPrescription($visit_id)
{
    $visit = $this->visitHistoryModel->find($visit_id);
    if (!$visit) {
        return redirect()->back()->with('error', 'Visit not found.');
    }

    $patient = $this->patientModel
        ->select('patients.*, users.name as patient_name, users.email')
        ->join('users', 'users.id = patients.user_id')
        ->where('patients.id', $visit['patient_id'])
        ->first();

    // Fetch the related appointment
    $appointment = $this->appointmentModel->find($visit['appointment_id']);

    return view('doctor/add_prescription', [
        'visit' => $visit,
        'patient' => $patient,
        'appointment' => $appointment  // <-- pass this to view
    ]);
}



// Save Prescription

public function savePrescription()
{
    $visit_id = $this->request->getPost('visit_id');
    $prescription_text = $this->request->getPost('prescription_text');

    if (!$visit_id || !$prescription_text) {
        return redirect()->back()->with('error', 'All fields are required.');
    }

    // Get the visit record
    $visit = $this->visitHistoryModel->find($visit_id);
    if (!$visit) {
        return redirect()->back()->with('error', 'Visit not found.');
    }

    // Make sure the patient exists
    $patient = $this->patientModel->find($visit['patient_id']);
    if (!$patient) {
        return redirect()->back()->with('error', 'Patient not found.');
    }

    // Make sure the doctor exists
    $doctor = $this->doctorModel->find($visit['doctor_id']);
    if (!$doctor) {
        return redirect()->back()->with('error', 'Doctor not found.');
    }

    // Insert prescription
    $this->prescriptionModel->insert([
        'visit_id' => $visit_id,
        'appointment_id' => $visit['appointment_id'],
        'patient_id' => $visit['patient_id'],
        'doctor_id' => $visit['doctor_id'],    // ✅ required foreign key
        'prescription_text' => $prescription_text,
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ]);

    return redirect()->to('doctor/appointments')->with('success', 'Prescription saved successfully!');
}



// View Prescription
public function viewPrescription($prescription_id)
{
    $prescription = $this->prescriptionModel->find($prescription_id);
    if (!$prescription) return redirect()->back()->with('error', 'Prescription not found.');

    $visit = $this->visitHistoryModel->find($prescription['visit_id']);
    $patient = $this->patientModel
    ->select('patients.*, users.name as patient_name, users.email')
    ->join('users', 'users.id = patients.user_id')
    ->where('patients.id', $visit['patient_id'])
    ->first();


    return view('doctor/view_prescription', [
        'prescription' => $prescription,
        'patient' => $patient,
        'visit' => $visit
    ]);
}

 
}
