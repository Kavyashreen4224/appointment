<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class PatientController extends Controller
{
public function dashboard()
{
    $session = session();
    $db = \Config\Database::connect();

    if (!$session->get('isLoggedIn') || $session->get('role') !== 'patient') {
        return redirect()->to('/login')->with('error', 'Access denied.');
    }

    $userId = $session->get('id');

   $hospitalId = $session->get('hospital_id');
    $hospitalUserId = $session->get('hospital_user_id');


    if (!$hospitalId) {
        return redirect()->to('/login')->with('error', 'Patient record not found.');
    }

    // ✅ Get patient record
   $patient = $db->table('patients p')
    ->join('hospital_users hu', 'p.user_hospital_id = hu.id')
    ->join('users u', 'hu.user_id = u.id')
    ->where('hu.id', $hospitalUserId)
    ->select('p.id AS patient_id, u.name AS patient_name, u.email, p.gender, p.age')
    ->get()
    ->getRowArray();


    // ✅ Get hospital info
    $hospital = $db->table('hospitals')->where('id', $hospitalId)->get()->getRowArray();

    // ✅ Appointment Stats
    $totalAppointments = $db->table('appointments')->where('patient_id', $patient['patient_id'])->countAllResults();
    $pendingAppointments = $db->table('appointments')->where('patient_id', $patient['patient_id'])->where('status', 'pending')->countAllResults();
    $completedAppointments = $db->table('appointments')->where('patient_id', $patient['patient_id'])->where('status', 'completed')->countAllResults();
    $cancelledAppointments = $db->table('appointments')->where('patient_id', $patient['patient_id'])->where('status', 'cancelled')->countAllResults(); // ✅ NEW

    // ✅ Upcoming Appointments
    $upcomingAppointments = $db->table('appointments a')
        ->select('a.*, u.name AS doctor_name')
        ->join('doctors d', 'a.doctor_id = d.id')
        ->join('hospital_users hu', 'd.user_hospital_id = hu.id')
        ->join('users u', 'hu.user_id = u.id')
        ->where('a.patient_id', $patient['patient_id'])
        ->where('a.status', 'pending')
        ->where('a.start_datetime >=', date('Y-m-d 00:00:00'))
        ->orderBy('a.start_datetime', 'ASC')
        ->get()
        ->getResultArray();

    // ✅ Visit History for Graphs (weight + bp)
    $visits = $db->table('visit_history')
        ->select('id, created_at, weight, blood_pressure')
        ->where('patient_id', $patient['patient_id'])
        ->orderBy('created_at', 'ASC')
        ->get()
        ->getResultArray();

    $dates = [];
    $weights = [];
    $bpSystolic = [];
    $bpDiastolic = [];

    foreach ($visits as $v) {
        $dates[] = date('d M', strtotime($v['created_at']));
        $weights[] = $v['weight'] ?? 0;

        if (!empty($v['blood_pressure']) && strpos($v['blood_pressure'], '/')) {
            [$sys, $dia] = explode('/', $v['blood_pressure']);
            $bpSystolic[] = (float) trim($sys);
            $bpDiastolic[] = (float) trim($dia);
        } else {
            $bpSystolic[] = null;
            $bpDiastolic[] = null;
        }
    }

    return view('patient/dashboard', [
        'patient' => $patient,
        'hospital' => $hospital,
        'totalAppointments' => $totalAppointments,
        'pendingAppointments' => $pendingAppointments,
        'completedAppointments' => $completedAppointments,
        'cancelledAppointments' => $cancelledAppointments, // ✅ Pass to view
        'upcomingAppointments' => $upcomingAppointments,
        'dates' => json_encode($dates),
        'weights' => json_encode($weights),
        'bpSystolic' => json_encode($bpSystolic),
        'bpDiastolic' => json_encode($bpDiastolic),
    ]);
}





public function bookAppointment()
{
    $session = session();
    $db = \Config\Database::connect();

    if (!$session->get('isLoggedIn') || $session->get('role') !== 'patient') {
        return redirect()->to('/login')->with('error', 'Access denied.');
    }

    $userId = $session->get('id');

  $hospitalId = $session->get('hospital_id');
$hospitalUserId = $session->get('hospital_user_id');


    if (!$hospitalId) {
        return redirect()->to('patient/dashboard')->with('error', 'Hospital not found.');
    }


    // ✅ Fetch available doctors from same hospital
    $doctors = $db->table('doctors d')
        ->select('d.id AS doctor_id, u.name AS doctor_name, d.expertise')
        ->join('hospital_users hu', 'd.user_hospital_id = hu.id')
        ->join('users u', 'hu.user_id = u.id')
        ->where('hu.hospital_id', $hospitalId)
        ->get()
        ->getResultArray();

    return view('patient/book_appointment', [
        'doctors' => $doctors
    ]);
}

public function saveAppointment()
{
    $session = session();
    $db = \Config\Database::connect();

    if (!$session->get('isLoggedIn') || $session->get('role') !== 'patient') {
        return redirect()->to('/login')->with('error', 'Access denied.');
    }

    $userId = $session->get('id');

    // ✅ Find patient ID
  $hospitalUserId = $session->get('hospital_user_id');

$patient = $db->table('patients p')
    ->join('hospital_users hu', 'p.user_hospital_id = hu.id')
    ->where('hu.id', $hospitalUserId)
    ->select('p.id AS patient_id, hu.hospital_id')
    ->get()
    ->getRowArray();


    if (!$patient) {
        return redirect()->to('patient/dashboard')->with('error', 'Patient not found.');
    }

    $doctorId = $this->request->getPost('doctor_id');
    $startDatetime = $this->request->getPost('start_datetime');
    $endDatetime = $this->request->getPost('end_datetime');

    if (!$doctorId || !$startDatetime || !$endDatetime) {
        return redirect()->back()->with('error', 'All fields are required.');
    }

    // ✅ Save appointment
    $db->table('appointments')->insert([
        'hospital_id'    => $patient['hospital_id'],
        'doctor_id'      => $doctorId,
        'patient_id'     => $patient['patient_id'],
        'start_datetime' => $startDatetime,
        'end_datetime'   => $endDatetime,
        'status'         => 'pending',
        'created_by'     => $userId,
        'created_at'     => date('Y-m-d H:i:s')
    ]);

    return redirect()->to('patient/appointments')->with('success', 'Appointment booked successfully!');
}



public function appointments()
{
    $session = session();
    $db = \Config\Database::connect();

    if (!$session->get('isLoggedIn') || $session->get('role') !== 'patient') {
        return redirect()->to('/login')->with('error', 'Access denied.');
    }

    $userId = $session->get('id');

    // ✅ Find patient ID
   $hospitalUserId = $session->get('hospital_user_id');

$patient = $db->table('patients p')
    ->join('hospital_users hu', 'p.user_hospital_id = hu.id')
    ->where('hu.id', $hospitalUserId)
    ->select('p.id AS patient_id')
    ->get()
    ->getRowArray();


    if (!$patient) {
        return redirect()->to('patient/dashboard')->with('error', 'Patient not found.');
    }

    $patientId = $patient['patient_id'];

    // ✅ Fetch all appointments grouped by status
    $appointments = $db->table('appointments a')
        ->select('
            a.id, a.start_datetime, a.end_datetime, a.status,
            u.name AS doctor_name,
            v.id AS visit_id,
            pr.id AS prescription_id,
            b.id AS bill_id
        ')
        ->join('doctors d', 'a.doctor_id = d.id')
        ->join('hospital_users hu', 'd.user_hospital_id = hu.id')
        ->join('users u', 'hu.user_id = u.id')
        ->join('visit_history v', 'v.appointment_id = a.id', 'left')
        ->join('prescriptions pr', 'pr.visit_id = v.id', 'left')
        ->join('bills b', 'b.appointment_id = a.id', 'left')
        ->where('a.patient_id', $patientId)
        ->orderBy('a.start_datetime', 'DESC')
        ->get()
        ->getResultArray();

    // ✅ Separate by status
    $pending = [];
    $completed = [];
    $cancelled = [];

    foreach ($appointments as $appt) {
        if ($appt['status'] === 'pending') {
            $pending[] = $appt;
        } elseif ($appt['status'] === 'completed') {
            $completed[] = $appt;
        } elseif ($appt['status'] === 'cancelled') {
            $cancelled[] = $appt;
        }
    }

    return view('patient/appointments', [
        'pending' => $pending,
        'completed' => $completed,
        'cancelled' => $cancelled
    ]);
}
   



public function cancelAppointment($id)
{
    $session = session();
    $db = \Config\Database::connect();

    if (!$session->get('isLoggedIn') || $session->get('role') !== 'patient') {
        return redirect()->to('/login')->with('error', 'Access denied.');
    }

    $db->table('appointments')->where('id', $id)->update(['status' => 'cancelled']);
    return redirect()->to('patient/appointments')->with('success', 'Appointment cancelled successfully.');
}



public function viewVisit($visitId)
{
    $session = session();
    if (!$session->get('isLoggedIn') || $session->get('role') !== 'patient') {
        return redirect()->to('/login')->with('error', 'Access denied.');
    }

    $db = \Config\Database::connect();

    $visit = $db->table('visit_history v')
        ->select('v.*, u.name as doctor_name')
        ->join('doctors d', 'v.doctor_id = d.id')
        ->join('hospital_users hu', 'd.user_hospital_id = hu.id')
        ->join('users u', 'hu.user_id = u.id')
        ->where('v.id', $visitId)
        ->get()
        ->getRowArray();

    if (!$visit) {
        return redirect()->to('patient/appointments')->with('error', 'Visit details not found.');
    }

    return view('patient/view_visit', ['visit' => $visit]);
}


public function viewPrescription($prescriptionId)
{
    $session = session();
    if (!$session->get('isLoggedIn') || $session->get('role') !== 'patient') {
        return redirect()->to('/login')->with('error', 'Access denied.');
    }

    $db = \Config\Database::connect();

    $prescription = $db->table('prescriptions p')
        ->select('p.*, u.name as doctor_name, v.appointment_id')
        ->join('visit_history v', 'v.id = p.visit_id')
        ->join('doctors d', 'p.doctor_id = d.id')
        ->join('hospital_users hu', 'd.user_hospital_id = hu.id')
        ->join('users u', 'hu.user_id = u.id')
        ->where('p.id', $prescriptionId)
        ->get()
        ->getRowArray();

    if (!$prescription) {
        return redirect()->to('patient/appointments')->with('error', 'Prescription not found.');
    }

    // Get medicines
    $items = $db->table('prescription_items')->where('prescription_id', $prescriptionId)->get()->getResultArray();

    return view('patient/view_prescription', [
        'prescription' => $prescription,
        'items' => $items
    ]);
}


public function viewBill($billId)
{
    $session = session();
    if (!$session->get('isLoggedIn') || $session->get('role') !== 'patient') {
        return redirect()->to('/login')->with('error', 'Access denied.');
    }

    $db = \Config\Database::connect();

    $bill = $db->table('bills b')
        ->select('b.*, u.name as doctor_name, p2.name as patient_name')
        ->join('doctors d', 'b.doctor_id = d.id')
        ->join('hospital_users hu1', 'd.user_hospital_id = hu1.id')
        ->join('users u', 'hu1.user_id = u.id')
        ->join('patients p', 'b.patient_id = p.id')
        ->join('hospital_users hu2', 'p.user_hospital_id = hu2.id')
        ->join('users p2', 'hu2.user_id = p2.id')
        ->where('b.id', $billId)
        ->get()
        ->getRowArray();

    if (!$bill) {
        return redirect()->to('patient/appointments')->with('error', 'Bill not found.');
    }

    $services = $db->table('bill_services')->where('bill_id', $billId)->get()->getResultArray();

    return view('patient/view_bill', [
        'bill' => $bill,
        'services' => $services
    ]);
}


public function visitHistory()
{
    $session = session();
    $db = \Config\Database::connect();

    if (!$session->get('isLoggedIn') || $session->get('role') !== 'patient') {
        return redirect()->to('/login')->with('error', 'Access denied.');
    }

    $userId = $session->get('id');

    // ✅ Find patient_id
   $hospitalUserId = $session->get('hospital_user_id');

$patient = $db->table('patients p')
    ->join('hospital_users hu', 'p.user_hospital_id = hu.id')
    ->join('users u', 'hu.user_id = u.id')
    ->where('hu.id', $hospitalUserId)
    ->select('p.id AS patient_id, u.name AS patient_name')
    ->get()
    ->getRowArray();


    if (!$patient) {
        return redirect()->to('patient/dashboard')->with('error', 'Patient record not found.');
    }

    // ✅ Fetch all visits for this patient
    $visits = $db->table('visit_history v')
        ->select('v.*, a.start_datetime, u.name AS doctor_name')
        ->join('appointments a', 'v.appointment_id = a.id', 'left')
        ->join('doctors d', 'v.doctor_id = d.id', 'left')
        ->join('hospital_users hu', 'd.user_hospital_id = hu.id', 'left')
        ->join('users u', 'hu.user_id = u.id', 'left')
        ->where('v.patient_id', $patient['patient_id'])
        ->orderBy('v.created_at', 'DESC')
        ->get()
        ->getResultArray();

    // ✅ Decode complaint-diagnosis JSON (if stored structured)
    foreach ($visits as &$v) {
        if (!empty($v['complaints']) && json_decode($v['complaints'], true)) {
            $v['complaints_data'] = json_decode($v['complaints'], true);
        } else {
            $v['complaints_data'] = [];
        }
    }

    return view('patient/visit_history', [
        'visits' => $visits,
        'patient' => $patient
    ]);
}


}
