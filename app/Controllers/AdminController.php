<?php

namespace App\Controllers;

use App\Models\DoctorModel;
use App\Models\PatientModel;
use App\Models\AppointmentModel;

class AdminController extends BaseController
{
    protected $doctorModel;
    protected $patientModel;
    protected $appointmentModel;
    protected $session;

    public function __construct()
    {
        $this->doctorModel = new DoctorModel();
        $this->patientModel = new PatientModel();
        $this->appointmentModel = new AppointmentModel();
        $this->session = session();
    }

    // ---------- DASHBOARD ----------
public function dashboard()
{
    $hospital_id = session()->get('hospital_id'); // assume admin logged in

    $doctorModel = new \App\Models\DoctorModel();
    $patientModel = new \App\Models\PatientModel();
    $appointmentModel = new \App\Models\AppointmentModel();

    $data = [
        'totalDoctors' => count($doctorModel->getDoctorsByHospital($hospital_id)),
        'totalPatients' => count($patientModel->getPatientsByHospital($hospital_id)),
        'totalAppointments' => count($appointmentModel->getAppointmentsByHospital($hospital_id)),
        'hospital_id' => $hospital_id
    ];

    return view('admin/dashboard', $data);
}




    public function listDoctors()
{
    $hospital_id = session()->get('hospital_id');
    $doctorModel = new \App\Models\DoctorModel();
    $doctors = $doctorModel->getDoctorsByHospital($hospital_id);

    return view('admin/doctors_list', ['doctors' => $doctors]);
}

public function addDoctor()
{
    $userModel = new \App\Models\UserModel();
    if ($this->request->getMethod() === 'post') {
        $user_id = $userModel->insert([
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role' => 'doctor',
            'hospital_id' => session()->get('hospital_id'),
            'created_at' => date('Y-m-d H:i:s')
        ]);

        $doctorModel = new \App\Models\DoctorModel();
        $doctorModel->insert([
            'user_id' => $user_id,
            'hospital_id' => session()->get('hospital_id'),
            'age' => $this->request->getPost('age'),
            'gender' => $this->request->getPost('gender'),
            'expertise' => $this->request->getPost('expertise'),
            'availability' => $this->request->getPost('availability'),
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->to(site_url('admin/listDoctors'))->with('success', 'Doctor added successfully!');
    }

    return view('admin/add_doctor');
}


   public function listPatients()
{
    $hospital_id = session()->get('hospital_id');
    $patientModel = new \App\Models\PatientModel();
    $patients = $patientModel->getPatientsByHospital($hospital_id);

    return view('admin/patients_list', ['patients' => $patients]);
}

public function addPatient()
{
    $userModel = new \App\Models\UserModel();
    if ($this->request->getMethod() === 'post') {
        $user_id = $userModel->insert([
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role' => 'patient',
            'hospital_id' => session()->get('hospital_id'),
            'created_at' => date('Y-m-d H:i:s')
        ]);

        $patientModel = new \App\Models\PatientModel();
        $patientModel->insert([
            'user_id' => $user_id,
            'hospital_id' => session()->get('hospital_id'),
            'age' => $this->request->getPost('age'),
            'gender' => $this->request->getPost('gender'),
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->to(site_url('admin/listPatients'))->with('success', 'Patient added successfully!');
    }

    return view('admin/add_patient');
}



}