<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'AuthController::landing');
$routes->get('register', 'AuthController::register');
$routes->post('auth/registerPost', 'AuthController::registerPost');
$routes->get('login', 'AuthController::login');
$routes->post('auth/loginPost', 'AuthController::loginPost');
$routes->get('logout', 'AuthController::logout');


$routes->get('superadmin/dashboard', 'SuperAdminController::dashboard');
$routes->get('superadmin/addHospital', 'SuperAdminController::addHospital');
$routes->post('superadmin/saveHospital', 'SuperAdminController::saveHospital');
$routes->get('superadmin/addAdmin/(:num)', 'SuperAdminController::addAdmin/$1');
$routes->post('superadmin/saveAdmin/(:num)', 'SuperAdminController::saveAdmin/$1');
$routes->get('superadmin/viewAdmin/(:num)', 'SuperAdminController::viewAdmin/$1');
$routes->get('superadmin/listAdmins', 'SuperAdminController::listAdmins');
$routes->get('superadmin/editAdmin/(:num)', 'SuperAdminController::editAdmin/$1');
$routes->post('superadmin/updateAdmin/(:num)', 'SuperAdminController::updateAdmin/$1');
$routes->get('superadmin/deleteAdmin/(:num)', 'SuperAdminController::deleteAdmin/$1');
 $routes->get('superadmin/addAdmin', 'SuperAdminController::addAdmin');        // Show add admin form
$routes->post('superadmin/addAdminPost', 'SuperAdminController::addAdminPost');


// SuperAdmin → Hospitals CRUD
$routes->get('superadmin/listHospitals', 'SuperAdminController::listHospitals');
$routes->get('superadmin/addHospital', 'SuperAdminController::addHospital');
$routes->post('superadmin/saveHospital', 'SuperAdminController::saveHospital');
$routes->get('superadmin/editHospital/(:num)', 'SuperAdminController::editHospital/$1');
$routes->post('superadmin/updateHospital/(:num)', 'SuperAdminController::updateHospital/$1');
$routes->get('superadmin/deleteHospital/(:num)', 'SuperAdminController::deleteHospital/$1');
$routes->get('superadmin/hospitalProfile/(:num)', 'SuperadminController::hospitalProfile/$1');
$routes->get('superadmin/restoreHospital/(:num)', 'SuperAdminController::restoreHospital/$1');


// Doctors management per hospital
$routes->get('superadmin/listDoctors/(:num)', 'SuperadminController::listDoctors/$1');
$routes->get('superadmin/addDoctor/(:num)', 'SuperadminController::addDoctor/$1');
$routes->post('superadmin/saveDoctor/(:num)', 'SuperadminController::saveDoctor/$1');
$routes->get('superadmin/editDoctor/(:num)', 'SuperadminController::editDoctor/$1');
$routes->post('superadmin/updateDoctor/(:num)', 'SuperadminController::updateDoctor/$1');
$routes->get('superadmin/deleteDoctor/(:num)', 'SuperadminController::deleteDoctor/$1');
$routes->get('superadmin/doctorProfile/(:num)', 'SuperAdminController::doctorProfile/$1');

$routes->get('superadmin/adminProfiles/(:num)', 'SuperAdminController::adminProfiles/$1');



// Appointments CRUD


$routes->get('superadmin/addAppointment/(:num)', 'SuperadminController::addAppointment/$1'); // show form
$routes->post('superadmin/storeAppointment', 'SuperadminController::storeAppointment');    // handle form submit
$routes->get('superadmin/editAppointment/(:num)', 'SuperadminController::editAppointment/$1');
$routes->post('superadmin/updateAppointment/(:num)', 'SuperadminController::updateAppointment/$1');
$routes->get('superadmin/cancelAppointment/(:num)', 'SuperadminController::cancelAppointment/$1');
$routes->get('superadmin/markAppointmentDone/(:num)', 'SuperadminController::markAppointmentDone/$1');
$routes->get('superadmin/rescheduleAppointment/(:num)', 'SuperadminController::rescheduleAppointment/$1');
$routes->post('superadmin/saveReschedule/(:num)', 'SuperadminController::saveReschedule/$1');
$routes->get('superadmin/patientProfile/(:num)/(:num)', 'SuperAdminController::patientProfile/$1/$2');
$routes->get('superadmin/addVisitDetails/(:num)', 'SuperadminController::addVisitDetails/$1');
$routes->post('superadmin/saveVisitDetails/(:num)', 'SuperadminController::saveVisitDetails/$1');

// Completed appointments and prescriptions

$routes->get('superadmin/addPrescription/(:num)', 'SuperAdminController::addPrescription/$1');
$routes->post('superadmin/savePrescription', 'SuperAdminController::savePrescription');
$routes->get('superadmin/viewPrescription/(:num)', 'SuperAdminController::viewPrescription/$1');
$routes->get('superadmin/downloadPrescription/(:num)', 'SuperAdminController::downloadPrescription/$1');




// Patient profile with visit history

$routes->get('superadmin/patientProfile/(:num)', 'SuperadminController::patientProfile/$1');
$routes->get('superadmin/managePatients/(:num)', 'SuperAdminController::managePatients/$1');
$routes->get('superadmin/manageAppointments/(:num)', 'SuperAdminController::manageAppointments/$1');
$routes->get('superadmin/addHospitalAppointment/(:num)', 'SuperAdminController::addHospitalAppointment/$1');
$routes->post('superadmin/storeHospitalAppointment', 'SuperAdminController::storeAppointment');

// Manage patients under a hospital
$routes->get('superadmin/listPatients/(:num)', 'SuperAdminController::listPatients/$1');
$routes->get('superadmin/addPatient/(:num)', 'SuperAdminController::addPatient/$1');
$routes->post('superadmin/savePatient/(:num)', 'SuperAdminController::savePatient/$1');
$routes->get('superadmin/editPatient/(:num)', 'SuperAdminController::editPatient/$1');
$routes->post('superadmin/updatePatient/(:num)', 'SuperAdminController::updatePatient/$1');
$routes->get('superadmin/deletePatient/(:num)', 'SuperAdminController::deletePatient/$1');






$routes->group('admin', ['namespace' => 'App\Controllers'], function($routes){
    $routes->get('dashboard', 'AdminController::dashboard');

    // Doctors
    $routes->get('listDoctors', 'AdminController::listDoctors');
    $routes->match(['get','post'],'addDoctor', 'AdminController::addDoctor');
    $routes->match(['get','post'],'editDoctor/(:num)', 'AdminController::editDoctor/$1');
    $routes->get('deleteDoctor/(:num)', 'AdminController::deleteDoctor/$1');

    // Patients
    $routes->get('listPatients', 'AdminController::listPatients');
    $routes->match(['get','post'],'addPatient', 'AdminController::addPatient');
    $routes->match(['get','post'],'editPatient/(:num)', 'AdminController::editPatient/$1');
    $routes->get('deletePatient/(:num)', 'AdminController::deletePatient/$1');

    // Appointments
    $routes->get('listAppointments', 'AdminController::listAppointments');
    $routes->match(['get','post'],'addAppointment', 'AdminController::addAppointment');
    $routes->match(['get','post'],'editAppointment/(:num)', 'AdminController::editAppointment/$1');
    $routes->get('deleteAppointment/(:num)', 'AdminController::deleteAppointment/$1');
});










// Doctor routes
$routes->group('doctor', function($routes) {
    $routes->get('dashboard', 'DoctorController::dashboard');
    $routes->get('patients', 'DoctorController::patients');
    $routes->get('addPatient', 'DoctorController::addPatient');
    $routes->post('savePatient', 'DoctorController::savePatient');
    $routes->get('editPatient/(:num)', 'DoctorController::editPatient/$1');
    $routes->post('updatePatient/(:num)', 'DoctorController::updatePatient/$1');
    $routes->get('appointments', 'DoctorController::appointments');
    $routes->get('appointment/(:num)', 'DoctorController::appointment/$1');
     $routes->get('viewAppointment/(:num)', 'DoctorController::viewAppointment/$1');
    $routes->post('addVisit/(:num)', 'DoctorController::addVisit/$1');

});





// $routes->group('patient', ['namespace' => 'App\Controllers'], function($routes) {
//     $routes->get('dashboard', 'PatientController::dashboard');
//     $routes->get('bookAppointment', 'PatientController::bookAppointment');
//     $routes->post('bookAppointmentPost', 'PatientController::bookAppointmentPost');
//     $routes->get('getDoctorsByHospital/(:num)', 'PatientController::getDoctorsByHospital/$1');

// });

$routes->get('visit-history', 'VisitHistoryController::index');
$routes->get('visit-history/getTrendData', 'VisitHistoryController::getTrendData');


$routes->group('patient', function($routes) {
    $routes->get('dashboard', 'PatientController::dashboard');
    $routes->get('downloadPrescription/(:num)', 'PatientController::downloadPrescription/$1');
});


