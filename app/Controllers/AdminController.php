<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\DoctorModel;
use App\Models\PatientModel;
use App\Models\AppointmentModel;
use App\Models\VisitHistoryModel;
use App\Models\PrescriptionModel;

class AdminController extends BaseController
{
    protected $userModel;
    protected $doctorModel;
    protected $patientModel;
    protected $appointmentModel;
    protected $visitHistoryModel;
    protected $prescriptionModel;
    protected $session;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->doctorModel = new DoctorModel();
        $this->patientModel = new PatientModel();
        $this->appointmentModel = new AppointmentModel();
        $this->visitHistoryModel = new VisitHistoryModel();
        $this->prescriptionModel = new PrescriptionModel();
        $this->session = session();
    }

    // --------------------------------------------------------
    // Admin Dashboard (Hospital-specific)
    // --------------------------------------------------------
public function dashboard()
{
    $hospitalId = session()->get('hospital_id');

    if (!$hospitalId) {
        return redirect()->to('login')->with('error', 'No hospital assigned to your account.');
    }

    $db = \Config\Database::connect();

    // Count total doctors for this hospital
    $totalDoctors = $db->table('doctors')
        ->where('hospital_id', $hospitalId)
        ->countAllResults();

    // Count total patients for this hospital
    $totalPatients = $db->table('patients')
        ->where('hospital_id', $hospitalId)
        ->countAllResults();

    // Count total appointments (join with doctor or patient to get hospital_id)
    $totalAppointments = $db->table('appointments')
        ->join('doctors', 'doctors.id = appointments.doctor_id', 'left')
        ->where('doctors.hospital_id', $hospitalId)
        ->countAllResults();

    // Count total visits (join with doctor to get hospital_id)
    $totalVisits = $db->table('visit_history')
        ->join('doctors', 'doctors.id = visit_history.doctor_id', 'left')
        ->where('doctors.hospital_id', $hospitalId)
        ->countAllResults();

    return view('admin/dashboard', [
        'totalDoctors' => $totalDoctors,
        'totalPatients' => $totalPatients,
        'totalAppointments' => $totalAppointments,
        'totalVisits' => $totalVisits,
    ]);
}

    // --------------------------------------------------------
    // List patients (hospital-specific)
    // --------------------------------------------------------
  

    // --------------------------------------------------------
    // View patient profile (with visits & prescriptions)
    // --------------------------------------------------------
    public function patient($patient_id)
    {
        $hospital_id = $this->session->get('hospital_id');

        $patient = $this->patientModel
            ->select('patients.*, users.name, users.email')
            ->join('users', 'users.id = patients.user_id')
            ->where('patients.id', $patient_id)
            ->where('patients.hospital_id', $hospital_id)
            ->first();

        if (!$patient) {
            return redirect()->back()->with('error', 'Patient not found.');
        }

        $visits = $this->visitHistoryModel
            ->select('visit_history.*, doctors.id as doctor_id, doctor_users.name as doctor_name, prescriptions.id as prescription_id, prescriptions.prescription_text')
            ->join('doctors', 'doctors.id = visit_history.doctor_id')
            ->join('users as doctor_users', 'doctor_users.id = doctors.user_id')
            ->join('prescriptions', 'prescriptions.visit_id = visit_history.id', 'left')
            ->where('visit_history.patient_id', $patient_id)
            ->get()
            ->getResultArray();

        return view('admin/patient_profile', [
            'patient' => $patient,
            'visits' => $visits
        ]);
    }

    // --------------------------------------------------------
    // List doctors (hospital-specific)
    // --------------------------------------------------------

    // --------------------------------------------------------
    // List appointments (hospital-specific)
    // --------------------------------------------------------
    public function appointments()
    {
        $hospital_id = $this->session->get('hospital_id');

        $appointments = $this->appointmentModel
            ->select('appointments.*, u.name as patient_name, d.name as doctor_name')
            ->join('patients p', 'p.id = appointments.patient_id', 'left')
            ->join('users u', 'u.id = p.user_id', 'left')
            ->join('doctors doc', 'doc.id = appointments.doctor_id', 'left')
            ->join('users d', 'd.id = doc.user_id', 'left')
            ->where('doc.hospital_id', $hospital_id)
            ->orderBy('appointments.start_datetime', 'DESC')
            ->findAll();

        return view('admin/appointments', ['appointments' => $appointments]);
  
    }

// public function patientProfile($patient_id)
// {
//     $patientModel = new \App\Models\PatientModel();
//     $visitModel = new \App\Models\VisitHistoryModel();
//     $userModel = new \App\Models\UserModel();

//     // Fetch patient info (with user & hospital)
//     $patient = $patientModel
//         ->select('patients.*, users.name as patient_name, users.email, hospitals.name as hospital_name')
//         ->join('users', 'users.id = patients.user_id')
//         ->join('hospitals', 'hospitals.id = patients.hospital_id', 'left')
//         ->where('patients.id', $patient_id)
//         ->first();

//     // Fetch visit history with doctor names and prescriptions
//     $visits = $visitModel
//         ->select('visit_history.*, 
//                   u.name as doctor_name, 
//                   prescriptions.id as prescription_id')
//         ->join('doctors d', 'd.id = visit_history.doctor_id')
//         ->join('users u', 'u.id = d.user_id')  // <--- join with users to get doctor name
//         ->join('prescriptions', 'prescriptions.visit_id = visit_history.id', 'left')
//         ->where('visit_history.patient_id', $patient_id)
//         ->orderBy('visit_history.created_at', 'DESC')
//         ->findAll();

//     return view('admin/patient_profile', [
//         'patient' => $patient,
//         'visits' => $visits
//     ]);
// }


public function viewPrescription($id)
{
    $prescriptionModel = new \App\Models\PrescriptionModel();
    $doctorModel = new \App\Models\DoctorModel();
    $patientModel = new \App\Models\PatientModel();

 $prescription = $this->prescriptionModel
    ->select('prescriptions.*, 
              u.name as doctor_name, 
              p.user_id as patient_user_id, 
              up.name as patient_name, 
              up.email as patient_email')
    ->join('doctors d', 'd.id = prescriptions.doctor_id', 'left')
    ->join('users u', 'u.id = d.user_id', 'left')  // doctor name
    ->join('patients p', 'p.id = prescriptions.patient_id', 'left')
    ->join('users up', 'up.id = p.user_id', 'left')  // patient name/email
    ->where('prescriptions.id', $id)
    ->first();


    if (!$prescription) {
        throw new \CodeIgniter\Exceptions\PageNotFoundException('Prescription not found');
    }

    return view('admin/view_prescription', ['prescription' => $prescription]);
}


public function doctors()
{
    $hospitalId = $this->session->get('hospital_id');

    $doctors = $this->doctorModel
        ->select('doctors.*, u.name, u.email, u.role')
        ->join('users u', 'u.id = doctors.user_id', 'left')
        ->where('doctors.hospital_id', $hospitalId)
        ->findAll();

    return view('admin/doctors', ['doctors' => $doctors]);
}



// --------------------------------------------------------
public function doctorProfile($doctor_id)
{
    $doctor = $this->doctorModel
        ->select('doctors.*, u.name as doctor_name, u.email')
        ->join('users u', 'u.id = doctors.user_id')
        ->where('doctors.id', $doctor_id)
        ->first();

    if (!$doctor) {
        return redirect()->back()->with('error', 'Doctor not found');
    }

    // Fetch appointments for this doctor
    $appointments = $this->appointmentModel
        ->select('appointments.*, p.id as patient_id, u.name as patient_name')
        ->join('patients p', 'p.id = appointments.patient_id', 'left')
        ->join('users u', 'u.id = p.user_id', 'left')
        ->where('appointments.doctor_id', $doctor_id)
        ->orderBy('appointments.start_datetime', 'DESC')
        ->findAll();

    return view('admin/doctor_profile', [
        'doctor' => $doctor,
        'appointments' => $appointments
    ]);
}
    // --------------------------------------------------------
    // Add Doctor Form
    // --------------------------------------------------------
    public function addDoctor()
    {
        return view('admin/add_doctor');
    }

    // --------------------------------------------------------
    // Save Doctor
    // --------------------------------------------------------
    public function saveDoctor()
    {
        $hospitalId = $this->session->get('hospital_id');

        // 1. Create user entry
        $userId = $this->userModel->insert([
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role' => 'doctor',
            'hospital_id' => $hospitalId,
        ]);

        $userId = $this->userModel->getInsertID();

        // 2. Create doctor entry
        $this->doctorModel->insert([
            'user_id' => $userId,
            'hospital_id' => $hospitalId,
            'age' => $this->request->getPost('age'),
            'gender' => $this->request->getPost('gender'),
            'expertise' => $this->request->getPost('expertise'),
            'availability' => $this->request->getPost('availability'),
            'created_by' => $this->session->get('user_id'),
        ]);

        return redirect()->to('admin/doctors')->with('success', 'Doctor added successfully!');
    }

    // --------------------------------------------------------
    // Edit Doctor Form
    // --------------------------------------------------------
    public function editDoctor($id)
    {
        $doctor = $this->doctorModel
            ->select('doctors.*, u.name, u.email')
            ->join('users u', 'u.id = doctors.user_id', 'left')
            ->where('doctors.id', $id)
            ->first();

        return view('admin/edit_doctor', ['doctor' => $doctor]);
    }

    // --------------------------------------------------------
    // Update Doctor
    // --------------------------------------------------------
    public function updateDoctor($id)
    {
        $doctor = $this->doctorModel->find($id);
        if (!$doctor) return redirect()->back()->with('error', 'Doctor not found');

        $userId = $doctor['user_id'];

        $this->userModel->update($userId, [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
        ]);

        $this->doctorModel->update($id, [
            'age' => $this->request->getPost('age'),
            'gender' => $this->request->getPost('gender'),
            'expertise' => $this->request->getPost('expertise'),
            'availability' => $this->request->getPost('availability'),
            'updated_by' => $this->session->get('user_id'),
        ]);

        return redirect()->to('admin/doctors')->with('success', 'Doctor updated successfully!');
    }

    // --------------------------------------------------------
    // Delete Doctor
    // --------------------------------------------------------
    public function deleteDoctor($id)
    {
        $doctor = $this->doctorModel->find($id);
        if (!$doctor) return redirect()->back()->with('error', 'Doctor not found');

        $userId = $doctor['user_id'];

        $this->doctorModel->delete($id);
        $this->userModel->delete($userId);

        return redirect()->to('admin/doctors')->with('success', 'Doctor deleted successfully!');
    }



    // --------------------------------------------------------
// List Patients
// --------------------------------------------------------
public function patients()
{
    $hospitalId = $this->session->get('hospital_id');

    $patients = $this->patientModel
        ->select('patients.*, u.name, u.email')
        ->join('users u', 'u.id = patients.user_id', 'left')
        ->where('patients.hospital_id', $hospitalId)
        ->findAll();

    return view('admin/patients', ['patients' => $patients]);
}

// --------------------------------------------------------
// Add Patient Form
// --------------------------------------------------------
public function addPatient()
{
    return view('admin/add_patient');
}

// --------------------------------------------------------
// Save Patient
// --------------------------------------------------------
public function savePatient()
{
    $hospitalId = $this->session->get('hospital_id');

    // 1. Create user entry
    $this->userModel->insert([
        'name' => $this->request->getPost('name'),
        'email' => $this->request->getPost('email'),
        'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
        'role' => 'patient',
        'hospital_id' => $hospitalId
    ]);
    $userId = $this->userModel->getInsertID();

    // 2. Create patient entry
    $this->patientModel->insert([
        'user_id' => $userId,
        'hospital_id' => $hospitalId,
        'age' => $this->request->getPost('age'),
        'gender' => $this->request->getPost('gender'),
        'created_by' => $this->session->get('user_id')
    ]);

    return redirect()->to('admin/patients')->with('success', 'Patient added successfully!');
}

// --------------------------------------------------------
// Edit Patient Form
// --------------------------------------------------------
public function editPatient($id)
{
    $patient = $this->patientModel
        ->select('patients.*, u.name, u.email')
        ->join('users u', 'u.id = patients.user_id', 'left')
        ->where('patients.id', $id)
        ->first();

    return view('admin/edit_patient', ['patient' => $patient]);
}

// --------------------------------------------------------
// Update Patient
// --------------------------------------------------------
public function updatePatient($id)
{
    $patient = $this->patientModel->find($id);
    if (!$patient) return redirect()->back()->with('error', 'Patient not found');

    $userId = $patient['user_id'];

    $this->userModel->update($userId, [
        'name' => $this->request->getPost('name'),
        'email' => $this->request->getPost('email')
    ]);

    $this->patientModel->update($id, [
        'age' => $this->request->getPost('age'),
        'gender' => $this->request->getPost('gender'),
        'updated_by' => $this->session->get('user_id')
    ]);

    return redirect()->to('admin/patients')->with('success', 'Patient updated successfully!');
}

// --------------------------------------------------------
// Delete Patient
// --------------------------------------------------------
public function deletePatient($id)
{
    $patient = $this->patientModel->find($id);
    if (!$patient) return redirect()->back()->with('error', 'Patient not found');

    $userId = $patient['user_id'];

    $this->patientModel->delete($id);
    $this->userModel->delete($userId);

    return redirect()->to('admin/patients')->with('success', 'Patient deleted successfully!');
}

// --------------------------------------------------------
// View Patient Profile (with Visit History + Prescription)
// --------------------------------------------------------
public function patientProfile($patient_id)
{
    $patient = $this->patientModel
        ->select('patients.*, u.name as patient_name, u.email')
        ->join('users u', 'u.id = patients.user_id')
        ->where('patients.id', $patient_id)
        ->first();

    $visits = $this->visitHistoryModel
        ->select('visit_history.*, d.id as doctor_id, u.name as doctor_name, p.id as prescription_id')
        ->join('doctors d', 'd.id = visit_history.doctor_id')
        ->join('users u', 'u.id = d.user_id')
        ->join('prescriptions p', 'p.visit_id = visit_history.id', 'left')
        ->where('visit_history.patient_id', $patient_id)
        ->orderBy('visit_history.created_at', 'DESC')
        ->findAll();

    return view('admin/patient_profile', [
        'patient' => $patient,
        'visits' => $visits
    ]);
}


public function viewAppointment($appointment_id)
{
    $db = \Config\Database::connect();

    $appointment = $db->table('appointments a')
        ->select('a.*, p.id as patient_id, u.name as patient_name, u.email as patient_email, d.id as doctor_id, du.name as doctor_name')
        ->join('patients p', 'p.id = a.patient_id', 'left')
        ->join('users u', 'u.id = p.user_id', 'left')
        ->join('doctors d', 'd.id = a.doctor_id', 'left')
        ->join('users du', 'du.id = d.user_id', 'left')
        ->where('a.id', $appointment_id)
        ->get()
        ->getRowArray();

    if (!$appointment) {
        return redirect()->back()->with('error', 'Appointment not found.');
    }

    $visit_history = $this->visitHistoryModel
        ->where('appointment_id', $appointment_id)
        ->orderBy('created_at', 'DESC')
        ->findAll();

    return view('admin/view_appointment', [
        'appointment' => $appointment,
        'visit_history' => $visit_history
    ]);
}

}

