<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AppointmentModel;
use App\Models\DoctorModel;
use App\Models\UserModel;
use App\Models\VisitHistoryModel;
use CodeIgniter\HTTP\ResponseInterface;

class VisitHistoryController extends BaseController
{
   protected $visitHistoryModel;
    protected $appointmentModel;
    protected $doctorModel;
    protected $userModel;
    protected $session;

    public function __construct()
    {
        $this->visitHistoryModel = new VisitHistoryModel();
        $this->appointmentModel  = new AppointmentModel();
        $this->doctorModel       = new DoctorModel();
        $this->userModel         = new UserModel();
        $this->session           = session();
    }

    // 🩺 View all visit history for logged-in patient
    public function index()
    {
        $patientId = $this->session->get('user_id');

        $visits = $this->visitHistoryModel
            ->select('visit_history.*, doctors.id as doctor_id, users.name as doctor_name')
            ->join('doctors', 'doctors.id = visit_history.doctor_id', 'left')
            ->join('users', 'users.id = doctors.user_id', 'left')
            ->where('visit_history.patient_id', $patientId)
            ->orderBy('visit_history.created_at', 'DESC')
            ->findAll();

        return view('patient/visit_history', ['visits' => $visits]);
    }

    // 📋 Doctor adds visit details for completed appointment
    public function add($appointmentId)
    {
        $appointment = $this->appointmentModel->find($appointmentId);

        if (!$appointment) {
            return redirect()->back()->with('error', 'Appointment not found');
        }

        return view('doctor/add_visit', ['appointment' => $appointment]);
    }

    // 💾 Save visit details
    public function save()
    {
        $data = [
            'appointment_id' => $this->request->getPost('appointment_id'),
            'patient_id'     => $this->request->getPost('patient_id'),
            'doctor_id'      => $this->request->getPost('doctor_id'),
            'reason'         => $this->request->getPost('reason'),
            'weight'         => $this->request->getPost('weight'),
            'blood_pressure' => $this->request->getPost('blood_pressure'),
            'doctor_comments'=> $this->request->getPost('doctor_comments'),
        ];

        $this->visitHistoryModel->insert($data);

        return redirect()->to('/doctor/appointments')->with('success', 'Visit details saved successfully');
    }
}
