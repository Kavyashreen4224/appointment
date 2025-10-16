<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\HospitalModel;

use CodeIgniter\HTTP\ResponseInterface;

class AuthController extends BaseController
{
    protected $userModel;
    protected $hospitalModel;
    protected $session;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->hospitalModel = new HospitalModel();
        $this->session = session();
    }

    // 🔹 Landing Page
    public function landing()
    {
        return view('landing');
    }

    // 🔹 Register Form
    public function register()
    {
        $hospitals = $this->hospitalModel->getActiveHospitals();
        return view('auth/register', ['hospitals' => $hospitals]);
    }

    // 🔹 Handle Registration
public function registerPost()
{
    $role = $this->request->getPost('role');
    $hospital_id = $this->request->getPost('hospital_id') ?? null; // superadmin may have null
    $email = $this->request->getPost('email');

    // Check if the user already exists for this hospital
    if ($role !== 'superadmin') {
        $existingUser = $this->userModel
            ->where('email', $email)
            ->where('hospital_id', $hospital_id)
            ->first();

        if ($existingUser) {
            return redirect()->back()->with('error', 'This email is already registered for this hospital.');
        }
    }

    // Save user data
    $userData = [
        'name' => $this->request->getPost('name'),
        'email' => $email,
        'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
        'role' => $role,
        'hospital_id' => $role !== 'superadmin' ? $hospital_id : null
    ];

    $this->userModel->save($userData);
    $userId = $this->userModel->getInsertID(); // Get the generated user ID

    // Role-specific inserts
    switch ($role) {
        case 'superadmin':
            // Nothing else needed
            break;

        case 'admin':
            $adminModel = new \App\Models\AdminModel();
            $adminData = [
                'user_id' => $userId,
                'hospital_id' => $hospital_id,
                'created_by' => $userId
            ];
            $adminModel->insert($adminData);
            break;

        case 'doctor':
            $doctorModel = new \App\Models\DoctorModel();
            $doctorData = [
                'user_id' => $userId,
                'hospital_id' => $hospital_id,
                'age' => $this->request->getPost('age') ?? null,
                'gender' => $this->request->getPost('gender') ?? null,
                'expertise' => $this->request->getPost('expertise') ?? null,
                'availability' => $this->request->getPost('availability') ?? null,
                'created_by' => $userId
            ];
            $doctorModel->insert($doctorData);
            break;

        case 'patient':
            $patientModel = new \App\Models\PatientModel();
            $patientData = [
                'user_id' => $userId,
                'hospital_id' => $hospital_id,
                'age' => $this->request->getPost('age') ?? null,
                'gender' => $this->request->getPost('gender') ?? null,
                'created_by' => $userId
            ];
            $patientModel->insert($patientData);
            break;
    }

    return redirect()->to('/login')->with('success', 'Registered successfully, please login!');
}



    // 🔹 Login Form
public function login()
{
    $role = $this->request->getVar('role') ?? null;

    // For superadmin, hide the hospital dropdown
    if ($role === 'superadmin') {
        $hospitals = [];
        $hideHospitalDropdown = true;
    } else {
        $hospitals = $this->hospitalModel->getActiveHospitals();
        $hideHospitalDropdown = false;
    }

    return view('auth/login', [
        'hospitals' => $hospitals,
        'hideHospitalDropdown' => $hideHospitalDropdown
    ]);
}


    // 🔹 Handle Login
//    public function loginPost()
// {
//     $email = $this->request->getPost('email');
//     $password = $this->request->getPost('password');
//     $hospital_id = $this->request->getPost('hospital_id'); // may be null for superadmin

//     // Fetch user
//     $user = $this->userModel->where('email', $email)->first();

//     if ($user && password_verify($password, $user['password'])) {

//         // For non-superadmin, ensure hospital matches
//         if ($user['role'] !== 'superadmin' && $user['hospital_id'] != $hospital_id) {
//             return redirect()->back()->with('error', 'Invalid hospital for this user.');
//         }

//         // Set session
//         $this->session->set([
//             'user_id' => $user['id'],
//             'name' => $user['name'],
//             'role' => $user['role'],
//             'hospital_id' => $user['role'] === 'superadmin' ? null : $user['hospital_id'],
//             'logged_in' => true
//         ]);

//         // Redirect based on role
//         switch($user['role']) {
//             case 'superadmin':
//                 return redirect()->to('/superadmin/dashboard');
//             case 'admin':
//                 return redirect()->to('/admin/dashboard');
//             case 'doctor':
//                 return redirect()->to('/doctor/dashboard');
//             case 'patient':
//                 return redirect()->to('/patient/dashboard');
//         }

//     } else {
//         return redirect()->back()->with('error', 'Invalid credentials');
//     }
// }

public function loginPost()
{
    $email = $this->request->getPost('email');
$password = $this->request->getPost('password');
$hospital_id = $this->request->getPost('hospital_id');

// Fetch user
$userQuery = $this->userModel->where('email', $email);
if ($hospital_id) {
    $userQuery->where('hospital_id', $hospital_id);
}
$user = $userQuery->first();

// Check password & role
if ($user && password_verify($password, $user['password'])) {
    $this->session->set([
        'user_id' => $user['id'],
        'name' => $user['name'],
        'role' => $user['role'],
        'hospital_id' => $user['hospital_id'],
        'logged_in' => true
    ]);

    // Redirect based on role
    switch ($user['role']) {
        case 'superadmin':
            return redirect()->to('/superadmin/dashboard');
        case 'admin':
            return redirect()->to('/admin/dashboard');
        case 'doctor':
            return redirect()->to('/doctor/dashboard');
        case 'patient':
            return redirect()->to('/patient/dashboard');
    }
} else {
    return redirect()->back()->with('error', 'Invalid credentials or hospital');
}

}




    // 🔹 Logout
    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/');
    }
}
