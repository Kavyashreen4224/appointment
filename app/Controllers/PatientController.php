<?php

namespace App\Controllers;

use App\Models\AppointmentModel;
use App\Models\UserModel;
use App\Models\PatientModel;
use App\Models\VisitHistoryModel;
use App\Models\PrescriptionModel;
use App\Models\DoctorModel;
use App\Models\HospitalModel;
use CodeIgniter\Controller;

class PatientController extends Controller
{
    protected $session;
    protected $userModel;
    protected $patientModel;
    protected $visitModel;
    protected $prescriptionModel;
    protected $doctorModel;
       protected $hospitalModel;
       protected $appointmentModel;

    public function __construct()
    {
        $this->session = session();
        $this->userModel = new UserModel();
        $this->patientModel = new PatientModel();
        $this->visitModel = new VisitHistoryModel();
        $this->prescriptionModel = new PrescriptionModel();
        $this->doctorModel = new DoctorModel();
        $this->appointmentModel = new AppointmentModel();
             $this->hospitalModel = new HospitalModel();
    }

    // ✅ Patient Dashboard/Profile
    public function dashboard()
    {
        // Logged-in user
        $user_id = $this->session->get('user_id');
        if (!$user_id) {
            return redirect()->to('/login')->with('error', 'Please login first.');
        }

        // Fetch patient info
        $patient = $this->patientModel
            ->select('patients.*, users.name, users.email')
            ->join('users', 'users.id = patients.user_id')
            ->where('patients.user_id', $user_id)
            ->first();

        if (!$patient) {
            return redirect()->back()->with('error', 'Patient not found');
        }

        // Fetch visit history (doctor names + prescriptions)
       $visits = $this->visitModel
    ->select('visit_history.*, 
              doctors.id as doctor_id, doctor_users.name as doctor_name, 
              prescriptions.id as prescription_id, prescriptions.prescription_text,
              bills.id as bill_id, bills.total_amount')
    ->join('doctors', 'doctors.id = visit_history.doctor_id')
    ->join('users as doctor_users', 'doctor_users.id = doctors.user_id')
    ->join('prescriptions', 'prescriptions.visit_id = visit_history.id', 'left')
    ->join('bills', 'bills.visit_id = visit_history.id', 'left')
    ->where('visit_history.patient_id', $patient['id'])
    ->get()
    ->getResultArray();


        return view('patient/dashboard', [
            'patient' => $patient,
            'visits' => $visits
        ]);
    }



    public function viewBill($bill_id)
{
    $billModel = new \App\Models\BillModel();
    $visitModel = new \App\Models\VisitHistoryModel();
    $doctorModel = new \App\Models\DoctorModel();
    $patientModel = new \App\Models\PatientModel();

    $bill = $billModel->find($bill_id);
    if (!$bill) {
        return redirect()->back()->with('error', 'Bill not found.');
    }

    $visit = $visitModel->find($bill['visit_id']);
    if (!$visit) {
        return redirect()->back()->with('error', 'Visit not found.');
    }

    $doctor = $doctorModel
        ->select('doctors.*, users.name as doctor_name')
        ->join('users', 'users.id = doctors.user_id')
        ->where('doctors.id', $visit['doctor_id'])
        ->first();

    $patient = $patientModel
        ->select('patients.*, users.name as patient_name')
        ->join('users', 'users.id = patients.user_id')
        ->where('patients.id', $visit['patient_id'])
        ->first();

    return view('patient/view_bill', [
        'bill' => $bill,
        'visit' => $visit,
        'doctor' => $doctor,
        'patient' => $patient
    ]);
}


    // ✅ Prescription download
   public function downloadPrescription($id)
{
    $prescriptionModel = new \App\Models\PrescriptionModel();
    $visitModel = new \App\Models\VisitHistoryModel();
    $userModel = new \App\Models\UserModel();
    $doctorModel = new \App\Models\DoctorModel();
    $patientModel = new \App\Models\PatientModel();

    $prescription = $prescriptionModel->find($id);
    if (!$prescription) {
        return redirect()->back()->with('error', 'Prescription not found');
    }

    $visit = $visitModel->find($prescription['visit_id']);
    if (!$visit) {
        return redirect()->back()->with('error', 'Visit not found');
    }

    // ✅ Fetch doctor name
    $doctor = $doctorModel
        ->select('doctors.*, users.name as doctor_name')
        ->join('users', 'users.id = doctors.user_id')
        ->where('doctors.id', $visit['doctor_id'])
        ->first();

    // ✅ Fetch patient name
    $patient = $patientModel
        ->select('patients.*, users.name as patient_name')
        ->join('users', 'users.id = patients.user_id')
        ->where('patients.id', $visit['patient_id'])
        ->first();

    if (!$doctor || !$patient) {
        return redirect()->back()->with('error', 'Doctor or Patient info not found.');
    }

    // ✅ Create clean PDF HTML
    $html = "
        <style>
            body { font-family: DejaVu Sans, sans-serif; }
            h2 { text-align: center; color: #333; }
            .details { margin-bottom: 20px; }
            .details p { margin: 2px 0; }
            .prescription { border-top: 1px solid #ccc; padding-top: 10px; }
        </style>
        <h2>Prescription Report</h2>
        <div class='details'>
            <p><strong>Patient Name:</strong> " . esc($patient['patient_name']) . "</p>
            <p><strong>Doctor Name:</strong> " . esc($doctor['doctor_name']) . "</p>
            <p><strong>Appointment ID:</strong> " . esc($visit['appointment_id']) . "</p>
        </div>
        <div class='prescription'>
            <h4>Prescription Details:</h4>
            <p>" . nl2br(esc($prescription['prescription_text'])) . "</p>
        </div>
    ";

    $dompdf = new \Dompdf\Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream('Prescription.pdf', ["Attachment" => 1]);
}

public function bookAppointment()
    {
        $user_id = $this->session->get('user_id');
        if (!$user_id) return redirect()->to('/login')->with('error', 'Please login first.');

        $hospitals = $this->hospitalModel->findAll();
        $doctors = $this->doctorModel
            ->select('doctors.id, users.name as doctor_name, doctors.hospital_id, doctors.expertise, hospitals.name as hospital_name')
            ->join('users', 'users.id = doctors.user_id')
            ->join('hospitals', 'hospitals.id = doctors.hospital_id')
            ->findAll();

        return view('patient/book_appointment', [
            'hospitals' => $hospitals,
            'doctors' => $doctors
        ]);
    }


   public function saveAppointment()
    {
        $user_id = $this->session->get('user_id');
        if (!$user_id) return redirect()->to('/login')->with('error', 'Please login first.');

        $hospital_id = $this->request->getPost('hospital_id');
        $doctor_id = $this->request->getPost('doctor_id');
        $appointment_date = $this->request->getPost('appointment_date');
        $appointment_time = $this->request->getPost('appointment_time');

        // Check if patient exists for this hospital
        $patient = $this->patientModel
            ->where('user_id', $user_id)
            ->where('hospital_id', $hospital_id)
            ->first();

        if (!$patient) {
            // Auto-register patient for this hospital
            $user = $this->userModel->find($user_id);
            $patient_id = $this->patientModel->insert([
                'user_id' => $user_id,
                'hospital_id' => $hospital_id,
                'age' => $user['age'] ?? 0,
                'gender' => $user['gender'] ?? 'other',
                'created_by' => $user_id,
                'updated_by' => $user_id,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ], true);
        } else {
            $patient_id = $patient['id'];
        }

        $start_datetime = $appointment_date . ' ' . $appointment_time;
        $this->appointmentModel->insert([
            'doctor_id' => $doctor_id,
            'patient_id' => $patient_id,
            'start_datetime' => $start_datetime,
            'end_datetime' => date('Y-m-d H:i:s', strtotime($start_datetime . ' +30 minutes')),
            'status' => 'pending',
            'created_by' => $user_id,
            'updated_by' => $user_id,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->to('patient/dashboard')->with('success', 'Appointment booked successfully.');
    }



}
