<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AdminModel;
use App\Models\AppointmentModel;
use App\Models\DoctorModel;
use App\Models\HospitalModel;
use App\Models\HospitalUserModel;
use App\Models\PatientModel;
use App\Models\PrescriptionModel;
use App\Models\SuperAdminModel;
use App\Models\UserModel;
use App\Models\VisitHistoryModel;
use CodeIgniter\HTTP\ResponseInterface;
use \Dompdf\Dompdf;
use \Dompdf\Options;


class SuperAdminController extends BaseController
{


    public function dashboard()
    {
        $hospitalModel = new HospitalModel();
        $hospitalUserModel = new HospitalUserModel();

        // Count total hospitals
        $hospitalCount = $hospitalModel->countAllResults();

        // Count all admins from hospital_users where role = 'admin'
        $adminCount = $hospitalUserModel->where('role', 'admin')->countAllResults();

        return view('superadmin/dashboard', [
            'hospitalCount' => $hospitalCount,
            'adminCount'    => $adminCount
        ]);
    }

    // -------------------
    // HOSPITAL MANAGEMENT
    // -------------------
    public function listHospitals()
    {
        $hospitalModel = new HospitalModel();
        $hospitals = $hospitalModel->findAll();

        return view('superadmin/listHospitals', ['hospitals' => $hospitals]);
    }

    public function addHospital()
    {
        return view('superadmin/add_hospital');
    }

    public function saveHospital()
    {
        $hospitalModel = new HospitalModel();

        $data = [
            'name'    => $this->request->getPost('name'),
            'address' => $this->request->getPost('address'),
            'contact' => $this->request->getPost('contact'),
            'email'   => $this->request->getPost('email'),
            'status'  => 'active'
        ];

        $hospitalModel->insert($data);

        return redirect()->to('superadmin/listHospitals')->with('success', 'Hospital added successfully.');
    }


    public function editHospital($id)
    {
        $hospitalModel = new HospitalModel();
        $hospital = $hospitalModel->find($id);

        if (!$hospital) {
            return redirect()->to('superadmin/listHospitals')->with('error', 'Hospital not found.');
        }

        return view('superadmin/edit_hospital', ['hospital' => $hospital]);
    }

    // -------------------
    // UPDATE HOSPITAL
    // -------------------
    public function updateHospital($id)
    {
        $hospitalModel = new HospitalModel();

        $data = [
            'name'    => $this->request->getPost('name'),
            'email'   => $this->request->getPost('email'),
            'contact' => $this->request->getPost('contact'),
            'address' => $this->request->getPost('address'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $hospitalModel->update($id, $data);

        return redirect()->to('superadmin/listHospitals')->with('success', 'Hospital updated successfully.');
    }

    // -------------------
    // DELETE HOSPITAL
    // -------------------
    public function deleteHospital($id)
    {
        $hospitalModel = new HospitalModel();

        if ($hospitalModel->find($id)) {
            $hospitalModel->delete($id);
            return redirect()->to('superadmin/listHospitals')->with('success', 'Hospital deleted successfully.');
        }

        return redirect()->to('superadmin/listHospitals')->with('error', 'Hospital not found.');
    }


    // -------------------
    // ADMIN MANAGEMENT
    // -------------------
 public function listAdmins()
{
    $db = \Config\Database::connect();

    // Build query to fetch admin details with proper aliasing
    $builder = $db->table('admins')
        ->select('
            admins.id AS admin_id,
            users.id AS user_id,
            hospital_users.id AS hospital_user_id,
            users.name AS admin_name,
            users.email AS admin_email,
            hospitals.name AS hospital_name,
            hospital_users.status,
            admins.created_at
        ')
        ->join('hospital_users', 'admins.user_hospital_id = hospital_users.id', 'left')
        ->join('users', 'hospital_users.user_id = users.id', 'left')
        ->join('hospitals', 'hospital_users.hospital_id = hospitals.id', 'left')
        ->where('hospital_users.role', 'admin')
        ->orderBy('admins.created_at', 'DESC');

    $query = $builder->get();
    $data['admins'] = $query->getResultArray();

    return view('superadmin/listAdmins', $data);
}



    // -------------------
// ADD ADMIN
// -------------------
public function addAdmin()
{
    $hospitalModel = new \App\Models\HospitalModel();
    $hospitals = $hospitalModel->findAll();

    return view('superadmin/addAdmin', ['hospitals' => $hospitals]);
}



public function saveAdmin()
{
    $db = \Config\Database::connect();
    $db->transStart();

    $name = $this->request->getPost('name');
    $email = $this->request->getPost('email');
    $password = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
    $hospital_id = $this->request->getPost('hospital_id');

    // For created_by tracking
    $created_by = session()->get('user_id'); // assuming SuperAdmin is logged in

    try {
        // 1️⃣ Insert into users table
        $db->table('users')->insert([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'role' => 'admin',
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        $user_id = $db->insertID();

        // 2️⃣ Insert into hospital_users (link user to hospital)
        $db->table('hospital_users')->insert([
            'user_id' => $user_id,
            'hospital_id' => $hospital_id,
            'role' => 'admin',
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        $user_hospital_id = $db->insertID();

        // 3️⃣ Insert into admins (link to hospital_users)
        $db->table('admins')->insert([
            'user_hospital_id' => $user_hospital_id,
            'created_by' => $created_by,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            throw new \Exception('Database transaction failed.');
        }

        return redirect()->to('superadmin/listAdmins')->with('success', 'Admin added successfully.');
    } catch (\Exception $e) {
        $db->transRollback();
        return redirect()->back()->with('error', $e->getMessage());
    }
}


// -------------------
// EDIT ADMIN
// -------------------
public function editAdmin($id)
{
    $db = \Config\Database::connect();

    // Get the admin record with related user and hospital info
    $query = $db->query("
        SELECT hu.id AS hospital_user_id, hu.hospital_id, u.id AS user_id, u.name, u.email
        FROM hospital_users hu
        JOIN users u ON hu.user_id = u.id
        WHERE hu.id = ?
    ", [$id]);
    $admin = $query->getRowArray();

    if (!$admin) {
        return redirect()->to('superadmin/listAdmins')->with('error', 'Admin not found.');
    }

    // Get hospitals for dropdown
    $hospitalModel = new \App\Models\HospitalModel();
    $hospitals = $hospitalModel->findAll();

    return view('superadmin/editAdmin', [
        'admin' => $admin,
        'hospitals' => $hospitals
    ]);
}


// -------------------
// UPDATE ADMIN
// -------------------
public function updateAdmin($id)
{
    $db = \Config\Database::connect();
    $db->transStart();

    $name = $this->request->getPost('name');
    $email = $this->request->getPost('email');
    $password = $this->request->getPost('password'); // optional
    $hospital_id = $this->request->getPost('hospital_id');

    try {
        // Get the related user_id for this admin
        $hu = $db->table('hospital_users')->where('id', $id)->get()->getRowArray();
        if (!$hu) {
            throw new \Exception('Hospital user not found.');
        }

        $updateUserData = [
            'name' => $name,
            'email' => $email,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if (!empty($password)) {
            $updateUserData['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        // Update users table
        $db->table('users')->where('id', $hu['user_id'])->update($updateUserData);

        // Update hospital_users table (hospital assignment)
        $db->table('hospital_users')->where('id', $id)->update([
            'hospital_id' => $hospital_id,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            throw new \Exception('Database update failed.');
        }

        return redirect()->to('superadmin/listAdmins')->with('success', 'Admin updated successfully.');
    } catch (\Exception $e) {
        $db->transRollback();
        return redirect()->back()->with('error', $e->getMessage());
    }
}


// -------------------
// DELETE ADMIN
// -------------------
public function deleteAdmin($id)
{
    $db = \Config\Database::connect();
    $db->transStart();

    try {
        // 1️⃣ Fetch the hospital_users record
        $hu = $db->table('hospital_users')->where('id', $id)->get()->getRowArray();

        if (!$hu) {
            throw new \Exception('Admin record not found.');
        }

        $user_id = $hu['user_id'];

        // 2️⃣ Delete from admins table first (based on user_hospital_id)
        $db->table('admins')->where('user_hospital_id', $id)->delete();

        // 3️⃣ Delete from hospital_users table
        $db->table('hospital_users')->where('id', $id)->delete();

        // 4️⃣ Check if this user still has links to other hospitals
        $linkedHospitals = $db->table('hospital_users')->where('user_id', $user_id)->countAllResults();

        if ($linkedHospitals == 0) {
            // ✅ No more hospital links → delete user completely
            $db->table('users')->where('id', $user_id)->delete();
        } else {
            // ⚠️ User is linked elsewhere → just log info (optional)
            log_message('info', "User ID {$user_id} still linked to {$linkedHospitals} other hospital(s), not deleting from users table.");
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            throw new \Exception('Deletion transaction failed.');
        }

        return redirect()->to('superadmin/listAdmins')->with('success', 'Admin deleted successfully.');
    } catch (\Exception $e) {
        $db->transRollback();
        return redirect()->to('superadmin/listAdmins')->with('error', $e->getMessage());
    }
}




// -------------------
// DOCTOR MANAGEMENT
// -------------------

public function listDoctors()
{
    $hospitalModel = new \App\Models\HospitalModel();
    $doctorModel = new \App\Models\DoctorModel();
    $hospitalUsersModel = new \App\Models\HospitalUserModel();
    $userModel = new \App\Models\UserModel();

    $hospitalId = $this->request->getGet('hospital_id');

    // Fetch all hospitals for filter dropdown
    $data['hospitals'] = $hospitalModel->findAll();

    // Fetch doctors with hospital info
    $builder = $hospitalUsersModel
        ->select('users.id as user_id, users.name, users.email, users.created_at, hospital_users.status, hospitals.name as hospital_name')
        ->join('users', 'users.id = hospital_users.user_id')
        ->join('hospitals', 'hospitals.id = hospital_users.hospital_id')
        ->where('hospital_users.role', 'doctor');

    if (!empty($hospitalId)) {
        $builder->where('hospital_users.hospital_id', $hospitalId);
    }

    $data['doctors'] = $builder->findAll();
    $data['selectedHospital'] = $hospitalId;
    $data['title'] = 'Doctor List';

    return view('superadmin/listDoctors', $data);
}

public function viewDoctor($doctorId)
{
    $hospitalUsersModel = new \App\Models\HospitalUserModel();

    $doctor = $hospitalUsersModel
        ->select('users.name, users.email, users.created_at, hospital_users.status, hospitals.name as hospital_name, hospitals.address, hospitals.contact')
        ->join('users', 'users.id = hospital_users.user_id')
        ->join('hospitals', 'hospitals.id = hospital_users.hospital_id')
        ->where('hospital_users.role', 'doctor')
        ->where('users.id', $doctorId)
        ->first();

    if (!$doctor) {
        return redirect()->back()->with('error', 'Doctor not found');
    }

    $data['doctor'] = $doctor;
    $data['title'] = 'Doctor Profile';

    return view('superadmin/viewDoctor', $data);
}



public function listPatients()
{
    $hospitalModel = new \App\Models\HospitalModel();
    $userModel = new \App\Models\UserModel();
    $hospitalUsersModel = new \App\Models\HospitalUserModel();

    $hospitalId = $this->request->getGet('hospital_id');

    // Fetch hospitals for filter dropdown
    $data['hospitals'] = $hospitalModel->findAll();

    // Fetch patients (filter by hospital if selected)
    $builder = $hospitalUsersModel
        ->select('users.id, users.name, users.email, users.created_at, hospital_users.status, hospitals.name as hospital_name')
        ->join('users', 'users.id = hospital_users.user_id')
        ->join('hospitals', 'hospitals.id = hospital_users.hospital_id')
        ->where('hospital_users.role', 'patient');

    if (!empty($hospitalId)) {
        $builder->where('hospital_users.hospital_id', $hospitalId);
    }

    $data['patients'] = $builder->findAll();
    $data['title'] = 'Patients List';

    return view('superadmin/listPatients', $data);
}


public function addPatient()
{
    $hospitalModel = new \App\Models\HospitalModel();
    $userModel = new \App\Models\UserModel();
    $hospitalUserModel = new \App\Models\HospitalUserModel();
    $patientModel = new \App\Models\PatientModel();

    // Fetch all hospitals for dropdown
    $data['hospitals'] = $hospitalModel->findAll();

    if ($this->request->getMethod() === 'post') {
        $hospital_id = $this->request->getPost('hospital_id');
        $name = $this->request->getPost('name');
        $email = $this->request->getPost('email');
        $password = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        $age = $this->request->getPost('age');
        $gender = $this->request->getPost('gender');

        $db = \Config\Database::connect();
        $db->transStart();

        // 1️⃣ Insert into users
        $userModel->insert([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'role' => 'patient',
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $user_id = $userModel->getInsertID();

        // 2️⃣ Insert into hospital_users
        $hospitalUserModel->insert([
            'user_id' => $user_id,
            'hospital_id' => $hospital_id,
            'role' => 'patient',
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $hospital_user_id = $hospitalUserModel->getInsertID();

        // 3️⃣ Insert into patients
        $patientModel->insert([
            'user_hospital_id' => $hospital_user_id,
            'age' => $age,
            'gender' => $gender,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Failed to add patient.');
        }

        return redirect()->to('superadmin/listPatients')->with('success', 'Patient added successfully.');
    }

    $data['title'] = 'Add Patient';
    echo view('superadmin/layouts/header', $data);
    echo view('superadmin/addPatient', $data);
    echo view('superadmin/layouts/footer');
}



public function savePatient()
{
    $userModel = new \App\Models\UserModel();
    $hospitalUsersModel = new \App\Models\HospitalUserModel();

    $userData = [
        'name'       => $this->request->getPost('name'),
        'email'      => $this->request->getPost('email'),
        'password'   => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
        'role'       => 'patient',
        'created_at' => date('Y-m-d H:i:s')
    ];

    $userModel->insert($userData);
    $userId = $userModel->getInsertID();

    $hospitalUsersModel->insert([
        'user_id'     => $userId,
        'hospital_id' => $this->request->getPost('hospital_id'),
        'role'        => 'patient',
        'status'      => 'active',
        'created_at'  => date('Y-m-d H:i:s')
    ]);

    return redirect()->to('superadmin/listPatients')->with('success', 'Patient added successfully.');
}


public function patientProfile($id)
{
    $userModel = new \App\Models\UserModel();
    $hospitalUsersModel = new \App\Models\HospitalUserModel();
    $hospitalModel = new \App\Models\HospitalModel();

    $patient = $userModel
        ->select('users.*, hospitals.name as hospital_name, hospital_users.status')
        ->join('hospital_users', 'hospital_users.user_id = users.id', 'left')
        ->join('hospitals', 'hospitals.id = hospital_users.hospital_id', 'left')
        ->where('users.id', $id)
        ->first();

    if (!$patient) {
        throw new \CodeIgniter\Exceptions\PageNotFoundException("Patient not found");
    }

    $data['patient'] = $patient;
    $data['title'] = 'Patient Profile';

    return view('superadmin/patientProfile', $data);
}



public function updatePatient($id)
{
    $userModel = new \App\Models\UserModel();
    $hospitalUserModel = new \App\Models\HospitalUserModel();
    $patientModel = new \App\Models\PatientModel();

    $db = \Config\Database::connect();
    $db->transStart();

    // 1. Update users table
    $userUpdate = [
        'name' => $this->request->getPost('name'),
        'email' => $this->request->getPost('email'),
        'updated_at' => date('Y-m-d H:i:s')
    ];

    $password = $this->request->getPost('password');
    if (!empty($password)) {
        $userUpdate['password'] = password_hash($password, PASSWORD_BCRYPT);
    }

    $userModel->update($id, $userUpdate);

    // 2. Update hospital_users table
    $hospital_id = $this->request->getPost('hospital_id');
    $status = $this->request->getPost('status');

    $hospitalUser = $hospitalUserModel->where('user_id', $id)->first();

    if ($hospitalUser) {
        $hospitalUserModel->update($hospitalUser['id'], [
            'hospital_id' => $hospital_id,
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        // 3. Update patients table
        $patientModel->where('user_hospital_id', $hospitalUser['id'])->set([
            'age' => $this->request->getPost('age'),
            'gender' => $this->request->getPost('gender'),
            'updated_at' => date('Y-m-d H:i:s')
        ])->update();
    }

    $db->transComplete();

    if ($db->transStatus() === false) {
        return redirect()->back()->with('error', 'Error updating patient.');
    }

    return redirect()->to('superadmin/listPatients')->with('success', 'Patient updated successfully.');
}



public function editPatient($id)
{
    $db = \Config\Database::connect();
    $query = $db->query("
        SELECT u.*, p.age, p.gender, h.id AS hospital_id, h.name AS hospital_name, hu.status
        FROM users u
        JOIN hospital_users hu ON hu.user_id = u.id
        JOIN hospitals h ON h.id = hu.hospital_id
        JOIN patients p ON p.user_hospital_id = hu.id
        WHERE u.id = ?
    ", [$id]);

    $patient = $query->getRowArray();

    if (!$patient) {
        throw new \CodeIgniter\Exceptions\PageNotFoundException('Patient not found');
    }

    $hospitalModel = new \App\Models\HospitalModel();
    $data = [
        'title' => 'Edit Patient',
        'patient' => $patient,
        'hospitals' => $hospitalModel->findAll()
    ];

    return view('superadmin/editPatient', $data);
}


public function deletePatient($id)
{
    $userModel = new \App\Models\UserModel();
    $hospitalUserModel = new \App\Models\HospitalUserModel();
    $patientModel = new \App\Models\PatientModel();

    $db = \Config\Database::connect();
    $db->transStart();

    // Get hospital_user id
    $hospitalUser = $hospitalUserModel->where('user_id', $id)->first();

    if ($hospitalUser) {
        // Delete from patients table
        $patientModel->where('user_hospital_id', $hospitalUser['id'])->delete();

        // Delete from hospital_users table
        $hospitalUserModel->delete($hospitalUser['id']);
    }

    // Delete from users table (only if not linked to another hospital)
    $otherLinks = $hospitalUserModel->where('user_id', $id)->countAllResults();
    if ($otherLinks == 0) {
        $userModel->delete($id);
    }

    $db->transComplete();

    if ($db->transStatus() === false) {
        return redirect()->back()->with('error', 'Error deleting patient.');
    }

    return redirect()->to('superadmin/listPatients')->with('success', 'Patient deleted successfully.');
}




public function viewPatient($id)
{
    $db = \Config\Database::connect();

    $query = $db->query("
        SELECT 
            u.name AS patient_name,
            u.email AS patient_email,
            u.role AS user_role,
            p.age,
            p.gender,
            p.created_at AS patient_created,
            h.name AS hospital_name,
            h.contact AS hospital_contact,
            h.address AS hospital_address,
            h.email AS hospital_email,
            hu.status AS patient_status,
            hu.created_at AS linked_on
        FROM patients p
        JOIN hospital_users hu ON p.user_hospital_id = hu.id
        JOIN users u ON hu.user_id = u.id
        JOIN hospitals h ON hu.hospital_id = h.id
        WHERE p.id = ?
    ", [$id]);

    $patient = $query->getRowArray();

    if (!$patient) {
        return redirect()->to('superadmin/listPatients')->with('error', 'Patient not found.');
    }

    return view('superadmin/viewPatient', ['patient' => $patient]);
}









// -------------------
// APPOINTMENT MANAGEMENT
// -------------------
public function listAppointments()
{
    $db = \Config\Database::connect();

    // Get filter parameters
    $hospitalId = $this->request->getGet('hospital_id');
    $doctorId   = $this->request->getGet('doctor_id');
    $status     = $this->request->getGet('status');

    // Base query joining appointments with related tables
    $query = "
        SELECT 
            a.id,
            a.start_datetime,
            a.end_datetime,
            a.status,
            h.name AS hospital_name,
            d_user.name AS doctor_name,
            p_user.name AS patient_name
        FROM appointments a
        JOIN hospitals h ON a.hospital_id = h.id
        JOIN users d_user ON a.doctor_id = d_user.id
        JOIN users p_user ON a.patient_id = p_user.id
        WHERE 1=1
    ";

    $params = [];

    // Apply filters dynamically
    if ($hospitalId) {
        $query .= " AND a.hospital_id = ?";
        $params[] = $hospitalId;
    }

    if ($doctorId) {
        $query .= " AND a.doctor_id = ?";
        $params[] = $doctorId;
    }

    if ($status) {
        $query .= " AND a.status = ?";
        $params[] = $status;
    }

    $query .= " ORDER BY a.start_datetime DESC";

    $appointments = $db->query($query, $params)->getResultArray();

    // Fetch hospitals and doctors for filters
    $hospitalModel = new \App\Models\HospitalModel();
    $userModel = new \App\Models\UserModel();

    $hospitals = $hospitalModel->select('id, name')->findAll();
    $doctors = $userModel->where('role', 'doctor')->select('id, name')->findAll();

    return view('superadmin/listAppointments', [
        'appointments' => $appointments,
        'hospitals' => $hospitals,
        'doctors' => $doctors,
        'selectedHospital' => $hospitalId,
        'selectedDoctor' => $doctorId,
        'selectedStatus' => $status
    ]);
}













    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }





















































    // protected $hospitalModel;
    // protected $userModel;
    // protected $session;
    // protected $patientModel;
    // protected $appointmentModel;

    // public function __construct()
    // {
    //     $this->hospitalModel = new HospitalModel();
    //     $this->userModel = new UserModel();
    //     $this->session = session();
    //     $this->patientModel = new PatientModel();
    //     $this->appointmentModel = new AppointmentModel();

    //     // Check if logged in and role is superadmin
    //     if (!$this->session->get('logged_in') || $this->session->get('role') != 'superadmin') {
    //         redirect()->to('/login')->send();
    //         exit;
    //     }
    // }

    // // Dashboard with counts
    // public function dashboard()
    // {
    //     $data = [
    //         'hospitalCount' => $this->hospitalModel->countAllResults(),
    //         'adminCount' => $this->userModel->where('role', 'admin')->countAllResults()
    //     ];
    //     return view('superadmin/dashboard', $data);
    // }



    // // ---------------------- HOSPITAL CRUD ----------------------
    // public function listHospitals()
    // {
    //     $hospitalModel = new \App\Models\HospitalModel();

    //     $data['hospitals'] = $hospitalModel->where('deleted_at', null)->findAll();
    //     $data['deletedHospitals'] = $hospitalModel->onlyDeleted()->findAll();
    //     return view('superadmin/list_hospitals', $data);
    // }



    // public function addHospital()
    // {
    //     return view('superadmin/add_hospital');
    // }



    // public function saveHospital()
    // {
    //     $hospitalModel = new \App\Models\HospitalModel();
    //     $validation = \Config\Services::validation();
    //     $validation->setRules([
    //         'name' => 'required|min_length[3]',
    //         'address' => 'required',
    //         'contact' => 'required|numeric',
    //         'email' => 'required|valid_email|is_unique[hospitals.email]'
    //     ]);

    //     if (!$validation->withRequest($this->request)->run()) {
    //         return redirect()->back()->withInput()->with('errors', $validation->getErrors());
    //     }
    //     $hospitalModel->save([
    //         'name' => $this->request->getPost('name'),
    //         'address' => $this->request->getPost('address'),
    //         'contact' => $this->request->getPost('contact'),
    //         'email' => $this->request->getPost('email'),
    //         'status' => 'Active'
    //     ]);
    //     return redirect()->to('/superadmin/listHospitals')->with('success', 'Hospital added successfully');
    // }



    // public function editHospital($id)
    // {
    //     $hospitalModel = new \App\Models\HospitalModel();
    //     $data['hospital'] = $hospitalModel->find($id);
    //     if (!$data['hospital']) {
    //         throw new \CodeIgniter\Exceptions\PageNotFoundException('Hospital not found');
    //     }
    //     return view('superadmin/edit_hospital', $data);
    // }



    // public function updateHospital($id)
    // {
    //     $hospitalModel = new \App\Models\HospitalModel();
    //     $hospital = $hospitalModel->find($id);
    //     if (!$hospital) {
    //         throw new \CodeIgniter\Exceptions\PageNotFoundException('Hospital not found');
    //     }
    //     $data = [
    //         'name' => $this->request->getPost('name'),
    //         'address' => $this->request->getPost('address'),
    //         'contact' => $this->request->getPost('contact'),
    //         'email' => $this->request->getPost('email'),
    //         'status' => $this->request->getPost('status'),
    //         'updated_at' => date('Y-m-d H:i:s')
    //     ];
    //     $hospitalModel->update($id, $data);
    //     return redirect()->to('/superadmin/listHospitals')->with('success', 'Hospital updated successfully');
    // }




    // public function deleteHospital($id)
    // {
    //     $hospitalModel = new \App\Models\HospitalModel();
    //     $hospital = $hospitalModel->find($id);
    //     if (!$hospital) {
    //         throw new \CodeIgniter\Exceptions\PageNotFoundException('Hospital not found');
    //     }
    //     // soft delete
    //     $hospitalModel->delete($id);
    //     return redirect()->to('/superadmin/listHospitals')->with('success', 'Hospital deleted successfully');
    // }




    // public function restoreHospital($id)
    // {
    //     $hospitalModel = new \App\Models\HospitalModel();
    //     $hospitalModel->update($id, ['deleted_at' => null]); // restores hospital
    //     return redirect()->to('/superadmin/listHospitals')->with('success', 'Hospital restored successfully!');
    // }




    // public function hospitalProfile($id)
    // {
    //     $hospitalModel = new \App\Models\HospitalModel();
    //     $userModel     = new \App\Models\UserModel();
    //     // Get hospital
    //     $hospital = $hospitalModel->find($id);
    //     if (!$hospital) {
    //         throw new \CodeIgniter\Exceptions\PageNotFoundException('Hospital not found');
    //     }
    //     // ✅ Get all admins linked to this hospital
    //     $admins = $userModel
    //         ->where('role', 'admin')
    //         ->where('hospital_id', $id)
    //         ->findAll();

    //     // You can also fetch other roles if needed (doctors, patients, etc.)
    //     return view('superadmin/hospital_profile', [
    //         'hospital' => $hospital,
    //         'admins'   => $admins
    //     ]);
    // }




    // // ---------------- Admin CRUD ----------------




    // public function listAdmins()
    // {
    //     $data['admins'] = $this->userModel->where('role', 'admin')->findAll();
    //     return view('superadmin/list_admins', $data);
    // }

    // public function editAdmin($id)
    // {
    //     $data['admin'] = $this->userModel->find($id);
    //     $data['hospitals'] = $this->hospitalModel->findAll();
    //     return view('superadmin/edit_admin', $data);
    // }

    // public function updateAdmin($id)
    // {
    //     $data = [
    //         'name' => $this->request->getPost('name'),
    //         'email' => $this->request->getPost('email'),
    //         'hospital_id' => $this->request->getPost('hospital_id')
    //     ];

    //     if ($this->request->getPost('password')) {
    //         $data['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
    //     }

    //     $this->userModel->update($id, $data);
    //     return redirect()->to('/superadmin/listAdmins')->with('success', 'Admin updated successfully');
    // }

    // public function deleteAdmin($id)
    // {
    //     $this->userModel->delete($id);
    //     return redirect()->to('/superadmin/listAdmins')->with('success', 'Admin deleted successfully');
    // }


    // // public function adminProfile($admin_id)
    // // {
    // //     $db = \Config\Database::connect();

    // //     $admin = $db->table('admins')
    // //         ->select('admins.id, users.name, users.email, users.role, hospitals.name as hospital_name, admins.created_at')
    // //         ->join('users', 'users.id = admins.user_id')
    // //         ->join('hospitals', 'hospitals.id = admins.hospital_id', 'left') // LEFT JOIN to avoid null
    // //         ->where('admins.user_id', $admin_id)
    // //         ->where('admins.deleted_at', null)
    // //         ->get()
    // //         ->getRowArray();

    // //     if (!$admin) {
    // //         throw new \CodeIgniter\Exceptions\PageNotFoundException('Admin not found');
    // //     }

    // //     return view('superadmin/admin_profile', [
    // //         'admin' => $admin
    // //     ]);
    // // }

    // public function adminProfiles($id)
    // {
    //     $userModel = new \App\Models\UserModel();
    //     $hospitalModel = new \App\Models\HospitalModel();

    //     // Fetch the admin
    //     $admin = $userModel->find($id);
    //     if (!$admin || $admin['role'] !== 'admin') {
    //         return redirect()->back()->with('error', 'Admin not found.');
    //     }

    //     // Fetch the hospital info for this admin
    //     $hospital = $hospitalModel->find($admin['hospital_id']);

    //     return view('superadmin/admin_profiles', [
    //         'admin' => $admin,
    //         'hospital' => $hospital
    //     ]);
    // }



    // public function addAdmin()
    // {
    //     $hospitals = $this->hospitalModel->getActiveHospitals();
    //     return view('superadmin/add_admin', ['hospitals' => $hospitals]);
    // }

    // // POST handler
    // public function addAdminPost()
    // {
    //     $data = $this->request->getPost();

    //     // Check if email already exists for the hospital
    //     $existingUser = $this->userModel
    //         ->where('email', $data['email'])
    //         ->where('hospital_id', $data['hospital_id'])
    //         ->first();

    //     if ($existingUser) {
    //         return redirect()->back()->with('error', 'This email is already registered for this hospital.');
    //     }

    //     // Save user
    //     $userData = [
    //         'name' => $data['name'],
    //         'email' => $data['email'],
    //         'password' => password_hash($data['password'], PASSWORD_DEFAULT),
    //         'role' => 'admin',
    //         'hospital_id' => $data['hospital_id']
    //     ];

    //     $this->userModel->save($userData);
    //     $userId = $this->userModel->getInsertID();

    //     // Save in admins table
    //     $adminModel = new \App\Models\AdminModel();
    //     $adminData = [
    //         'user_id' => $userId,
    //         'hospital_id' => $data['hospital_id'],
    //         'created_by' => session()->get('user_id')
    //     ];
    //     $adminModel->insert($adminData);

    //     return redirect()->to('/superadmin/dashboard')->with('success', 'Admin added successfully!');
    // }






    // // ------------------ List Doctors ------------------
    // public function listDoctors($hospital_id)
    // {
    //     $hospitalModel = new \App\Models\HospitalModel();
    //     $doctorModel = new \App\Models\DoctorModel();
    //     $userModel = new \App\Models\UserModel();

    //     $hospital = $hospitalModel->find($hospital_id);
    //     if (!$hospital) throw new \CodeIgniter\Exceptions\PageNotFoundException('Hospital not found');

    //     $doctorsRaw = $doctorModel->where('hospital_id', $hospital_id)->findAll();

    //     $doctors = [];
    //     foreach ($doctorsRaw as $d) {
    //         $user = $userModel->find($d['user_id']);
    //         $d['name'] = $user['name'];
    //         $d['email'] = $user['email'];
    //         $doctors[] = $d;
    //     }

    //     return view('superadmin/list_doctors', [
    //         'hospital' => $hospital,
    //         'doctors' => $doctors
    //     ]);
    // }


    // // ------------------ Add Doctor Form ------------------
    // public function addDoctor($hospital_id)
    // {
    //     helper('form');
    //     return view('superadmin/add_doctor', ['hospital_id' => $hospital_id]);
    // }

    // // ------------------ Save Doctor ------------------
    // public function saveDoctor($hospital_id)
    // {
    //     $validation = \Config\Services::validation();
    //     $validation->setRules([
    //         'name' => 'required|min_length[3]',
    //         'email' => 'required|valid_email|is_unique[users.email]',
    //         'password' => 'required|min_length[6]',
    //         'age' => 'required|numeric',
    //         'gender' => 'required',
    //         'expertise' => 'required',
    //         'availability' => 'required'
    //     ]);

    //     if (!$validation->withRequest($this->request)->run()) {
    //         return redirect()->back()->withInput()->with('errors', $validation->getErrors());
    //     }

    //     $userModel = new UserModel();
    //     $doctorModel = new DoctorModel();

    //     // 1️⃣ Create user
    //     $user_id = $userModel->insert([
    //         'name' => $this->request->getPost('name'),
    //         'email' => $this->request->getPost('email'),
    //         'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
    //         'role' => 'doctor',
    //         'hospital_id' => $hospital_id
    //     ]);

    //     // 2️⃣ Create doctor details
    //     $doctorModel->save([
    //         'user_id' => $user_id,
    //         'hospital_id' => $hospital_id,
    //         'age' => $this->request->getPost('age'),
    //         'gender' => $this->request->getPost('gender'),
    //         'expertise' => $this->request->getPost('expertise'),
    //         'availability' => $this->request->getPost('availability'),
    //         'created_by' => session()->get('superadmin_id') ?? null
    //     ]);

    //     return redirect()->to(site_url('superadmin/listDoctors/' . $hospital_id))->with('success', 'Doctor added successfully');
    // }

    // // ------------------ Edit Doctor Form ------------------
    // public function editDoctor($doctor_id)
    // {
    //     helper('form');
    //     $doctorModel = new DoctorModel();
    //     $doctor = $doctorModel->find($doctor_id);
    //     if (!$doctor) throw new \CodeIgniter\Exceptions\PageNotFoundException('Doctor not found');

    //     $userModel = new UserModel();
    //     $user = $userModel->find($doctor['user_id']);

    //     return view('superadmin/edit_doctor', ['doctor' => $doctor, 'user' => $user]);
    // }

    // // ------------------ Update Doctor ------------------
    // public function updateDoctor($doctor_id)
    // {
    //     $doctorModel = new DoctorModel();
    //     $doctor = $doctorModel->find($doctor_id);
    //     if (!$doctor) throw new \CodeIgniter\Exceptions\PageNotFoundException('Doctor not found');

    //     $userModel = new UserModel();

    //     // Update user
    //     $userModel->update($doctor['user_id'], [
    //         'name' => $this->request->getPost('name'),
    //         'email' => $this->request->getPost('email')
    //     ]);

    //     // Update doctor
    //     $doctorModel->update($doctor_id, [
    //         'age' => $this->request->getPost('age'),
    //         'gender' => $this->request->getPost('gender'),
    //         'expertise' => $this->request->getPost('expertise'),
    //         'availability' => $this->request->getPost('availability'),
    //         'updated_by' => session()->get('superadmin_id') ?? null
    //     ]);

    //     return redirect()->to(site_url('superadmin/listDoctors/' . $doctor['hospital_id']))->with('success', 'Doctor updated successfully');
    // }

    // public function doctorProfile($doctor_id)
    // {
    //     $appointmentModel = new \App\Models\AppointmentModel();
    //     $doctorModel = new \App\Models\DoctorModel();

    //     // Fetch doctor info with user details
    //     $doctor = $doctorModel
    //         ->select('doctors.*, users.name, users.email')
    //         ->join('users', 'users.id = doctors.user_id')
    //         ->where('doctors.id', $doctor_id)
    //         ->first();

    //     if (!$doctor) {
    //         return redirect()->back()->with('error', 'Doctor not found.');
    //     }

    //     // Fetch pending appointments with patient info
    //     $pendingAppointments = $appointmentModel
    //         ->select('appointments.*, patients.id as patient_id, users.name as patient_name, users.email as patient_email')
    //         ->join('patients', 'patients.id = appointments.patient_id')
    //         ->join('users', 'users.id = patients.user_id')
    //         ->where('appointments.doctor_id', $doctor_id)
    //         ->where('appointments.status', 'pending')
    //         ->orderBy('appointments.start_datetime', 'ASC')
    //         ->findAll();

    //     // Similarly for completed and cancelled appointments
    //     // Completed appointments
    //     $completedAppointments = $appointmentModel
    //         ->select('appointments.*, patients.id as patient_id, users.name as patient_name, users.email as patient_email, 
    //     (CASE WHEN prescriptions.id IS NOT NULL THEN 1 ELSE 0 END) as prescription_exists')
    //         ->join('patients', 'patients.id = appointments.patient_id')
    //         ->join('users', 'users.id = patients.user_id')
    //         ->join('prescriptions', 'prescriptions.appointment_id = appointments.id', 'left')
    //         ->where('appointments.doctor_id', $doctor_id)
    //         ->where('appointments.status', 'completed')
    //         ->orderBy('appointments.start_datetime', 'DESC')
    //         ->findAll();




    //     $cancelledAppointments = $appointmentModel
    //         ->select('appointments.*, patients.id as patient_id, users.name as patient_name, users.email as patient_email')
    //         ->join('patients', 'patients.id = appointments.patient_id')
    //         ->join('users', 'users.id = patients.user_id')
    //         ->where('appointments.doctor_id', $doctor_id)
    //         ->where('appointments.status', 'cancelled')
    //         ->orderBy('appointments.start_datetime', 'DESC')
    //         ->findAll();

    //     return view('superadmin/doctor_profile', [
    //         'doctor' => $doctor,
    //         'pendingAppointments' => $pendingAppointments,
    //         'completedAppointments' => $completedAppointments,
    //         'cancelledAppointments' => $cancelledAppointments
    //     ]);
    // }









    // // SuperAdminController.php
    // // Add Appointment Form
    // public function addAppointment($doctor_id)
    // {
    //     $doctorModel = new \App\Models\DoctorModel();
    //     $userModel   = new \App\Models\UserModel();
    //     $patientModel = new \App\Models\PatientModel();

    //     // Get doctor info
    //     $doctor = $doctorModel->select('doctors.*, users.name, users.email')
    //         ->join('users', 'users.id = doctors.user_id')
    //         ->where('doctors.id', $doctor_id)
    //         ->where('availability','yes')
    //         ->first();

    //     // Get patients of the same hospital
    //     $patients = $patientModel->select('patients.*, users.id as user_id, users.name')
    //         ->join('users', 'users.id = patients.user_id')
    //         ->where('patients.hospital_id', $doctor['hospital_id'])
    //         ->where('patients.deleted_at', null)
    //         ->findAll();

    //     return view('superadmin/add_appointment', [
    //         'doctor' => $doctor,
    //         'patients' => $patients
    //     ]);
    // }




    // // Save Appointment
    // public function storeAppointment()
    // {
    //     $appointmentModel = new \App\Models\AppointmentModel();

    //     $data = [
    //         'doctor_id'   => $this->request->getPost('doctor_id'),
    //         'patient_id'  => $this->request->getPost('patient_id'),
    //         'start_datetime' => $this->request->getPost('start_datetime'),
    //         'end_datetime'   => $this->request->getPost('end_datetime'),
    //         'status'      => 'pending',
    //         'created_by'  => session()->get('user_id'), // optional
    //         'created_at'  => date('Y-m-d H:i:s'),
    //     ];

    //     // Check if doctor already has an appointment at the same time
    //     $conflict = $appointmentModel
    //         ->where('doctor_id', $data['doctor_id'])
    //         ->where('status', 'pending')
    //         ->where('start_datetime <=', $data['end_datetime'])
    //         ->where('end_datetime >=', $data['start_datetime'])
    //         ->first();

    //     if ($conflict) {
    //         return redirect()->back()->with('error', 'Doctor already has an appointment at this time');
    //     }

    //     $appointmentModel->insert($data);

    //     return redirect()->to(site_url('superadmin/doctorProfile/' . $data['doctor_id']))
    //         ->with('success', 'Appointment created successfully');
    // }


    // // Edit Appointment
    // // Edit Appointment Form
    // public function editAppointment($id)
    // {
    //     $appointmentModel = new \App\Models\AppointmentModel();
    //     $appointment = $appointmentModel->find($id);

    //     if (!$appointment) {
    //         throw new \CodeIgniter\Exceptions\PageNotFoundException('Appointment not found');
    //     }

    //     $doctorModel = new \App\Models\DoctorModel();
    //     $doctor = $doctorModel->find($appointment['doctor_id']);
    //     if (!$doctor) {
    //         throw new \CodeIgniter\Exceptions\PageNotFoundException('Doctor not found');
    //     }

    //     $patientModel = new \App\Models\PatientModel();
    //     $userModel = new \App\Models\UserModel();

    //     // Fetch patients of the same hospital as the doctor
    //     $patients = $patientModel
    //         ->select('patients.id, users.name')
    //         ->join('users', 'users.id = patients.user_id')
    //         ->where('patients.hospital_id', $doctor['hospital_id'])
    //         ->where('patients.deleted_at', null)
    //         ->findAll();

    //     return view('superadmin/edit_appointment', [
    //         'appointment' => $appointment,
    //         'patients' => $patients
    //     ]);
    // }

    // // Update Appointment
    // public function updateAppointment($id)
    // {
    //     $appointmentModel = new \App\Models\AppointmentModel();
    //     $appointment = $appointmentModel->find($id);

    //     if (!$appointment) {
    //         throw new \CodeIgniter\Exceptions\PageNotFoundException('Appointment not found');
    //     }

    //     $data = [
    //         'patient_id' => $this->request->getPost('patient_id'), // must be patients.id
    //         'start_datetime' => $this->request->getPost('start_datetime'),
    //         'end_datetime' => $this->request->getPost('end_datetime'),
    //         'notes' => $this->request->getPost('notes') ?? ''
    //     ];

    //     $appointmentModel->update($id, $data);

    //     // Redirect to doctor profile
    //     $appointment = $appointmentModel->find($id); // refresh to get doctor_id
    //     return redirect()->to(site_url('superadmin/doctorProfile/' . $appointment['doctor_id']))
    //         ->with('success', 'Appointment updated successfully!');
    // }



    // public function markDone($id)
    // {
    //     $appointmentModel = new \App\Models\AppointmentModel();
    //     $appointmentModel->update($id, ['status' => 'completed']);
    //     return redirect()->back()->with('success', 'Appointment marked as done');
    // }

    // public function markAppointmentDone($appointment_id)
    // {
    //     $appointmentModel = new \App\Models\AppointmentModel();

    //     // Fetch appointment
    //     $appointment = $appointmentModel->find($appointment_id);
    //     if (!$appointment) {
    //         return redirect()->back()->with('error', 'Appointment not found.');
    //     }

    //     // Update status to completed
    //     $appointmentModel->update($appointment_id, ['status' => 'completed']);

    //     return redirect()->back()->with('success', 'Appointment marked as completed.');
    // }



    // public function rescheduleAppointment($id)
    // {
    //     helper('form');
    //     $appointmentModel = new \App\Models\AppointmentModel();
    //     $appointment = $appointmentModel->find($id);

    //     if (!$appointment) throw new \CodeIgniter\Exceptions\PageNotFoundException('Appointment not found');

    //     return view('superadmin/reschedule_appointment', ['appointment' => $appointment]);
    // }

    // public function saveReschedule($id)
    // {
    //     $appointmentModel = new \App\Models\AppointmentModel();

    //     // Fetch appointment to get doctor_id
    //     $appointment = $appointmentModel->find($id);

    //     if (!$appointment) {
    //         throw new \CodeIgniter\Exceptions\PageNotFoundException('Appointment not found');
    //     }

    //     // Update start and end datetime
    //     $data = [
    //         'start_datetime' => $this->request->getPost('start_datetime'),
    //         'end_datetime'   => $this->request->getPost('end_datetime'),
    //     ];

    //     $appointmentModel->update($id, $data);

    //     // Redirect to doctor profile page
    //     return redirect()->to(site_url('superadmin/doctorProfile/' . $appointment['doctor_id']))
    //         ->with('success', 'Appointment rescheduled successfully');
    // }





    // public function cancelAppointment($id)
    // {
    //     $appointmentModel = new \App\Models\AppointmentModel();
    //     $appointmentModel->update($id, ['status' => 'cancelled']);
    //     return redirect()->back()->with('success', 'Appointment cancelled successfully');
    // }


    // // Show Add Visit Details form
    // public function addVisitDetails($appointment_id)
    // {
    //     $appointmentModel = new \App\Models\AppointmentModel();
    //     $doctorModel = new \App\Models\DoctorModel();
    //     $patientModel = new \App\Models\PatientModel();
    //     $userModel = new \App\Models\UserModel();

    //     // Fetch appointment
    //     $appointment = $appointmentModel->find($appointment_id);
    //     if (!$appointment) {
    //         return redirect()->back()->with('error', 'Appointment not found.');
    //     }

    //     // Fetch doctor
    //     $doctor = $doctorModel->find($appointment['doctor_id']);
    //     if (!$doctor) {
    //         return redirect()->back()->with('error', 'Doctor not found.');
    //     }

    //     // Fetch patient from patients table
    //     $patient = $patientModel->find($appointment['patient_id']);
    //     if (!$patient) {
    //         return redirect()->back()->with('error', 'Patient not found.');
    //     }

    //     // Fetch patient’s user record to show name/email
    //     $patientUser = $userModel->find($patient['user_id']);

    //     $data = [
    //         'appointment' => $appointment,
    //         'doctor' => $doctor,
    //         'patient' => $patient,
    //         'patient_user' => $patientUser,
    //     ];

    //     return view('superadmin/add_visit_details', $data);
    // }




    // // Save Visit Details and mark appointment as DONE
    // public function saveVisitDetails($appointment_id)
    // {
    //     $appointmentModel = new \App\Models\AppointmentModel();
    //     $visitModel = new \App\Models\VisitHistoryModel();
    //     $patientModel = new \App\Models\PatientModel();

    //     // Fetch appointment
    //     $appointment = $appointmentModel->find($appointment_id);
    //     if (!$appointment) {
    //         return redirect()->back()->with('error', 'Appointment not found.');
    //     }

    //     // Fetch patient from patients table

    //     $patient = $this->patientModel->where('id', $appointment['patient_id'])->first();
    //     if (!$patient) {
    //         return redirect()->back()->with('error', 'Patient not found.');
    //     }

    //     // Prepare visit data (use patients.id)
    //     $visitData = [
    //         'appointment_id'  => $appointment_id,
    //         'patient_id'      => $patient['id'],   // ✅ use patients.id
    //         'doctor_id'       => $appointment['doctor_id'],
    //         'reason'          => $this->request->getPost('reason'),
    //         'weight'          => $this->request->getPost('weight'),
    //         'blood_pressure'  => $this->request->getPost('blood_pressure'),
    //         'doctor_comments' => $this->request->getPost('doctor_comments'),
    //         'created_at'      => date('Y-m-d H:i:s'),
    //         'updated_at'      => date('Y-m-d H:i:s'),
    //     ];

    //     // Save visit record
    //     $visitModel->insert($visitData);

    //     // Update appointment status
    //     $appointmentModel->update($appointment_id, ['status' => 'completed']);

    //     return redirect()->to('superadmin/doctorProfile/' . $appointment['doctor_id'])
    //         ->with('success', 'Visit details added successfully and appointment completed. ');
    // }


    // public function addPrescription($appointment_id)
    // {
    //     $db = \Config\Database::connect();

    //     $appointment = $db->table('appointments')->where('id', $appointment_id)->get()->getRowArray();
    //     if (!$appointment) {
    //         return redirect()->back()->with('error', 'Appointment not found.');
    //     }

    //     $visit = $db->table('visit_history')->where('appointment_id', $appointment_id)->get()->getRowArray();
    //     $doctor = $db->table('doctors')->where('id', $appointment['doctor_id'])->get()->getRowArray();
    //     $patient = $db->table('patients')->where('id', $appointment['patient_id'])->get()->getRowArray();

    //     return view('superadmin/add_prescription', [
    //         'appointment_id' => $appointment_id,
    //         'doctor_id' => $appointment['doctor_id'],
    //         'patient_id' => $appointment['patient_id'],
    //         'visit' => $visit
    //     ]);
    // }



    // public function savePrescription()
    // {
    //     $prescriptionModel = new \App\Models\PrescriptionModel();
    //     $db = \Config\Database::connect();

    //     $appointmentId = $this->request->getPost('appointment_id');
    //     $doctorId = $this->request->getPost('doctor_id');
    //     $patientId = $this->request->getPost('patient_id');
    //     $visitId = $this->request->getPost('visit_id');
    //     $text = $this->request->getPost('prescription_text');

    //     // Ensure we have a visit_id
    //     if (empty($visitId)) {
    //         $visit = $db->table('visit_history')->where('appointment_id', $appointmentId)->get()->getRowArray();
    //         if ($visit) {
    //             $visitId = $visit['id'];
    //         }
    //     }

    //     if (empty($visitId)) {
    //         return redirect()->back()->with('error', 'Visit record not found for this appointment.');
    //     }

    //     // Check if already exists
    //     $existing = $prescriptionModel->where('appointment_id', $appointmentId)->first();

    //     if ($existing) {
    //         $prescriptionModel->update($existing['id'], [
    //             'prescription_text' => $text,
    //             'updated_at' => date('Y-m-d H:i:s')
    //         ]);
    //     } else {
    //         $prescriptionModel->insert([
    //             'appointment_id' => $appointmentId,
    //             'visit_id' => $visitId,
    //             'patient_id' => $patientId,
    //             'doctor_id' => $doctorId,
    //             'prescription_text' => $text,
    //             'created_at' => date('Y-m-d H:i:s')
    //         ]);
    //     }

    //     return redirect()->to(site_url('superadmin/doctorProfile/' . $doctorId))->with('success', 'Prescription saved successfully!');
    // }



    // public function viewPrescription($appointment_id)
    // {
    //     $db = \Config\Database::connect();

    //     $prescription = $db->table('prescriptions')->where('appointment_id', $appointment_id)->get()->getRowArray();
    //     if (!$prescription) {
    //         return redirect()->back()->with('error', 'Prescription not found.');
    //     }

    //     $appointment = $db->table('appointments')->where('id', $appointment_id)->get()->getRowArray();
    //     $doctor = $db->table('doctors')
    //         ->select('doctors.*, users.name as doctor_name, users.email as doctor_email')
    //         ->join('users', 'users.id = doctors.user_id')
    //         ->where('doctors.id', $appointment['doctor_id'])
    //         ->get()->getRowArray();

    //     $patient = $db->table('patients')
    //         ->select('patients.*, users.name as patient_name, users.email as patient_email')
    //         ->join('users', 'users.id = patients.user_id')
    //         ->where('patients.id', $appointment['patient_id'])
    //         ->get()->getRowArray();

    //     return view('superadmin/view_prescription', [
    //         'prescription' => $prescription,
    //         'doctor' => $doctor,
    //         'patient' => $patient,
    //         'appointment' => $appointment
    //     ]);
    // }





    // public function downloadPrescription($prescription_id)
    // {
    //     $prescriptionModel = new \App\Models\PrescriptionModel();
    //     $visitModel = new \App\Models\VisitHistoryModel();
    //     $userModel = new \App\Models\UserModel();
    //     $doctorModel = new \App\Models\DoctorModel();
    //     $patientModel = new \App\Models\PatientModel();

    //     // Fetch prescription
    //     $prescription = $prescriptionModel->find($prescription_id);
    //     if (!$prescription) {
    //         return redirect()->back()->with('error', 'Prescription not found');
    //     }

    //     // Fetch visit
    //     $visit = $visitModel->find($prescription['visit_id']);
    //     if (!$visit) {
    //         return redirect()->back()->with('error', 'Visit not found');
    //     }

    //     // Fetch doctor & patient info
    //     $doctorRecord = (new \App\Models\DoctorModel())->find($visit['doctor_id']);
    //     $doctor = $doctorRecord ? $userModel->find($doctorRecord['user_id']) : null;
    //     if (!$doctor) {
    //         return redirect()->back()->with('error', 'Doctor user not found.');
    //     }


    //     // Fetch patient user
    //     $patientRecord = (new \App\Models\PatientModel())->find($visit['patient_id']);
    //     $patient = $userModel->find($patientRecord['user_id']);
    //     // Prepare HTML
    //     $html = "
    // <h2>Prescription</h2>
    // <p><strong>Patient Name:</strong> " . esc($patient['name']) . "</p>
    // <p><strong>Doctor Name:</strong> " . esc($doctor['name']) . "</p>
    // <p><strong>Appointment ID:</strong> " . esc($visit['appointment_id']) . "</p>
    // <hr>
    // <h4>Visit Details</h4>
    // <p>Reason: " . esc($visit['reason']) . "</p>
    // <p>BP: " . esc($visit['blood_pressure']) . "</p>
    // <p>Weight: " . esc($visit['weight']) . "</p>
    // <p>Doctor Comments: " . esc($visit['doctor_comments']) . "</p>
    // <hr>
    // <h4>Prescription</h4>
    // <p>" . nl2br(esc($prescription['prescription_text'])) . "</p>
    // ";

    //     // Initialize Dompdf
    //     $options = new Options();
    //     $options->set('isRemoteEnabled', true);
    //     $dompdf = new Dompdf($options);
    //     $dompdf->loadHtml($html);
    //     $dompdf->setPaper('A4', 'portrait');
    //     $dompdf->render();

    //     // Output as PDF
    //     $filename = "Prescription_Appointment_" . $visit['appointment_id'] . ".pdf";
    //     $dompdf->stream($filename, ['Attachment' => true]); // Force download
    // }





    // public function patientProfile($user_id, $doctor_id = null)
    // {
    //     $patientModel = new \App\Models\PatientModel();
    //     $visitModel = new \App\Models\VisitHistoryModel();
    //     $userModel = new \App\Models\UserModel();

    //     // Fetch patient info (join with users)
    //     $patient = $patientModel
    //         ->select('patients.*, users.name, users.email, users.id as user_id')
    //         ->join('users', 'users.id = patients.user_id')
    //         ->where('patients.id', $user_id)
    //         ->first();
    //     $patientId = $patient['id'] ?? null;

    //     if (!$patient) {
    //         return redirect()->back()->with('error', 'Patient not found');
    //     }

    //     // Fetch visit history with doctor names
    //     $visits = $visitModel
    //         ->select('visit_history.*, doctors.id as doctor_id, doctor_users.name as doctor_name, 
    //           prescriptions.id as prescription_id, prescriptions.prescription_text, 
    //           appointments.id as appointment_id')
    //         ->join('doctors', 'doctors.id = visit_history.doctor_id')
    //         ->join('users as doctor_users', 'doctor_users.id = doctors.user_id')
    //         ->join('appointments', 'appointments.id = visit_history.appointment_id')
    //         ->join('prescriptions', 'prescriptions.visit_id = visit_history.id', 'left')
    //         ->where('visit_history.patient_id', $patientId)
    //         ->get()->getResultArray();


    //     return view('superadmin/patient_profile', [
    //         'patient' => $patient,
    //         'visits' => $visits,
    //         'doctor_id' => $doctor_id
    //     ]);
    // }
























    // public function viewAdmin($id)
    // {
    //     $admin = $this->userModel->where('id', $id)->where('role', 'admin')->first();

    //     if (!$admin) {
    //         throw new \CodeIgniter\Exceptions\PageNotFoundException("Admin not found");
    //     }

    //     // Optionally fetch the hospital name
    //     $hospitalModel = new \App\Models\HospitalModel();
    //     $hospital = $hospitalModel->find($admin['hospital_id']);

    //     $data = [
    //         'title' => 'Admin Profile',
    //         'admin' => $admin,
    //         'hospital' => $hospital
    //     ];

    //     return view('superadmin/view_admin', $data);
    // }















    // // List Patients for a Hospital
    // public function listPatients($hospital_id)
    // {
    //     $hospitalModel = new \App\Models\HospitalModel();
    //     $patientModel = new \App\Models\PatientModel();
    //     $userModel = new \App\Models\UserModel();

    //     $hospital = $hospitalModel->find($hospital_id);
    //     if (!$hospital) throw new \CodeIgniter\Exceptions\PageNotFoundException('Hospital not found');

    //     $patientsRaw = $patientModel->where('hospital_id', $hospital_id)->findAll();
    //     $patients = [];

    //     foreach ($patientsRaw as $p) {
    //         $user = $userModel->find($p['user_id']);
    //         $p['name'] = $user['name'] ?? '';
    //         $p['email'] = $user['email'] ?? '';
    //         $patients[] = $p;
    //     }

    //     return view('superadmin/list_patients', [
    //         'hospital' => $hospital,
    //         'patients' => $patients
    //     ]);
    // }

    // // Show Add Patient Form
    // public function addPatient($hospital_id)
    // {
    //     return view('superadmin/add_patient', ['hospital_id' => $hospital_id]);
    // }

    // // Save Patient
    // public function savePatient($hospital_id)
    // {
    //     $validation = \Config\Services::validation();
    //     $validation->setRules([
    //         'name' => 'required|min_length[3]',
    //         'email' => 'required|valid_email|is_unique[users.email]',
    //         'password' => 'required|min_length[6]',
    //         'age' => 'required|numeric',
    //         'gender' => 'required'
    //     ]);

    //     if (!$validation->withRequest($this->request)->run()) {
    //         return redirect()->back()->withInput()->with('errors', $validation->getErrors());
    //     }

    //     $userModel = new \App\Models\UserModel();
    //     $patientModel = new \App\Models\PatientModel();

    //     // 1️⃣ Insert user
    //     $user_id = $userModel->insert([
    //         'name' => $this->request->getPost('name'),
    //         'email' => $this->request->getPost('email'),
    //         'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
    //         'role' => 'patient',
    //         'hospital_id' => $hospital_id
    //     ]);

    //     // 2️⃣ Insert patient
    //     $patientModel->insert([
    //         'user_id' => $user_id,
    //         'hospital_id' => $hospital_id,
    //         'age' => $this->request->getPost('age'),
    //         'gender' => $this->request->getPost('gender'),
    //         'created_by' => session()->get('superadmin_id') ?? null
    //     ]);

    //     return redirect()->to(site_url('superadmin/listPatients/' . $hospital_id))
    //         ->with('success', 'Patient added successfully');
    // }

    // // Edit Patient Form
    // public function editPatient($patient_id)
    // {
    //     $patientModel = new \App\Models\PatientModel();
    //     $userModel = new \App\Models\UserModel();

    //     $patient = $patientModel->find($patient_id);
    //     if (!$patient) throw new \CodeIgniter\Exceptions\PageNotFoundException('Patient not found');

    //     $user = $userModel->find($patient['user_id']);

    //     return view('superadmin/edit_patient', [
    //         'patient' => $patient,
    //         'user' => $user
    //     ]);
    // }

    // // Update Patient
    // public function updatePatient($patient_id)
    // {
    //     $patientModel = new \App\Models\PatientModel();
    //     $userModel = new \App\Models\UserModel();

    //     $patient = $patientModel->find($patient_id);
    //     if (!$patient) throw new \CodeIgniter\Exceptions\PageNotFoundException('Patient not found');

    //     // Update user
    //     $userData = [
    //         'name' => $this->request->getPost('name'),
    //         'email' => $this->request->getPost('email')
    //     ];
    //     if ($this->request->getPost('password')) {
    //         $userData['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
    //     }
    //     $userModel->update($patient['user_id'], $userData);

    //     // Update patient
    //     $patientModel->update($patient_id, [
    //         'age' => $this->request->getPost('age'),
    //         'gender' => $this->request->getPost('gender'),
    //         'updated_by' => session()->get('superadmin_id') ?? null
    //     ]);

    //     return redirect()->to(site_url('superadmin/listPatients/' . $patient['hospital_id']))
    //         ->with('success', 'Patient updated successfully');
    // }

    // // Delete Patient
    // public function deletePatient($patient_id)
    // {
    //     $patientModel = new \App\Models\PatientModel();
    //     $patient = $patientModel->find($patient_id);
    //     if (!$patient) throw new \CodeIgniter\Exceptions\PageNotFoundException('Patient not found');

    //     $patientModel->delete($patient_id);
    //     return redirect()->to(site_url('superadmin/listPatients/' . $patient['hospital_id']))
    //         ->with('success', 'Patient deleted successfully');
    // }

    // public function managePatients($hospital_id)
    // {
    //     $hospitalModel = new \App\Models\HospitalModel();
    //     $patientModel = new \App\Models\PatientModel();
    //     $userModel = new \App\Models\UserModel();

    //     $hospital = $hospitalModel->find($hospital_id);
    //     if (!$hospital) throw new \CodeIgniter\Exceptions\PageNotFoundException('Hospital not found');

    //     $patientsRaw = $patientModel->where('hospital_id', $hospital_id)->findAll();

    //     $patients = [];
    //     foreach ($patientsRaw as $p) {
    //         $user = $userModel->find($p['user_id']);
    //         $p['name'] = $user['name'];
    //         $p['email'] = $user['email'];
    //         $patients[] = $p;
    //     }

    //     return view('superadmin/manage_patients', [
    //         'hospital' => $hospital,
    //         'patients' => $patients
    //     ]);
    // }


    // public function manageAppointments($hospital_id)
    // {
    //     $hospitalModel = new \App\Models\HospitalModel();
    //     $appointmentModel = new \App\Models\AppointmentModel();
    //     $doctorModel = new \App\Models\DoctorModel();
    //     $userModel = new \App\Models\UserModel();
    //     $patientModel = new \App\Models\PatientModel();

    //     $hospital = $hospitalModel->find($hospital_id);
    //     if (!$hospital) throw new \CodeIgniter\Exceptions\PageNotFoundException('Hospital not found');

    //     // Get all doctors of this hospital
    //     $doctors = $doctorModel->where('hospital_id', $hospital_id)->findAll();
    //     $doctor_ids = array_column($doctors, 'id');

    //     // Initialize arrays
    //     $pendingAppointments = [];
    //     $completedAppointments = [];
    //     $cancelledAppointments = [];

    //     if (!empty($doctor_ids)) {
    //         // Pending
    //         $pendingRaw = $appointmentModel
    //             ->whereIn('doctor_id', $doctor_ids)
    //             ->where('status', 'pending')
    //             ->orderBy('start_datetime', 'ASC')
    //             ->findAll();

    //         // Completed
    //         $completedRaw = $appointmentModel
    //             ->whereIn('doctor_id', $doctor_ids)
    //             ->where('status', 'completed')
    //             ->orderBy('start_datetime', 'DESC')
    //             ->findAll();

    //         // Cancelled
    //         $cancelledRaw = $appointmentModel
    //             ->whereIn('doctor_id', $doctor_ids)
    //             ->where('status', 'cancelled')
    //             ->orderBy('start_datetime', 'DESC')
    //             ->findAll();

    //         // Function to attach doctor & patient info
    //         $enrichAppointments = function ($appointments) use ($doctorModel, $patientModel, $userModel) {
    //             $result = [];
    //             foreach ($appointments as $a) {
    //                 // Doctor
    //                 $doctorRecord = $doctorModel->find($a['doctor_id']);
    //                 $doctor = $doctorRecord ? $userModel->find($doctorRecord['user_id']) : null;

    //                 // Patient
    //                 $patientRecord = $patientModel->find($a['patient_id']);
    //                 $patient = $patientRecord ? $userModel->find($patientRecord['user_id']) : null;

    //                 $a['doctor_name'] = $doctor['name'] ?? 'N/A';
    //                 $a['patient_name'] = $patient['name'] ?? 'N/A';
    //                 $a['patient_email'] = $patient['email'] ?? '';
    //                 $result[] = $a;
    //             }
    //             return $result;
    //         };


    //         $pendingAppointments = $enrichAppointments($pendingRaw);
    //         $completedAppointments = $enrichAppointments($completedRaw);
    //         $cancelledAppointments = $enrichAppointments($cancelledRaw);
    //     }

    //     return view('superadmin/manage_appointments', [
    //         'hospital' => $hospital,
    //         'pendingAppointments' => $pendingAppointments,
    //         'completedAppointments' => $completedAppointments,
    //         'cancelledAppointments' => $cancelledAppointments
    //     ]);
    // }



    // public function addHospitalAppointment($hospital_id)
    // {
    //     $hospitalModel = new \App\Models\HospitalModel();
    //     $doctorModel = new \App\Models\DoctorModel();
    //     $patientModel = new \App\Models\PatientModel();
    //     $userModel = new \App\Models\UserModel();

    //     $hospital = $hospitalModel->find($hospital_id);
    //     if (!$hospital) throw new \CodeIgniter\Exceptions\PageNotFoundException('Hospital not found');

    //     // Get all doctors and patients of this hospital
    //     $doctorsRaw = $doctorModel->where('hospital_id', $hospital_id)->findAll();
    //     $doctors = [];
    //     foreach ($doctorsRaw as $d) {
    //         $user = $userModel->find($d['user_id']);
    //         $d['name'] = $user['name'];
    //         $doctors[] = $d;
    //     }

    //     $patientsRaw = $patientModel->where('hospital_id', $hospital_id)->findAll();
    //     $patients = [];
    //     foreach ($patientsRaw as $p) {
    //         $user = $userModel->find($p['user_id']);
    //         $p['name'] = $user['name'];
    //         $patients[] = $p;
    //     }

    //     return view('superadmin/add_hospital_appointment', [
    //         'hospital' => $hospital,
    //         'doctors' => $doctors,
    //         'patients' => $patients
    //     ]);
    // }
}
