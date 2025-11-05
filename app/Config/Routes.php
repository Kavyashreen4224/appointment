<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */



$routes->get('/', 'AuthController::landing');

// ================== AUTH ROUTES ==================
$routes->get('register', 'AuthController::register');                     // Registration form
$routes->post('registerPost', 'AuthController::registerPost');       // Registration submit
// Authentication
$routes->get('login', 'AuthController::login');
$routes->post('auth/loginPost', 'AuthController::loginPost');
$routes->get('logout', 'AuthController::logout');

// SuperAdmin Routes
// SuperAdmin Routes
$routes->group('superadmin', function ($routes) {
    // Dashboard
    $routes->get('dashboard', 'SuperAdminController::dashboard');
    $routes->get('listHospitals', 'SuperAdminController::listHospitals');
    $routes->get('addHospital', 'SuperAdminController::addHospital');
    $routes->post('saveHospital', 'SuperAdminController::saveHospital');
    $routes->get('editHospital/(:num)', 'SuperAdminController::editHospital/$1');
    $routes->post('updateHospital/(:num)', 'SuperAdminController::updateHospital/$1');
    $routes->get('deleteHospital/(:num)', 'SuperAdminController::deleteHospital/$1');


    // Admin Management
    $routes->get('listAdmins', 'SuperAdminController::listAdmins');
    $routes->get('addAdmin', 'SuperAdminController::addAdmin');
    $routes->post('saveAdmin', 'SuperAdminController::saveAdmin');
    $routes->get('editAdmin/(:num)', 'SuperAdminController::editAdmin/$1');
    $routes->post('updateAdmin/(:num)', 'SuperAdminController::updateAdmin/$1');
    $routes->get('deleteAdmin/(:num)', 'SuperAdminController::deleteAdmin/$1');
    $routes->get('listDoctors', 'SuperAdminController::listDoctors');
    $routes->get('viewDoctor/(:num)', 'SuperAdminController::viewDoctor/$1');


    // ================= SuperAdmin: Patients Management =================
    $routes->get('listPatients', 'SuperAdminController::listPatients');
    $routes->get('patientProfile/(:num)', 'SuperAdminController::patientProfile/$1');
    $routes->get('addPatient', 'Superadmin::addPatient');
    $routes->post('addPatient', 'Superadmin::addPatient');
    $routes->get('editPatient/(:num)', 'SuperAdminController::editPatient/$1');
    $routes->post('updatePatient/(:num)', 'SuperAdminController::updatePatient/$1');
    $routes->get('deletePatient/(:num)', 'SuperAdminController::deletePatient/$1');
    $routes->get('listAppointments', 'SuperAdminController::listAppointments');
});





// ----------------------
// ADMIN DOCTORS ROUTES
// ----------------------
$routes->group('admin', function ($routes) {
    
    // Dashboard
    $routes->get('dashboard', 'AdminController::dashboard');

    // Doctor Management
    $routes->get('listDoctors', 'AdminController::listDoctors');
    $routes->get('addDoctor', 'AdminController::addDoctor');
    $routes->post('saveDoctor', 'AdminController::saveDoctor');
    $routes->get('editDoctor/(:num)', 'AdminController::editDoctor/$1');
    $routes->post('updateDoctor/(:num)', 'AdminController::updateDoctor/$1');
    $routes->get('deleteDoctor/(:num)', 'AdminController::deleteDoctor/$1');
    $routes->get('viewDoctor/(:num)', 'AdminController::viewDoctor/$1');
    $routes->get('viewPatient/(:num)', 'AdminController::viewPatient/$1');

    // 🏥 Admin - Patients CRUD
$routes->get('listPatients', 'AdminController::listPatients');
$routes->get('addPatient', 'AdminController::addPatient');
$routes->post('addPatientPost', 'AdminController::addPatientPost');
$routes->get('editPatient/(:num)', 'AdminController::editPatient/$1');
$routes->post('updatePatient/(:num)', 'AdminController::updatePatient/$1');
$routes->get('deletePatient/(:num)', 'AdminController::deletePatient/$1');
$routes->get('viewPatient/(:num)', 'AdminController::viewPatient/$1'); // already existing
$routes->get('listAppointments', 'AdminController::listAppointments');

});




$routes->group('doctor', function($routes) {
    $routes->get('dashboard', 'DoctorController::dashboard');
    $routes->get('patients', 'DoctorController::patientsList');
    $routes->get('addPatient', 'DoctorController::addPatient');
    $routes->post('savePatient', 'DoctorController::savePatient');
    $routes->get('editPatient/(:num)', 'DoctorController::editPatient/$1');
    $routes->post('updatePatient/(:num)', 'DoctorController::updatePatient/$1');
    $routes->get('deletePatient/(:num)', 'DoctorController::deletePatient/$1');


    // Doctor Appointments Routes
$routes->get('appointments', 'DoctorController::appointments');
$routes->get('addAppointment', 'DoctorController::addAppointment');
$routes->post('saveAppointment', 'DoctorController::saveAppointment');
$routes->get('rescheduleAppointment/(:num)', 'DoctorController::rescheduleAppointment/$1');
$routes->post('updateAppointment/(:num)', 'DoctorController::updateAppointment/$1');
$routes->get('cancelAppointment/(:num)', 'DoctorController::cancelAppointment/$1');

$routes->get('markDone/(:num)', 'DoctorController::markAsDone/$1');
$routes->get('addVisit/(:num)', 'DoctorController::addVisit/$1');
$routes->post('saveVisit', 'DoctorController::saveVisit');
$routes->get('viewVisit/(:num)', 'DoctorController::viewVisit/$1');
// Doctor Prescription Routes
$routes->get('addPrescription/(:num)', 'DoctorController::addPrescription/$1'); // visit_id
$routes->post('savePrescription', 'DoctorController::savePrescription');
$routes->get('viewPrescription/(:num)', 'DoctorController::viewPrescription/$1'); // visit_id

$routes->get('addBill/(:num)', 'DoctorController::addBill/$1');
$routes->post('saveBill', 'DoctorController::saveBill');
$routes->get('viewBill/(:num)', 'DoctorController::viewBill/$1');
$routes->post('markBillPaid/(:num)', 'DoctorController::markBillPaid/$1');

// Doctor Service Management
$routes->get('services', 'DoctorController::services');
$routes->get('addService', 'DoctorController::addService');
$routes->post('saveService', 'DoctorController::saveService');
$routes->get('editService/(:num)', 'DoctorController::editService/$1');
$routes->post('updateService/(:num)', 'DoctorController::updateService/$1');
$routes->get('deleteService/(:num)', 'DoctorController::deleteService/$1');

$routes->get('profile', 'DoctorController::profile');
$routes->post('updateProfile', 'DoctorController::updateProfile');


});














$routes->group('patient', function ($routes) {
    $routes->get('dashboard', 'PatientController::dashboard');
    $routes->get('appointments', 'PatientController::appointments');
    // ✅ Patient Views for completed appointments
$routes->get('viewVisit/(:num)', 'PatientController::viewVisit/$1');
$routes->get('viewPrescription/(:num)', 'PatientController::viewPrescription/$1');
$routes->get('viewBill/(:num)', 'PatientController::viewBill/$1');

     $routes->get('bookAppointment', 'PatientController::bookAppointment');
    $routes->post('saveAppointment', 'PatientController::saveAppointment');
    $routes->get('cancelAppointment/(:num)', 'PatientController::cancelAppointment/$1');

    $routes->get('visitHistory', 'PatientController::visitHistory');


});














// $routes->get('/', 'AuthController::landing');
// $routes->get('register', 'AuthController::register');
// $routes->post('auth/registerPost', 'AuthController::registerPost');
// $routes->get('login', 'AuthController::login');
// $routes->post('auth/loginPost', 'AuthController::loginPost');
// $routes->get('logout', 'AuthController::logout');



// $routes->get('superadmin/dashboard', 'SuperAdminController::dashboard');
// $routes->get('superadmin/addHospital', 'SuperAdminController::addHospital');
// $routes->post('superadmin/saveHospital', 'SuperAdminController::saveHospital');
// $routes->get('superadmin/addAdmin/(:num)', 'SuperAdminController::addAdmin/$1');
// $routes->post('superadmin/saveAdmin/(:num)', 'SuperAdminController::saveAdmin/$1');
// $routes->get('superadmin/viewAdmin/(:num)', 'SuperAdminController::viewAdmin/$1');
// $routes->get('superadmin/listAdmins', 'SuperAdminController::listAdmins');
// $routes->get('superadmin/editAdmin/(:num)', 'SuperAdminController::editAdmin/$1');
// $routes->post('superadmin/updateAdmin/(:num)', 'SuperAdminController::updateAdmin/$1');
// $routes->get('superadmin/deleteAdmin/(:num)', 'SuperAdminController::deleteAdmin/$1');
// $routes->get('superadmin/addAdmin', 'SuperAdminController::addAdmin');        // Show add admin form
// $routes->post('superadmin/addAdminPost', 'SuperAdminController::addAdminPost');


// // SuperAdmin → Hospitals CRUD
// $routes->get('superadmin/listHospitals', 'SuperAdminController::listHospitals');
// $routes->get('superadmin/addHospital', 'SuperAdminController::addHospital');
// $routes->post('superadmin/saveHospital', 'SuperAdminController::saveHospital');
// $routes->get('superadmin/editHospital/(:num)', 'SuperAdminController::editHospital/$1');
// $routes->post('superadmin/updateHospital/(:num)', 'SuperAdminController::updateHospital/$1');
// $routes->get('superadmin/deleteHospital/(:num)', 'SuperAdminController::deleteHospital/$1');
// $routes->get('superadmin/hospitalProfile/(:num)', 'SuperadminController::hospitalProfile/$1');
// $routes->get('superadmin/restoreHospital/(:num)', 'SuperAdminController::restoreHospital/$1');


// // Doctors management per hospital
// $routes->get('superadmin/listDoctors/(:num)', 'SuperadminController::listDoctors/$1');
// $routes->get('superadmin/addDoctor/(:num)', 'SuperadminController::addDoctor/$1');
// $routes->post('superadmin/saveDoctor/(:num)', 'SuperadminController::saveDoctor/$1');
// $routes->get('superadmin/editDoctor/(:num)', 'SuperadminController::editDoctor/$1');
// $routes->post('superadmin/updateDoctor/(:num)', 'SuperadminController::updateDoctor/$1');
// $routes->get('superadmin/deleteDoctor/(:num)', 'SuperadminController::deleteDoctor/$1');
// $routes->get('superadmin/doctorProfile/(:num)', 'SuperAdminController::doctorProfile/$1');

// $routes->get('superadmin/adminProfiles/(:num)', 'SuperAdminController::adminProfiles/$1');



// // Appointments CRUD


// $routes->get('superadmin/addAppointment/(:num)', 'SuperadminController::addAppointment/$1'); // show form
// $routes->post('superadmin/storeAppointment', 'SuperadminController::storeAppointment');    // handle form submit
// $routes->get('superadmin/editAppointment/(:num)', 'SuperadminController::editAppointment/$1');
// $routes->post('superadmin/updateAppointment/(:num)', 'SuperadminController::updateAppointment/$1');
// $routes->get('superadmin/cancelAppointment/(:num)', 'SuperadminController::cancelAppointment/$1');
// $routes->get('superadmin/markAppointmentDone/(:num)', 'SuperadminController::markAppointmentDone/$1');
// $routes->get('superadmin/rescheduleAppointment/(:num)', 'SuperadminController::rescheduleAppointment/$1');
// $routes->post('superadmin/saveReschedule/(:num)', 'SuperadminController::saveReschedule/$1');
// $routes->get('superadmin/patientProfile/(:num)/(:num)', 'SuperAdminController::patientProfile/$1/$2');
// $routes->get('superadmin/addVisitDetails/(:num)', 'SuperadminController::addVisitDetails/$1');
// $routes->post('superadmin/saveVisitDetails/(:num)', 'SuperadminController::saveVisitDetails/$1');

// // Completed appointments and prescriptions

// $routes->get('superadmin/addPrescription/(:num)', 'SuperAdminController::addPrescription/$1');
// $routes->post('superadmin/savePrescription', 'SuperAdminController::savePrescription');
// $routes->get('superadmin/viewPrescription/(:num)', 'SuperAdminController::viewPrescription/$1');
// $routes->get('superadmin/downloadPrescription/(:num)', 'SuperAdminController::downloadPrescription/$1');


// // Patient profile with visit history

// $routes->get('superadmin/patientProfile/(:num)', 'SuperadminController::patientProfile/$1');
// $routes->get('superadmin/managePatients/(:num)', 'SuperAdminController::managePatients/$1');
// $routes->get('superadmin/manageAppointments/(:num)', 'SuperAdminController::manageAppointments/$1');
// $routes->get('superadmin/addHospitalAppointment/(:num)', 'SuperAdminController::addHospitalAppointment/$1');
// $routes->post('superadmin/storeHospitalAppointment', 'SuperAdminController::storeAppointment');

// // Manage patients under a hospital
// $routes->get('superadmin/listPatients/(:num)', 'SuperAdminController::listPatients/$1');
// $routes->get('superadmin/addPatient/(:num)', 'SuperAdminController::addPatient/$1');
// $routes->post('superadmin/savePatient/(:num)', 'SuperAdminController::savePatient/$1');
// $routes->get('superadmin/editPatient/(:num)', 'SuperAdminController::editPatient/$1');
// $routes->post('superadmin/updatePatient/(:num)', 'SuperAdminController::updatePatient/$1');
// $routes->get('superadmin/deletePatient/(:num)', 'SuperAdminController::deletePatient/$1');






// $routes->group('admin', function ($routes) {
//     $routes->get('dashboard', 'AdminController::dashboard');
//     $routes->get('patients', 'AdminController::patients');
//     $routes->get('patient/(:num)', 'AdminController::patient/$1');
//     $routes->get('doctors', 'AdminController::doctors');
//     $routes->get('appointments', 'AdminController::appointments');
//     $routes->get('viewPrescription/(:num)', 'AdminController::viewPrescription/$1');

//     $routes->get('patientProfile/(:num)', 'AdminController::patientProfile/$1');                 // List all doctors
//     $routes->get('doctorProfile/(:num)', 'AdminController::doctorProfile/$1'); // View single doctor


//     $routes->get('appointments', 'AdminController::appointments');           // List all appointments
//     $routes->get('viewAppointment/(:num)', 'AdminController::viewAppointment/$1'); // View single appointment


// });









// // Doctor routes
// $routes->group('doctor', function ($routes) {
//     $routes->get('dashboard', 'DoctorController::dashboard');
//     $routes->get('patients', 'DoctorController::patients');
//     $routes->get('patient/(:num)', 'DoctorController::patientProfile/$1');

//     $routes->get('addPatient', 'DoctorController::addPatient');
//     $routes->post('savePatient', 'DoctorController::savePatient');
//     $routes->get('editPatient/(:num)', 'DoctorController::editPatient/$1');
//     $routes->post('updatePatient/(:num)', 'DoctorController::updatePatient/$1');
//     $routes->get('appointments', 'DoctorController::appointments');
//     $routes->get('appointment/(:num)', 'DoctorController::appointment/$1');
//     $routes->get('viewAppointment/(:num)', 'DoctorController::viewAppointment/$1');
//     $routes->post('addVisit/(:num)', 'DoctorController::addVisit/$1');

//     $routes->get('addPrescription/(:num)', 'DoctorController::addPrescription/$1'); // appointment_id
//     $routes->post('savePrescription', 'DoctorController::savePrescription');
//     $routes->get('viewPrescription/(:num)', 'DoctorController::viewPrescription/$1'); // prescription_id


//     $routes->get('appointments', 'DoctorController::appointments');
//     $routes->get('addAppointment', 'DoctorController::addAppointment'); // form
//     $routes->post('saveAppointment', 'DoctorController::saveAppointment'); // submit

//     // Doctor Billing Routes
//     $routes->get('addBill/(:num)', 'DoctorController::addBill/$1');
//     $routes->post('saveBill', 'DoctorController::saveBill');
//     $routes->get('viewBill/(:num)', 'DoctorController::viewBill/$1');

//     $routes->get('markDone/(:num)', 'DoctorController::markDone/$1');
//     $routes->get('cancelAppointment/(:num)', 'DoctorController::cancelAppointment/$1');
//     $routes->get('reschedule/(:num)', 'DoctorController::reschedule/$1');

//     $routes->post('saveVisit', 'DoctorController::saveVisit');

//     $routes->post('updatePaymentStatus/(:num)', 'DoctorController::updatePaymentStatus/$1');

//     $routes->get('editBill/(:num)', 'DoctorController::editBill/$1');
//     $routes->post('updateBill/(:num)', 'DoctorController::updateBill/$1');
//     $routes->get('downloadBill/(:num)', 'DoctorController::downloadBill/$1');
// });






// $routes->group('patient', function ($routes) {
//     $routes->get('dashboard', 'PatientController::dashboard');
//     $routes->get('downloadPrescription/(:num)', 'PatientController::downloadPrescription/$1');

//     $routes->get('bookAppointment', 'PatientController::bookAppointment');
//     $routes->post('saveAppointment', 'PatientController::saveAppointment');
//     $routes->get('viewBill/(:num)', 'PatientController::viewBill/$1');
//     $routes->get('upcomingAppointments', 'PatientController::upcomingAppointments');
//     $routes->get('cancelAppointment/(:num)', 'PatientController::cancelAppointment/$1');

//     $routes->get('rescheduleAppointment/(:num)', 'PatientController::rescheduleAppointment/$1');
//     $routes->post('updateReschedule/(:num)', 'PatientController::updateReschedule/$1');


//     $routes->get('completedAppointments', 'PatientController::completedAppointments');
// });
