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

        $hospitalId = $session->get('hospital_id');
        $hospitalUserId = $session->get('hospital_user_id');

        if (!$hospitalId || !$hospitalUserId) {
            return redirect()->to('/login')->with('error', 'Hospital context missing in session.');
        }


        // ✅ Step 2: Fetch doctor record joined with users (to get doctor name)
        $doctor = $db->table('doctors d')
            ->select('d.*, u.name AS doctor_name, u.email AS doctor_email')
            ->join('hospital_users hu', 'd.user_hospital_id = hu.id')
            ->join('users u', 'hu.user_id = u.id')
            ->where('d.user_hospital_id', $hospitalUserId)
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

       // ✅ Step 5: Get only today's pending appointments
$todayStart = date('Y-m-d 00:00:00');
$todayEnd   = date('Y-m-d 23:59:59');

$upcomingAppointments = $db->table('appointments a')
    ->select('a.*, u.name AS patient_name')
    ->join('patients p', 'a.patient_id = p.id')
    ->join('hospital_users hu', 'p.user_hospital_id = hu.id')
    ->join('users u', 'hu.user_id = u.id')
    ->where('a.doctor_id', $doctor['id'])
    ->where('a.hospital_id', $hospitalId)
    ->where('a.status', 'pending') // ✅ Only show pending
    ->where('a.start_datetime >=', $todayStart)
    ->where('a.start_datetime <=', $todayEnd)
    ->orderBy('a.start_datetime', 'ASC')
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

       $hospitalId = $session->get('hospital_id');


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
    $db = \Config\Database::connect();

    if (!$session->get('isLoggedIn') || $session->get('role') !== 'doctor') {
        return redirect()->to('/login')->with('error', 'Access denied.');
    }

    $hospitalId = $session->get('hospital_id');
    $hospitalUserId = $session->get('hospital_user_id'); // not strictly needed here

    $name = $this->request->getPost('name');
    $email = $this->request->getPost('email');
    $plainPassword = $this->request->getPost('password'); // doctor-supplied password
    $age = $this->request->getPost('age');
    $gender = $this->request->getPost('gender');

    if (empty($name) || empty($email) || empty($plainPassword)) {
        return redirect()->back()->with('error', 'Name, email and password are required.');
    }

    $userModel = new \App\Models\UserModel();
    $hospitalUserModel = new \App\Models\HospitalUserModel();
    $patientModel = new \App\Models\PatientModel();

    $db->transStart();

    // 1) Check if user already exists by email
    $existingUser = $db->table('users')->where('email', $email)->get()->getRowArray();

    if ($existingUser) {
        // Reuse existing user id
        $user_id = $existingUser['id'];

        // Optionally update password (this changes password globally for that user)
        // If you DON'T want to overwrite global password, remove/update this block.
        $db->table('users')->where('id', $user_id)->update([
            'name' => $name, // you can decide to update name or keep original
            'password' => password_hash($plainPassword, PASSWORD_DEFAULT),
            'role' => 'patient',
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    } else {
        // Create a new user
        $db->table('users')->insert([
            'name' => $name,
            'email' => $email,
            'password' => password_hash($plainPassword, PASSWORD_DEFAULT),
            'role' => 'patient',
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        $user_id = $db->insertID();
    }

    // 2) Ensure hospital_users mapping doesn't already exist
    $existingHU = $db->table('hospital_users')
        ->where([
            'user_id' => $user_id,
            'hospital_id' => $hospitalId,
            'role' => 'patient'
        ])->get()->getRowArray();

    if (!$existingHU) {
        $db->table('hospital_users')->insert([
            'user_id' => $user_id,
            'hospital_id' => $hospitalId,
            'role' => 'patient',
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        $hospital_user_id = $db->insertID();
    } else {
        // reuse existing mapping
        $hospital_user_id = $existingHU['id'];
    }

    // 3) Ensure patients table has an entry for this hospital_user
    $existingPatient = $db->table('patients')
        ->where('user_hospital_id', $hospital_user_id)
        ->get()->getRowArray();

    if (!$existingPatient) {
        $db->table('patients')->insert([
            'user_hospital_id' => $hospital_user_id,
            'age' => $age,
            'gender' => $gender,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    } else {
        // update details if you want
        $db->table('patients')->where('id', $existingPatient['id'])->update([
            'age' => $age,
            'gender' => $gender,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    $db->transComplete();

    if ($db->transStatus() === FALSE) {
        return redirect()->back()->with('error', 'Failed to add patient.');
    }

    return redirect()->to('doctor/patients')->with('success', 'Patient added/updated successfully.');
}


        // ✏️ Edit Patient
    public function editPatient($id)
    {
        $db = \Config\Database::connect();

        $patient = $db->table('patients p')
            ->select('p.id, u.name, u.email, p.age, p.gender')
            ->join('hospital_users hu', 'p.user_hospital_id = hu.id')
            ->join('users u', 'hu.user_id = u.id')
            ->where('p.id', $id)
            ->get()->getRowArray();

        if (!$patient) {
            return redirect()->to('doctor/patients')->with('error', 'Patient not found.');
        }

        return view('doctor/edit_patient', ['patient' => $patient]);
    }

   // 🔁 Update Patient
    public function updatePatient($id)
    {
        $db = \Config\Database::connect();
        $userModel = new UserModel();
        $patientModel = new PatientModel();

        $name = $this->request->getPost('name');
        $email = $this->request->getPost('email');
        $age = $this->request->getPost('age');
        $gender = $this->request->getPost('gender');

        // find user_id via join
        $patient = $db->table('patients p')
            ->join('hospital_users hu', 'p.user_hospital_id = hu.id')
            ->join('users u', 'hu.user_id = u.id')
            ->where('p.id', $id)
            ->select('u.id as user_id')
            ->get()->getRowArray();

        if ($patient) {
            $userModel->update($patient['user_id'], [
                'name' => $name,
                'email' => $email,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            $patientModel->update($id, [
                'age' => $age,
                'gender' => $gender,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }

        return redirect()->to('doctor/patients')->with('success', 'Patient updated successfully.');
    }

        // ❌ Delete Patient
    public function deletePatient($id)
    {
        $db = \Config\Database::connect();

        $patient = $db->table('patients p')
            ->join('hospital_users hu', 'p.user_hospital_id = hu.id')
            ->join('users u', 'hu.user_id = u.id')
            ->where('p.id', $id)
            ->select('p.id as patient_id, hu.id as hospital_user_id, u.id as user_id')
            ->get()->getRowArray();

        if ($patient) {
            $db->table('patients')->delete(['id' => $patient['patient_id']]);
            $db->table('hospital_users')->delete(['id' => $patient['hospital_user_id']]);
            $db->table('users')->delete(['id' => $patient['user_id']]);
        }

        return redirect()->to('doctor/patients')->with('success', 'Patient deleted successfully.');
    }



   
   
public function appointments()
{
    $session = session();
    $db = \Config\Database::connect();

    if (!$session->get('isLoggedIn') || $session->get('role') !== 'doctor') {
        return redirect()->to('/login')->with('error', 'Access denied.');
    }

   $hospitalId = $session->get('hospital_id');
$hospitalUserId = $session->get('hospital_user_id');

$doctor = $db->table('doctors')
    ->select('id')
    ->where('user_hospital_id', $hospitalUserId)
    ->get()
    ->getRowArray();


    if (!$doctor) {
        return redirect()->back()->with('error', 'Doctor record not found.');
    }

    $doctorId = $doctor['id'];

    // ✅ Step 2: Fetch appointments joined with related tables
    $appointments = $db->table('appointments a')
        ->select('a.*, u.name AS patient_name, 
                  vh.id AS visit_id, 
                  pr.id AS prescription_id, 
                  b.id AS bill_id')
        ->join('patients p', 'a.patient_id = p.id')
        ->join('hospital_users hu', 'p.user_hospital_id = hu.id')
        ->join('users u', 'hu.user_id = u.id')
        ->join('visit_history vh', 'vh.appointment_id = a.id', 'left')
        ->join('prescriptions pr', 'pr.visit_id = vh.id', 'left')
        ->join('bills b', 'b.appointment_id = a.id', 'left')
        ->where('a.doctor_id', $doctorId)
        ->where('a.hospital_id', $hospitalId)
        ->orderBy('a.start_datetime', 'DESC')
        ->get()
        ->getResultArray();

    return view('doctor/appointments', [
        'appointments' => $appointments,
        'title' => 'Appointments'
    ]);
}




   


    public function addAppointment()
    {
        $patientModel = new PatientModel();
        $hospitalId = session()->get('hospital_id');

        $data['patients'] = $patientModel
            ->select('patients.id, users.name as patient_name')
            ->join('hospital_users', 'hospital_users.id = patients.user_hospital_id')
            ->join('users', 'users.id = hospital_users.user_id')
            ->where('hospital_users.hospital_id', $hospitalId)
            ->findAll();

        return view('doctor/add_appointment', $data);
    }

   public function saveAppointment()
{
    $session = session();
    $db = \Config\Database::connect();

    if (!$session->get('isLoggedIn') || $session->get('role') !== 'doctor') {
        return redirect()->to('/login')->with('error', 'Access denied.');
    }

    // ✅ Step 1: Get logged-in user and hospital
    $userId = $session->get('id');
    $hospitalId = $session->get('hospital_id');

    // ✅ Step 2: Find the doctor_id from doctors table via hospital_users
   $doctor = $db->table('doctors')
    ->select('id')
    ->where('user_hospital_id', $session->get('hospital_user_id'))
    ->get()
    ->getRowArray();


    if (!$doctor) {
        return redirect()->back()->with('error', 'Doctor record not found.');
    }

    $doctorId = $doctor['id'];

    // ✅ Step 3: Get form data
    $patientId = $this->request->getPost('patient_id');
    $startDatetime = $this->request->getPost('start_datetime');
    $endDatetime = $this->request->getPost('end_datetime');

    if (!$patientId || !$startDatetime || !$endDatetime) {
        return redirect()->back()->with('error', 'All fields are required.');
    }

    // ✅ Step 4: Insert into appointments table
    $appointmentModel = new \App\Models\AppointmentModel();

    $appointmentModel->insert([
        'hospital_id'    => $hospitalId,
        'doctor_id'      => $doctorId,
        'patient_id'     => $patientId,
        'start_datetime' => $startDatetime,
        'end_datetime'   => $endDatetime,
        'status'         => 'pending',
        'created_by'     => $userId,
        'created_at'     => date('Y-m-d H:i:s')
    ]);

    // ✅ Step 5: Redirect back with success
    return redirect()->to('doctor/appointments')->with('success', 'Appointment created successfully.');
}


    public function cancelAppointment($id)
{
    $db = \Config\Database::connect();
    $db->table('appointments')->where('id', $id)->update(['status' => 'cancelled']);
    return redirect()->to('doctor/appointments')->with('success', 'Appointment cancelled successfully.');
}



public function rescheduleAppointment($id)
{
    $db = \Config\Database::connect();

    $appointment = $db->table('appointments a')
        ->select('a.*, u.name AS patient_name')
        ->join('patients p', 'p.id = a.patient_id')
        ->join('hospital_users hu', 'hu.id = p.user_hospital_id')
        ->join('users u', 'u.id = hu.user_id')
        ->where('a.id', $id)
        ->get()
        ->getRowArray();

    if (!$appointment) {
        return redirect()->to('doctor/appointments')->with('error', 'Appointment not found');
    }

    return view('doctor/reschedule_appointment', ['appointment' => $appointment]);
}

public function updateAppointment($id)
{
    $db = \Config\Database::connect();
    $builder = $db->table('appointments');

    $data = [
        'start_datetime' => $this->request->getPost('start_datetime'),
        'end_datetime'   => $this->request->getPost('end_datetime'),
        'updated_at'     => date('Y-m-d H:i:s'),
    ];

    $builder->where('id', $id)->update($data);

    return redirect()->to('doctor/appointments')->with('success', 'Appointment rescheduled successfully.');
}




public function markAsDone($id)
{
    $db = \Config\Database::connect();

    // Get appointment details
    $appointment = $db->table('appointments')->where('id', $id)->get()->getRowArray();
    if (!$appointment) {
        return redirect()->to('doctor/appointments')->with('error', 'Appointment not found.');
    }

    // Update appointment status to completed
    $db->table('appointments')->where('id', $id)->update([
        'status' => 'completed',
        'updated_at' => date('Y-m-d H:i:s')
    ]);

    // Redirect to visit details form
    return redirect()->to('doctor/addVisit/' . $id);
}

// Show the visit form
public function addVisit($appointment_id)
{
    $db = \Config\Database::connect();
    $appointment = $db->table('appointments')->where('id', $appointment_id)->get()->getRowArray();

    if (!$appointment) {
        return redirect()->to('doctor/appointments')->with('error', 'Appointment not found.');
    }

    $data['appointment'] = $appointment;
    return view('doctor/add_visit', $data);
}

// Save visit details
public function saveVisit()
{
    $appointmentId = $this->request->getPost('appointment_id');
    $patientId     = $this->request->getPost('patient_id');
    $doctorId      = $this->request->getPost('doctor_id');

    // Get multiple complaints and diagnoses
    $complaints       = $this->request->getPost('complaints');
    $otherComplaints  = $this->request->getPost('other_complaints');
    $diagnosis        = $this->request->getPost('diagnosis');
    $otherDiagnosis   = $this->request->getPost('other_diagnosis');

    $structuredData = [];

    // Combine complaint and diagnosis into pairs
    foreach ($complaints as $index => $complaint) {
        $actualComplaint = ($complaint === "Other" && !empty($otherComplaints[$index]))
            ? $otherComplaints[$index]
            : $complaint;

        $actualDiagnosis = ($diagnosis[$index] === "Other" && !empty($otherDiagnosis[$index]))
            ? $otherDiagnosis[$index]
            : $diagnosis[$index];

        $structuredData[] = [
            'complaint' => $actualComplaint,
            'diagnosis' => $actualDiagnosis
        ];
    }

    // Prepare data for insert
    $data = [
        'appointment_id'  => $appointmentId,
        'patient_id'      => $patientId,
        'doctor_id'       => $doctorId,
        'complaints'      => json_encode($structuredData),  // JSON data
        'diagnosis'       => json_encode($structuredData),  // optional duplicate for easy query
        'weight'          => $this->request->getPost('weight'),
        'blood_pressure'  => $this->request->getPost('blood_pressure'),
        'doctor_comments' => $this->request->getPost('doctor_comments'),
        'created_at'      => date('Y-m-d H:i:s')
    ];

    $visitModel = new \App\Models\VisitHistoryModel();
    $visitModel->insert($data);

    // Update appointment status to completed
    $appointmentModel = new \App\Models\AppointmentModel();
    $appointmentModel->update($appointmentId, ['status' => 'completed']);

    return redirect()
        ->to('doctor/appointments')
        ->with('success', 'Visit details saved and appointment marked as completed.');
}


public function viewVisit($appointmentId)
{
    $visitModel = new \App\Models\VisitHistoryModel();

    $visit = $visitModel
        ->select('visit_history.*, 
                  up.name as patient_name, 
                  ud.name as doctor_name')
        ->join('patients', 'patients.id = visit_history.patient_id')
        ->join('hospital_users hp', 'hp.id = patients.user_hospital_id')
        ->join('users up', 'up.id = hp.user_id') // Patient user details
        ->join('doctors', 'doctors.id = visit_history.doctor_id')
        ->join('hospital_users hd', 'hd.id = doctors.user_hospital_id')
        ->join('users ud', 'ud.id = hd.user_id') // Doctor user details
        ->where('visit_history.appointment_id', $appointmentId)
        ->first();

    if (!$visit) {
        return redirect()->to('doctor/appointments')->with('error', 'Visit details not found.');
    }

    return view('doctor/view_visit', ['visit' => $visit]);
}





public function addPrescription($visit_id)
{
    $visitModel = new \App\Models\VisitHistoryModel();
    $visit = $visitModel->find($visit_id);

    if (!$visit) {
        return redirect()->back()->with('error', 'Visit not found.');
    }

    return view('doctor/add_prescription', ['visit' => $visit]);
}

public function savePrescription()
{
    $prescriptionModel = new \App\Models\PrescriptionModel();
    $prescriptionItemModel = new \App\Models\PrescriptionItemModel();

    $data = [
        'visit_id'   => $this->request->getPost('visit_id'),
        'doctor_id'  => $this->request->getPost('doctor_id'),
        'patient_id' => $this->request->getPost('patient_id'),
        'notes'      => $this->request->getPost('notes'),
        'created_at' => date('Y-m-d H:i:s'),
    ];

    // Save main prescription
    $prescriptionModel->insert($data);
    $prescription_id = $prescriptionModel->getInsertID();

    // Save each medicine
    $medicines = $this->request->getPost('medicine_name');
    $dosages = $this->request->getPost('dosage');
    $frequencies = $this->request->getPost('frequency');
    $durations = $this->request->getPost('duration');
    $instructions = $this->request->getPost('usage_instruction');
    $diagnoses = $this->request->getPost('related_diagnosis');

    if ($medicines && is_array($medicines)) {
        foreach ($medicines as $i => $medicine) {
            if (trim($medicine) == '') continue;

            $prescriptionItemModel->insert([
                'prescription_id'   => $prescription_id,
                'medicine_name'     => $medicine,
                'dosage'            => $dosages[$i] ?? null,
                'frequency'         => $frequencies[$i] ?? null,
                'duration'          => $durations[$i] ?? null,
                'usage_instruction' => $instructions[$i] ?? null,
                'related_diagnosis' => $diagnoses[$i] ?? null,
                'created_at'        => date('Y-m-d H:i:s'),
            ]);
        }
    }

    return redirect()->to('doctor/viewPrescription/' . $data['visit_id'])
        ->with('success', 'Prescription saved successfully.');
}

public function viewPrescription($visit_id)
{
    $db = \Config\Database::connect();

    // ✅ Fetch main prescription
    $prescription = $db->table('prescriptions')
        ->where('visit_id', $visit_id)
        ->get()
        ->getRowArray();

    if (!$prescription) {
        return redirect()->back()->with('error', 'Prescription not found for this visit.');
    }

    // ✅ Fetch items (medicines)
    $items = $db->table('prescription_items')
        ->where('prescription_id', $prescription['id'])
        ->get()
        ->getResultArray();

    // ✅ Fetch visit details (for appointment, date, etc.)
    $visit = $db->table('visit_history')
        ->where('id', $visit_id)
        ->get()
        ->getRowArray();

    // ✅ Fetch patient details linked to this visit
    $patient = $db->table('patients p')
        ->select('u.name AS patient_name, u.email')
        ->join('hospital_users hu', 'p.user_hospital_id = hu.id')
        ->join('users u', 'hu.user_id = u.id')
        ->where('p.id', $visit['patient_id'])
        ->get()
        ->getRowArray();

    // ✅ Pass everything to the view
    return view('doctor/view_prescription', [
        'prescription' => $prescription,
        'items'        => $items,
        'visit'        => $visit,
        'patient'      => $patient
    ]);
}




public function addBill($appointmentId)
{
    $db = \Config\Database::connect();

    // ✅ Get appointment details (with patient info)
    $appointment = $db->table('appointments a')
        ->select('a.*, p.id as patient_id, d.id as doctor_id, hu.hospital_id')
        ->join('patients p', 'a.patient_id = p.id')
        ->join('doctors d', 'a.doctor_id = d.id')
        ->join('hospital_users hu', 'd.user_hospital_id = hu.id')
        ->where('a.id', $appointmentId)
        ->get()->getRowArray();

    if (!$appointment) {
        return redirect()->back()->with('error', 'Appointment not found.');
    }

    // ✅ Fetch doctor services with fallback hospital price
    $services = $db->table('doctor_services ds')
        ->select('ds.id as doctor_service_id, s.name as service_name, 
                  COALESCE(ds.custom_fee, hs.price) as price')
        ->join('services s', 's.id = ds.service_id')
        ->join('hospital_services hs', 'hs.service_id = s.id AND hs.hospital_id = ds.hospital_id')
        ->where('ds.doctor_id', $appointment['doctor_id'])
        ->where('ds.hospital_id', $appointment['hospital_id'])
        ->get()->getResultArray();

    return view('doctor/add_bill', [
        'appointment' => $appointment,
        'services' => $services
    ]);
}


public function saveBill()
{
    $db = \Config\Database::connect();
    $post = $this->request->getPost();

    // Extract form data
    $appointmentId = $post['appointment_id'];
    $patientId = $post['patient_id'];
    $doctorId = $post['doctor_id'];
    $serviceIds = $post['service_id'];
    $quantities = $post['quantity'];

    // ✅ 1. Fetch doctor’s hospital
    $doctor = $db->table('doctors d')
        ->select('hu.hospital_id')
        ->join('hospital_users hu', 'hu.id = d.user_hospital_id')
        ->where('d.id', $doctorId)
        ->get()->getRowArray();

    if (!$doctor) {
        return redirect()->back()->with('error', 'Doctor record not found.');
    }

    $hospitalId = $doctor['hospital_id'];

    // ✅ 2. Find Visit for this Appointment
    $visit = $db->table('visit_history')
        ->select('id')
        ->where('appointment_id', $appointmentId)
        ->get()->getRowArray();

    if (!$visit) {
        return redirect()->back()->with('error', 'No visit record found for this appointment.');
    }

    $visitId = $visit['id'];

    $db->transStart();

    // ✅ 3. Insert into bills table
    $bill = [
        'appointment_id'  => $appointmentId,
        'visit_id'        => $visitId,
        'patient_id'      => $patientId,
        'doctor_id'       => $doctorId,
        'consultation_fee'=> 0,
        'total_amount'    => 0,
        'payment_status'  => 'Pending',
        'created_at'      => date('Y-m-d H:i:s')
    ];

    $db->table('bills')->insert($bill);
    $billId = $db->insertID();

    $total = 0;

    // ✅ 4. Insert selected services into bill_services
    foreach ($serviceIds as $i => $dsId) {
        $qty = (int)($quantities[$i] ?? 1);

        $service = $db->table('doctor_services ds')
            ->select('s.name, hs.id as hospital_service_id, COALESCE(ds.custom_fee, hs.price) as price')
            ->join('services s', 's.id = ds.service_id')
            ->join('hospital_services hs', 'hs.service_id = s.id AND hs.hospital_id = ds.hospital_id')
            ->where('ds.id', $dsId)
            ->get()->getRowArray();

        if ($service) {
            $subtotal = $service['price'] * $qty;
            $total += $subtotal;

            $db->table('bill_services')->insert([
                'bill_id' => $billId,
                'hospital_service_id' => $service['hospital_service_id'],
                'service_name' => $service['name'],
                'price' => $service['price'],
                'quantity' => $qty,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }
    }

    // ✅ 5. Update total amount in bills table
    $db->table('bills')->where('id', $billId)->update(['total_amount' => $total]);
    $db->transComplete();

    if (!$db->transStatus()) {
        return redirect()->back()->with('error', 'Bill creation failed.');
    }

    // ✅ 6. Redirect to viewBill
    return redirect()->to('doctor/viewBill/' . $billId)->with('success', 'Bill created successfully!');
}



public function viewBill($billId)
{
    $db = \Config\Database::connect();

    $bill = $db->table('bills b')
        ->select('b.*, u.name AS patient_name, u.email AS patient_email')
        ->join('patients p', 'b.patient_id = p.id')
        ->join('hospital_users hu', 'hu.id = p.user_hospital_id')
        ->join('users u', 'u.id = hu.user_id')
        ->where('b.id', $billId)
        ->get()->getRowArray();

    if (!$bill) {
        return redirect()->back()->with('error', 'Bill not found.');
    }

    $billServices = $db->table('bill_services')
        ->where('bill_id', $billId)
        ->get()->getResultArray();

    return view('doctor/view_bill', [
        'bill' => $bill,
        'billServices' => $billServices
    ]);
}




// 📋 List all services offered by doctor
public function services()
{
    $session = session();
    $db = \Config\Database::connect();
    $userId = $session->get('id');
    $hospitalId = $session->get('hospital_id');

   $doctor = $db->table('doctors')
    ->select('id')
    ->where('user_hospital_id', $session->get('hospital_user_id'))
    ->get()
    ->getRowArray();


    if (!$doctor) {
        return redirect()->to('/login')->with('error', 'Doctor not found.');
    }

    // Fetch doctor’s services with hospital fallback
    $services = $db->table('doctor_services ds')
        ->select('ds.id, s.name as service_name, COALESCE(ds.custom_fee, hs.price) as price, hs.price as hospital_price')
        ->join('services s', 's.id = ds.service_id')
        ->join('hospital_services hs', 'hs.service_id = s.id AND hs.hospital_id = ds.hospital_id')
        ->where('ds.doctor_id', $doctor['id'])
        ->where('ds.hospital_id', $hospitalId)
        ->get()
        ->getResultArray();

    return view('doctor/services', ['services' => $services]);
}


// ➕ Add Service Form
public function addService()
{
    $session = session();
    $hospitalId = $session->get('hospital_id');

    $db = \Config\Database::connect();

    // Fetch hospital’s active services
    $hospitalServices = $db->table('hospital_services hs')
        ->select('hs.id as hospital_service_id, s.id as service_id, s.name, hs.price')
        ->join('services s', 's.id = hs.service_id')
        ->where('hs.hospital_id', $hospitalId)
        ->where('hs.status', 'active')
        ->get()->getResultArray();

    return view('doctor/add_service', ['hospitalServices' => $hospitalServices]);
}


// 💾 Save Service Mapping
public function saveService()
{
    $session = session();
    $db = \Config\Database::connect();
    $userId = $session->get('id');
    $hospitalId = $session->get('hospital_id');

    $doctor = $db->table('doctors')
    ->select('id')
    ->where('user_hospital_id', $session->get('hospital_user_id'))
    ->get()
    ->getRowArray();


    if (!$doctor) {
        return redirect()->back()->with('error', 'Doctor not found.');
    }

    $serviceId = $this->request->getPost('service_id');
    $customFee = $this->request->getPost('custom_fee');

    // Check if doctor already has this service
    $exists = $db->table('doctor_services')
        ->where('doctor_id', $doctor['id'])
        ->where('hospital_id', $hospitalId)
        ->where('service_id', $serviceId)
        ->get()->getRowArray();

    if ($exists) {
        return redirect()->back()->with('error', 'This service is already added.');
    }

    $db->table('doctor_services')->insert([
        'doctor_id' => $doctor['id'],
        'hospital_id' => $hospitalId,
        'service_id' => $serviceId,
        'custom_fee' => $customFee ?: null,
        'created_at' => date('Y-m-d H:i:s')
    ]);

    return redirect()->to('doctor/services')->with('success', 'Service added successfully.');
}


// ✏️ Edit Service
public function editService($id)
{
    $db = \Config\Database::connect();
    $service = $db->table('doctor_services ds')
        ->select('ds.*, s.name as service_name, hs.price as hospital_price')
        ->join('services s', 's.id = ds.service_id')
        ->join('hospital_services hs', 'hs.service_id = s.id AND hs.hospital_id = ds.hospital_id')
        ->where('ds.id', $id)
        ->get()->getRowArray();

    return view('doctor/edit_service', ['service' => $service]);
}


// 💾 Update Service
public function updateService($id)
{
    $db = \Config\Database::connect();

    $customFee = $this->request->getPost('custom_fee');
    $db->table('doctor_services')->where('id', $id)->update([
        'custom_fee' => $customFee ?: null,
        'updated_at' => date('Y-m-d H:i:s')
    ]);

    return redirect()->to('doctor/services')->with('success', 'Service updated successfully.');
}


// ❌ Delete Service
public function deleteService($id)
{
    $db = \Config\Database::connect();
    $db->table('doctor_services')->delete(['id' => $id]);

    return redirect()->to('doctor/services')->with('success', 'Service removed.');
}


public function profile()
{
    $session = session();
    $db = \Config\Database::connect();

    // Ensure only logged-in doctors access
    if (!$session->get('isLoggedIn') || $session->get('role') !== 'doctor') {
        return redirect()->to('/login')->with('error', 'Access denied.');
    }

    $userId = $session->get('id');

    // Join users + hospital_users + doctors
    $doctor = $db->table('doctors d')
        ->select('u.name, u.email, d.age, d.gender, d.expertise, d.availability_type, h.name as hospital_name')
        ->join('hospital_users hu', 'hu.id = d.user_hospital_id')
        ->join('users u', 'u.id = hu.user_id')
        ->join('hospitals h', 'h.id = hu.hospital_id')
        ->where('hu.id', $session->get('hospital_user_id'))
        ->get()
        ->getRowArray();

    if (!$doctor) {
        return redirect()->to('doctor/dashboard')->with('error', 'Doctor profile not found.');
    }

    return view('doctor/profile', ['doctor' => $doctor]);
}

public function updateProfile()
{
    $db = \Config\Database::connect();
    $session = session();

    $userModel = new \App\Models\UserModel();
    $doctorModel = new \App\Models\DoctorModel();

    $userId = $session->get('id');

    // Get form input
    $name = $this->request->getPost('name');
    $email = $this->request->getPost('email');
    $age = $this->request->getPost('age');
    $gender = $this->request->getPost('gender');
    $expertise = $this->request->getPost('expertise');
    $availability = $this->request->getPost('availability_type');

    // Update users table
    $userModel->update($userId, [
        'name' => $name,
        'email' => $email,
        'updated_at' => date('Y-m-d H:i:s'),
    ]);

    // Find doctor's user_hospital_id
    $doctor = $db->table('doctors d')
        ->select('d.id')
        ->join('hospital_users hu', 'hu.id = d.user_hospital_id')
     ->where('hu.id', $session->get('hospital_user_id'))
        ->get()
        ->getRowArray();

    if ($doctor) {
        $doctorModel->update($doctor['id'], [
            'age' => $age,
            'gender' => $gender,
            'expertise' => $expertise,
            'availability_type' => $availability,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    return redirect()->to('doctor/profile')->with('success', 'Profile updated successfully.');
}


}

