<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\DoctorModel;
use App\Models\PatientModel;
use App\Models\AppointmentModel;
use App\Models\BillModel;
use App\Models\BillServiceModel;
use App\Models\VisitHistoryModel;
use App\Models\HospitalModel;
use App\Models\PrescriptionModel;
use App\Models\ServiceModel;

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
      protected $billModel;
    protected $serviceModel;
    protected $billServiceModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->doctorModel = new DoctorModel();
        $this->patientModel = new PatientModel();
        $this->appointmentModel = new AppointmentModel();
        $this->visitHistoryModel = new VisitHistoryModel();
        $this->hospitalModel = new HospitalModel();
        $this->prescriptionModel = new PrescriptionModel(); 
         $this->billModel = new BillModel();
        $this->serviceModel = new ServiceModel();
        $this->billServiceModel = new BillServiceModel();
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
    $session = session();
    $userId = $session->get('user_id');

    // ✅ Fetch doctor info using logged-in user ID
    $doctor = $this->doctorModel->where('user_id', $userId)->first();

    if (!$doctor) {
        return redirect()->to('login')->with('error', 'Doctor not found or not logged in properly.');
    }

    // ✅ Get filters from GET parameters
    $filter = $this->request->getGet('filter');
    $custom_date = $this->request->getGet('custom_date');

    // ✅ Build base query
    $builder = $this->appointmentModel
        ->select('appointments.*, 
                  u.name AS patient_name, 
                  p.id AS patient_id, 
                  pr.id AS prescription_id, 
                  b.id AS bill_id')
        ->join('patients p', 'p.id = appointments.patient_id', 'left')
        ->join('users u', 'u.id = p.user_id', 'left')
        ->join('visit_history vh', 'vh.appointment_id = appointments.id', 'left')
        ->join('prescriptions pr', 'pr.visit_id = vh.id', 'left')
        ->join('bills b', 'b.appointment_id = appointments.id', 'left')
        ->where('appointments.doctor_id', $doctor['id']);

    // ✅ Apply filter logic (does not affect old logic)
    if ($filter) {
        switch ($filter) {
            case 'today':
                $builder->where('DATE(appointments.start_datetime)', date('Y-m-d'));
                break;
            case 'yesterday':
                $builder->where('DATE(appointments.start_datetime)', date('Y-m-d', strtotime('-1 day')));
                break;
            case 'this_week':
                $builder->where('YEARWEEK(appointments.start_datetime, 1)', date('oW'));
                break;
            case 'this_month':
                $builder->where('MONTH(appointments.start_datetime)', date('m'))
                        ->where('YEAR(appointments.start_datetime)', date('Y'));
                break;
            case '3_months':
                $builder->where('appointments.start_datetime >=', date('Y-m-d', strtotime('-3 months')));
                break;
            case '6_months':
                $builder->where('appointments.start_datetime >=', date('Y-m-d', strtotime('-6 months')));
                break;
            case 'this_year':
                $builder->where('YEAR(appointments.start_datetime)', date('Y'));
                break;
            case 'custom':
                if (!empty($custom_date)) {
                    $builder->where('DATE(appointments.start_datetime)', date('Y-m-d', strtotime($custom_date)));
                }
                break;
        }
    }

    // ✅ Execute
    $appointments = $builder->orderBy('appointments.start_datetime', 'DESC')->findAll();

    // ✅ Pass filter state to view
    return view('doctor/appointments', [
        'appointments' => $appointments,
        'filter' => $filter,
        'custom_date' => $custom_date
    ]);
}



 public function addBill($appointment_id)
    {
        $appointment = $this->appointmentModel
            ->select('appointments.*, users.name as patient_name, users.id as user_id')
            ->join('patients', 'patients.id = appointments.patient_id', 'left')
            ->join('users', 'users.id = patients.user_id', 'left')
            ->where('appointments.id', $appointment_id)
            ->first();

        if (!$appointment) {
            return redirect()->back()->with('error', 'Appointment not found');
        }

        $services = $this->serviceModel->findAll();

        return view('doctor/add_bill', [
            'appointment' => $appointment,
            'services' => $services,
        ]);
    }

    /**
     * ✅ Save Bill with Selected Services
     */
    public function saveBill()
    {
        $appointment_id = $this->request->getPost('appointment_id');
        $consultation_fee = $this->request->getPost('consultation_fee');
        $selected_services = $this->request->getPost('services'); // array of service IDs
        $payment_status = $this->request->getPost('payment_status');
        $payment_mode = $this->request->getPost('payment_mode');

        $appointment = $this->appointmentModel->find($appointment_id);
        if (!$appointment) {
            return redirect()->back()->with('error', 'Appointment not found');
        }

        // Fetch related doctor and patient details
        $doctor_id = $appointment['doctor_id'];
        $patient_id = $appointment['patient_id'];
        $visit = $this->visitHistoryModel->where('appointment_id', $appointment_id)->first();
        $visit_id = $visit ? $visit['id'] : null;

        // Calculate total
        $total_amount = (float)$consultation_fee;

        $service_details = [];
        if (!empty($selected_services)) {
            $services = $this->serviceModel->whereIn('id', $selected_services)->findAll();
            foreach ($services as $service) {
                $total_amount += (float)$service['price'];
                $service_details[] = [
                    'service_id' => $service['id'],
                    'service_name' => $service['name'],
                    'price' => $service['price']
                ];
            }
        }

        // Insert Bill
        $billData = [
            'appointment_id' => $appointment_id,
            'visit_id' => $visit_id,
            'patient_id' => $patient_id,
            'doctor_id' => $doctor_id,
            'hospital_id' => $appointment['hospital_id'] ?? 1,
            'consultation_fee' => $consultation_fee,
            'total_amount' => $total_amount,
            'payment_status' => $payment_status,
            'payment_mode' => $payment_mode,
            'payment_date' => $payment_status === 'Paid' ? date('Y-m-d H:i:s') : null,
        ];

        $this->billModel->insert($billData);
        $bill_id = $this->billModel->getInsertID();

        // ✅ Store selected services in bill_services table
        if (!empty($service_details)) {
            foreach ($service_details as $sd) {
                $this->billServiceModel->insert([
                    'bill_id' => $bill_id,
                    'service_id' => $sd['service_id'],
                    'service_name' => $sd['service_name'],
                    'price' => $sd['price']
                ]);
            }
        }
        return redirect()->to('doctor/viewBill/' . $bill_id)->with('success', 'Bill added successfully');
    }

public function updatePaymentStatus($bill_id)
{
    $payment_status = $this->request->getPost('payment_status');
    $payment_mode   = $this->request->getPost('payment_mode');

    if (empty($payment_status) && empty($payment_mode)) {
        return redirect()->back()->with('error', 'No payment data received.');
    }

    $data = [
        'payment_status' => $payment_status,
        'payment_mode'   => $payment_mode,
        'payment_date'   => ($payment_status === 'Paid') ? date('Y-m-d H:i:s') : null,
        'updated_at'     => date('Y-m-d H:i:s'),
    ];

    // ✅ Update the bill table
    $this->billModel->update($bill_id, $data);



    return redirect()->to(site_url('doctor/viewBill/' . $bill_id))
        ->with('success', 'Payment status updated successfully.');
}


/**
 * ✅ Load Edit Bill Page
 */
public function editBill($bill_id)
{
    $bill = $this->billModel->find($bill_id);
    if (!$bill) {
        return redirect()->back()->with('error', 'Bill not found');
    }

    // Get selected services for this bill
    $selectedServices = $this->billServiceModel->where('bill_id', $bill_id)->findAll();
    $selectedServiceIds = array_column($selectedServices, 'service_id');

    // Fetch all available services
    $services = $this->serviceModel->findAll();

    return view('doctor/edit_bill', [
        'bill' => $bill,
        'services' => $services,
        'selectedServiceIds' => $selectedServiceIds
    ]);
}


/**
 * ✅ Handle Bill Update (services + payment)
 */
public function updateBill($bill_id)
{
    $bill = $this->billModel->find($bill_id);
    if (!$bill) {
        return redirect()->back()->with('error', 'Bill not found');
    }

    $consultation_fee = $this->request->getPost('consultation_fee');
    $selected_services = $this->request->getPost('services');
    $payment_status = $this->request->getPost('payment_status');
    $payment_mode = $this->request->getPost('payment_mode');

    // Calculate total
    $total_amount = (float)$consultation_fee;

    $service_details = [];
    if (!empty($selected_services)) {
        $services = $this->serviceModel->whereIn('id', $selected_services)->findAll();
        foreach ($services as $service) {
            $total_amount += (float)$service['price'];
            $service_details[] = [
                'service_id' => $service['id'],
                'service_name' => $service['name'],
                'price' => $service['price']
            ];
        }
    }

    // Update bill
    $this->billModel->update($bill_id, [
        'consultation_fee' => $consultation_fee,
        'total_amount' => $total_amount,
        'payment_status' => $payment_status,
        'payment_mode' => $payment_mode,
        'payment_date' => $payment_status === 'Paid' ? date('Y-m-d H:i:s') : null,
    ]);

    // Refresh bill_services table
    $this->billServiceModel->where('bill_id', $bill_id)->delete();
    foreach ($service_details as $sd) {
        $this->billServiceModel->insert([
            'bill_id' => $bill_id,
            'service_id' => $sd['service_id'],
            'service_name' => $sd['service_name'],
            'price' => $sd['price']
        ]);
    }

    return redirect()->to('doctor/viewBill/' . $bill_id)->with('success', 'Bill updated successfully');
}




    /**
     * ✅ View Bill Details
     */
    public function viewBill($bill_id)
    {
        $bill = $this->billModel
            ->select('bills.*, 
                      duser.name as doctor_name, 
                      puser.name as patient_name, 
                      appointments.start_datetime, 
                      appointments.end_datetime')
            ->join('appointments', 'appointments.id = bills.appointment_id', 'left')
            ->join('doctors', 'doctors.id = bills.doctor_id', 'left')
            ->join('users duser', 'duser.id = doctors.user_id', 'left')
            ->join('patients', 'patients.id = bills.patient_id', 'left')
            ->join('users puser', 'puser.id = patients.user_id', 'left')
            ->where('bills.id', $bill_id)
            ->first();

        if (!$bill) {
            return redirect()->back()->with('error', 'Bill not found');
        }

        // ✅ Fetch associated services from bill_services
        $bill_services = $this->billServiceModel->where('bill_id', $bill_id)->findAll();

        return view('doctor/view_bill', [
            'bill' => $bill,
            'bill_services' => $bill_services
        ]);
    }



    public function downloadBill($bill_id)
{
    $billModel = new \App\Models\BillModel();
    $appointmentModel = new \App\Models\AppointmentModel();
    $patientModel = new \App\Models\PatientModel();
    $userModel = new \App\Models\UserModel();
    $doctorModel = new \App\Models\DoctorModel();

    $bill = $billModel->find($bill_id);
    if (!$bill) {
        return redirect()->back()->with('error', 'Bill not found.');
    }

    $appointment = $appointmentModel->find($bill['appointment_id']);
    $patient = $patientModel
        ->select('patients.*, users.name AS patient_name, users.email AS patient_email')
        ->join('users', 'users.id = patients.user_id')
        ->where('patients.id', $appointment['patient_id'])
        ->first();

    $doctor = $doctorModel
        ->select('doctors.*, users.name AS doctor_name')
        ->join('users', 'users.id = doctors.user_id')
        ->where('doctors.id', $appointment['doctor_id'])
        ->first();

    // ✅ Generate PDF HTML
    $html = "
        <style>
            body { font-family: DejaVu Sans, sans-serif; }
            h2 { text-align: center; color: #333; }
            .details { margin-bottom: 20px; }
            .details p { margin: 2px 0; }
            table { width: 100%; border-collapse: collapse; margin-top: 10px; }
            th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
            th { background-color: #f2f2f2; }
        </style>
        <h2>Hospital Bill</h2>
        <div class='details'>
            <p><strong>Doctor:</strong> {$doctor['doctor_name']}</p>
            <p><strong>Patient:</strong> {$patient['patient_name']} ({$patient['patient_email']})</p>
            <p><strong>Bill Date:</strong> {$bill['created_at']}</p>
            <p><strong>Appointment ID:</strong> {$appointment['id']}</p>
        </div>

        <table>
            <tr><th>Amount</th><td>₹ {$bill['total_amount']}</td></tr>

        </table>
    ";

    // ✅ Use Dompdf to download
    $dompdf = new \Dompdf\Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    // Force download
    $dompdf->stream("Bill_{$bill_id}.pdf", ["Attachment" => true]);
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

public function viewAppointment($id)
{
    $appointmentModel = new \App\Models\AppointmentModel();
    $appointment = $appointmentModel
        ->select('
            appointments.*, 
            du.name AS doctor_name, 
            pu.name AS patient_name,
            vh.id AS visit_id,
            vh.reason, 
            vh.weight, 
            vh.blood_pressure, 
            vh.doctor_comments,
            b.id AS bill_id,
            b.total_amount, 
            b.payment_status, 
            b.payment_mode,
            pr.id AS prescription_id,
            pr.prescription_text
        ')
        ->join('doctors d', 'd.id = appointments.doctor_id', 'left')
        ->join('users du', 'du.id = d.user_id', 'left')
        ->join('patients p', 'p.id = appointments.patient_id', 'left')
        ->join('users pu', 'pu.id = p.user_id', 'left')
        ->join('visit_history vh', 'vh.appointment_id = appointments.id', 'left')
        ->join('bills b', 'b.appointment_id = appointments.id', 'left')
        ->join('prescriptions pr', 'pr.appointment_id = appointments.id', 'left')
        ->where('appointments.id', $id)
        ->first();

    if (!$appointment) {
        return redirect()->back()->with('error', 'Appointment not found.');
    }

    return view('doctor/view_appointment', [
        'appointment' => $appointment
    ]);
}




public function addPrescription($appointment_id)
{
    // Find visit by appointment_id
    $visit = $this->visitHistoryModel
        ->where('appointment_id', $appointment_id)
        ->first();

    if (!$visit) {
        return redirect()->back()->with('error', 'Visit not found for this appointment.');
    }

    $patient = $this->patientModel
        ->select('patients.*, users.name as patient_name, users.email')
        ->join('users', 'users.id = patients.user_id')
        ->where('patients.id', $visit['patient_id'])
        ->first();

    $appointment = $this->appointmentModel->find($appointment_id);

    return view('doctor/add_prescription', [
        'visit' => $visit,
        'patient' => $patient,
        'appointment' => $appointment
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



// ✅ Mark Appointment as Done
public function markDone($appointment_id)
{
    $appointment = $this->appointmentModel
        ->select('appointments.*, u.name AS patient_name')
        ->join('patients p', 'p.id = appointments.patient_id', 'left')
        ->join('users u', 'u.id = p.user_id', 'left')
        ->where('appointments.id', $appointment_id)
        ->first();

    if (!$appointment) {
        return redirect()->back()->with('error', 'Appointment not found.');
    }

    return view('doctor/add_visit', [
        'appointment' => $appointment
    ]);
}


public function saveVisit()
{
    $appointment_id = $this->request->getPost('appointment_id');
    $appointment = $this->appointmentModel->find($appointment_id);

    if (!$appointment) {
        return redirect()->back()->with('error', 'Appointment not found.');
    }

    $data = [
        'appointment_id' => $appointment_id,
        'patient_id' => $this->request->getPost('patient_id'),
        'doctor_id' => $this->request->getPost('doctor_id'),
        'reason' => $this->request->getPost('reason'),
        'weight' => $this->request->getPost('weight'),
        'blood_pressure' => $this->request->getPost('blood_pressure'),
        'doctor_comments' => $this->request->getPost('doctor_comments'),
        'created_at' => date('Y-m-d H:i:s')
    ];

    $this->visitHistoryModel->insert($data);

    // ✅ Mark appointment as completed
    $this->appointmentModel->update($appointment_id, [
        'status' => 'completed',
        'updated_at' => date('Y-m-d H:i:s')
    ]);

    return redirect()->to('doctor/appointments')->with('success', 'Visit history added and appointment marked as completed.');
}


// ❌ Cancel Appointment
public function cancelAppointment($appointment_id)
{
    $this->appointmentModel->update($appointment_id, [
        'status' => 'cancelled',
        'updated_at' => date('Y-m-d H:i:s')
    ]);
    return redirect()->back()->with('success', 'Appointment cancelled.');
}

// 🔁 Reschedule Appointment (redirect to addAppointment with data)
public function reschedule($appointment_id)
{
    $appointment = $this->appointmentModel->find($appointment_id);
    if (!$appointment) {
        return redirect()->back()->with('error', 'Appointment not found.');
    }
    return view('doctor/reschedule_appointment', ['appointment' => $appointment]);
}






 
}
