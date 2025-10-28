<?php

namespace App\Controllers;

use App\Models\AppointmentModel;
use App\Models\BillServiceModel;
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
    protected $billServicesModel;

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
        $this->billServicesModel=new BillServiceModel();
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



        // Appointment counts
        $totalUpcoming = $this->appointmentModel
            ->where('patient_id', $patient['id'])
            ->where('status', 'pending')
            ->countAllResults();

        $totalCompleted = $this->appointmentModel
            ->where('patient_id', $patient['id'])
            ->where('status', 'completed')
            ->countAllResults();






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


        $hospital = null;
        if ($patient && $patient['hospital_id']) {
            $hospital = $this->hospitalModel->find($patient['hospital_id']);
        }

        return view('patient/dashboard', [
            'patient' => $patient,
            'visits' => $visits,
            'hospital' => $hospital,
            'totalUpcoming' => $totalUpcoming,
            'totalCompleted' => $totalCompleted
        ]);
    }


// Show all pending appointments (upcoming)
public function upcomingAppointments()
{
    $session = session();
    $userId = $session->get('user_id'); // logged-in user's id

    if (!$userId) {
        return redirect()->to('login')->with('error', 'Please log in to continue.');
    }

    $appointmentModel = new \App\Models\AppointmentModel();

    // Join with patients and users (doctors)
    $appointments = $appointmentModel
        ->select('appointments.*, doctors.name as doctor_name')
        ->join('patients', 'patients.id = appointments.patient_id', 'left')
        ->join('users as doctors', 'doctors.id = appointments.doctor_id', 'left')
        ->where('patients.user_id', $userId)
        ->whereIn('appointments.status', ['pending']) // upcoming = pending
        ->orderBy('appointments.start_datetime', 'ASC')
        ->findAll();

    return view('patient/upcoming_appointments', ['appointments' => $appointments]);
}

public function cancelAppointment($appointmentId)
{
    $session = session();
    $userId = $session->get('user_id');

    if (!$userId) {
        return redirect()->to('login')->with('error', 'Please log in first.');
    }

    $appointmentModel = new \App\Models\AppointmentModel();

    // Verify that this appointment belongs to the logged-in patient
    $appointment = $appointmentModel
        ->join('patients', 'patients.id = appointments.patient_id', 'left')
        ->where('patients.user_id', $userId)
        ->where('appointments.id', $appointmentId)
        ->first();

    if (!$appointment) {
        return redirect()->to('patient/upcomingAppointments')->with('error', 'Appointment not found or access denied.');
    }

    // Update status to cancelled
    $appointmentModel->update($appointmentId, ['status' => 'cancelled']);

    return redirect()->to('patient/upcomingAppointments')->with('success', 'Appointment cancelled successfully.');
}



// Reschedule form
public function rescheduleAppointment($id)
{
    $appointmentModel = new \App\Models\AppointmentModel();
    $appointment = $appointmentModel->find($id);

    if (!$appointment) {
        return redirect()->back()->with('error', 'Appointment not found');
    }

    return view('patient/reschedule_form', ['appointment' => $appointment]);
}

// Update new date/time
public function updateReschedule($id)
{
    $appointmentModel = new \App\Models\AppointmentModel();

    $data = [
        'start_datetime' => $this->request->getPost('start_datetime'),
        'end_datetime'   => $this->request->getPost('end_datetime'),
        'status'         => 'pending' // stays pending
    ];

    $appointmentModel->update($id, $data);

    return redirect()->to('patient/upcomingAppointments')->with('success', 'Appointment rescheduled successfully.');
}




public function completedAppointments()
{
    $session = session();
    $userId = $session->get('user_id');

    if (!$userId) {
        return redirect()->to('login')->with('error', 'Please log in first.');
    }

    $appointmentModel = new \App\Models\AppointmentModel();

    // Fetch only completed appointments for this patient
    $appointments = $appointmentModel
        ->select('appointments.*, doctors.id AS doctor_id, users.name AS doctor_name, prescriptions.id AS prescription_id, bills.id AS bill_id')
        ->join('patients', 'patients.id = appointments.patient_id', 'left')
        ->join('doctors', 'doctors.id = appointments.doctor_id', 'left')
        ->join('users', 'users.id = doctors.user_id', 'left')
        ->join('visit_history vh', 'vh.appointment_id = appointments.id', 'left')
        ->join('prescriptions', 'prescriptions.visit_id = vh.id', 'left')
        ->join('bills', 'bills.appointment_id = appointments.id', 'left')
        ->where('patients.user_id', $userId)
        ->where('appointments.status', 'completed')
        ->orderBy('appointments.start_datetime', 'DESC')
        ->findAll();

    return view('patient/completed_appointments', ['appointments' => $appointments]);
}



   public function viewBill($bill_id)
{
    $billModel = new \App\Models\BillModel();
    $visitModel = new \App\Models\VisitHistoryModel();
    $doctorModel = new \App\Models\DoctorModel();
    $patientModel = new \App\Models\PatientModel();
    $billServicesModel = new \App\Models\BillServiceModel();

    // Fetch main bill record
    $bill = $billModel->find($bill_id);
    if (!$bill) {
        return redirect()->back()->with('error', 'Bill not found.');
    }

    // Fetch associated services for that bill
    $billServices = $billServicesModel
        ->where('bill_id', $bill_id)
        ->findAll();

    // Fetch visit details
    $visit = $visitModel->find($bill['visit_id']);
    if (!$visit) {
        return redirect()->back()->with('error', 'Visit not found.');
    }

    // Fetch doctor name
    $doctor = $doctorModel
        ->select('doctors.*, users.name as doctor_name')
        ->join('users', 'users.id = doctors.user_id')
        ->where('doctors.id', $visit['doctor_id'])
        ->first();

    // Fetch patient name
    $patient = $patientModel
        ->select('patients.*, users.name as patient_name')
        ->join('users', 'users.id = patients.user_id')
        ->where('patients.id', $visit['patient_id'])
        ->first();

    return view('patient/view_bill', [
        'bill' => $bill,
        'visit' => $visit,
        'doctor' => $doctor,
        'patient' => $patient,
        'billServices' => $billServices
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

        // ✅ Step 1: Fetch logged-in user details
        $mainUser = $this->userModel->find($user_id);
        if (!$mainUser) {
            return redirect()->back()->with('error', 'User not found.');
        }

        // ✅ Step 2: Check if user already exists in this hospital
        $existingUser = $this->userModel
            ->where('email', $mainUser['email'])
            ->where('hospital_id', $hospital_id)
            ->first();

        // ✅ Step 3: Duplicate email protection within the same hospital
        if ($existingUser && $existingUser['id'] !== $user_id) {
            // Prevent duplicate entry for same hospital
            $newUserId = $existingUser['id'];
        } else {
            // ✅ If not exists, create new user entry for that hospital
            if (!$existingUser) {
                $newUserId = $this->userModel->insert([
                    'name' => $mainUser['name'],
                    'email' => $mainUser['email'],
                    'password' => $mainUser['password'], // hashed already
                    'role' => 'patient',
                    'hospital_id' => $hospital_id,
                    'gender' => $mainUser['gender'] ?? 'other',
                    'age' => $mainUser['age'] ?? 0,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ], true);
            } else {
                $newUserId = $existingUser['id'];
            }
        }

        // ✅ Step 4: Check or create patient record for that hospital
        $patient = $this->patientModel
            ->where('user_id', $newUserId)
            ->where('hospital_id', $hospital_id)
            ->first();

        if (!$patient) {
            $patient_id = $this->patientModel->insert([
                'user_id' => $newUserId,
                'hospital_id' => $hospital_id,
                'age' => $mainUser['age'] ?? 0,
                'gender' => $mainUser['gender'] ?? 'other',
                'created_by' => $user_id,
                'updated_by' => $user_id,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ], true);
        } else {
            $patient_id = $patient['id'];
        }

        // ✅ Step 5: Book appointment
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

        // ✅ Step 6: Custom success message depending on case
        if (!$existingUser) {
            $hospital = $this->hospitalModel->find($hospital_id);
            $hospitalName = $hospital['name'] ?? 'the hospital';
            return redirect()
                ->to('patient/dashboard')
                ->with('success', "You’ve been registered to $hospitalName and your appointment has been booked successfully!");
        }

        return redirect()->to('patient/dashboard')->with('success', 'Appointment booked successfully.');
    }
}
