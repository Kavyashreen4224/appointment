<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\PatientModel;
use App\Models\VisitHistoryModel;
use App\Models\PrescriptionModel;
use App\Models\DoctorModel;
use CodeIgniter\Controller;

class PatientController extends Controller
{
    protected $session;
    protected $userModel;
    protected $patientModel;
    protected $visitModel;
    protected $prescriptionModel;
    protected $doctorModel;

    public function __construct()
    {
        $this->session = session();
        $this->userModel = new UserModel();
        $this->patientModel = new PatientModel();
        $this->visitModel = new VisitHistoryModel();
        $this->prescriptionModel = new PrescriptionModel();
        $this->doctorModel = new DoctorModel();
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
            ->select('visit_history.*, doctors.id as doctor_id, doctor_users.name as doctor_name, 
                      prescriptions.id as prescription_id, prescriptions.prescription_text')
            ->join('doctors', 'doctors.id = visit_history.doctor_id')
            ->join('users as doctor_users', 'doctor_users.id = doctors.user_id')
            ->join('prescriptions', 'prescriptions.visit_id = visit_history.id', 'left')
            ->where('visit_history.patient_id', $patient['id'])
        
            ->get()
            ->getResultArray();

        return view('patient/dashboard', [
            'patient' => $patient,
            'visits' => $visits
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

}
