<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\HospitalUserModel;
use App\Models\AdminModel;
use App\Models\DoctorModel;
use App\Models\HospitalModel;
use App\Models\PatientModel;
use CodeIgniter\Controller;

class AuthController extends Controller
{
    // 🔹 Landing Page
    public function landing()
    {
        return view('landing');
    }

    public function register()
    {
        $hospitalModel = new HospitalModel();
        $data['hospitals'] = $hospitalModel->where('status', 'active')->findAll();
        return view('auth/register', $data);
    }

  public function registerPost()
{
    $userModel = new UserModel();
    $hospitalUserModel = new HospitalUserModel();
    $adminModel = new AdminModel();
    $doctorModel = new DoctorModel();
    $patientModel = new PatientModel();

    $role = $this->request->getPost('role');
    $hospitalId = $this->request->getPost('hospital_id');

    $userData = [
        'name' => $this->request->getPost('name'),
        'email' => $this->request->getPost('email'),
        'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
        'role' => $role,
        'created_at' => date('Y-m-d H:i:s')
    ];

    $db = \Config\Database::connect();
    $db->transStart();

    // ✅ Step 1: Check if user exists
    $existingUser = $userModel->where('email', $userData['email'])->first();

    if ($existingUser) {
        $userId = $existingUser['id'];

        // ✅ Check if already linked to this hospital for same role
        $existingHU = $hospitalUserModel
            ->where([
                'user_id' => $userId,
                'hospital_id' => $hospitalId,
                'role' => $role
            ])
            ->first();

        if ($existingHU) {
            $db->transRollback();
            return redirect()->back()->with('error', 'User already registered as ' . $role . ' for this hospital.');
        }
    } else {
        // 🆕 New user
        $userModel->insert($userData);
        $userId = $userModel->getInsertID();
    }

    // ✅ Step 2: Add to hospital_users
    $hospitalUserModel->insert([
        'user_id' => $userId,
        'hospital_id' => $hospitalId,
        'role' => $role,
        'status' => 'active',
        'created_at' => date('Y-m-d H:i:s')
    ]);
    $userHospitalId = $hospitalUserModel->getInsertID();

    // ✅ Step 3: Add to specific role table
    if ($role === 'admin') {
        $adminModel->insert([
            'user_hospital_id' => $userHospitalId,
            'created_by' => $userId,
            'updated_by' => $userId,
        ]);
    } elseif ($role === 'doctor') {
        $doctorModel->insert([
            'user_hospital_id' => $userHospitalId,
            'age' => $this->request->getPost('age'),
            'gender' => $this->request->getPost('gender'),
            'expertise' => $this->request->getPost('expertise'),
            'availability_type' => $this->request->getPost('availability_type'),
        ]);
    } elseif ($role === 'patient') {
        $patientModel->insert([
            'user_hospital_id' => $userHospitalId,
            'age' => $this->request->getPost('age'),
            'gender' => $this->request->getPost('gender'),
        ]);
    }

    $db->transComplete();

    if ($db->transStatus() === false) {
        return redirect()->back()->with('error', 'Registration failed.');
    }

    return redirect()->to('login')->with('success', ucfirst($role) . ' registered successfully!');
}




  public function login()
{
    $hospitalModel = new \App\Models\HospitalModel();

    // ✅ Fetch only active hospitals
    $data['hospitals'] = $hospitalModel->where('status', 'active')->findAll();

    // ✅ Pass hospitals to the view
    return view('auth/login', $data);
}


  public function loginPost()
{
    $session = session();
    $userModel = new UserModel();
    $hospitalUserModel = new \App\Models\HospitalUserModel();

    $email = $this->request->getPost('email');
    $password = $this->request->getPost('password');
    $role = $this->request->getPost('role');
    $hospitalId = $this->request->getPost('hospital_id');

    $user = $userModel->where('email', $email)->first();

    if (!$user) {
        return redirect()->back()->with('error', 'Email not found');
    }

    if (!password_verify($password, $user['password'])) {
        return redirect()->back()->with('error', 'Invalid password');
    }

    // ✅ Superadmin: no hospital needed
    if ($role === 'superadmin' && $user['role'] === 'superadmin') {
        $session->set([
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role'],
            'isLoggedIn' => true,
        ]);
        return redirect()->to('/superadmin/dashboard');
    }

    // ✅ Other roles must match hospital + role in hospital_users
    $hospitalUser = $hospitalUserModel
        ->where([
            'user_id' => $user['id'],
            'hospital_id' => $hospitalId,
            'role' => $role,
            'status' => 'active'
        ])
        ->first();

    if (!$hospitalUser) {
        return redirect()->back()->with('error', 'User not associated with this hospital or inactive.');
    }

    // ✅ Store session
    $session->set([
        'id' => $user['id'],
        'hospital_user_id' => $hospitalUser['id'], 
        'name' => $user['name'],
        'email' => $user['email'],
        'role' => $role,
        'hospital_id' => $hospitalId,
        'isLoggedIn' => true,
    ]);

    // ✅ Redirect by role
    switch ($role) {
        case 'admin':
            return redirect()->to('/admin/dashboard');
        case 'doctor':
            return redirect()->to('/doctor/dashboard');
        case 'patient':
            return redirect()->to('/patient/dashboard');
        default:
            return redirect()->to('/login')->with('error', 'Invalid role selection.');
    }
}


    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
