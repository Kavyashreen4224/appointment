<?php helper('form'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Doctor Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-4">

        <h3>Doctor Profile</h3>

        <!-- Flash Messages -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <a href="<?= site_url('superadmin/listDoctors/' . $doctor['hospital_id']) ?>" class="btn btn-secondary mb-3">Back to Doctors List</a>

        <!-- Doctor Info -->
        <div class="card mb-4">
            <div class="card-body">
                <h4 class="card-title"><?= esc($doctor['name']) ?></h4>
                <p><strong>Email:</strong> <?= esc($doctor['email']) ?></p>
                <p><strong>Age:</strong> <?= esc($doctor['age']) ?></p>
                <p><strong>Gender:</strong> <?= esc($doctor['gender']) ?></p>
                <p><strong>Expertise:</strong> <?= esc($doctor['expertise']) ?></p>
                <p><strong>Availability:</strong> <?= esc($doctor['availability']) ?></p>
            </div>
        </div>

        <!-- Add Appointment Button -->
        <a href="<?= site_url('superadmin/addAppointment/' . $doctor['id']) ?>" class="btn btn-primary mb-3">Add Appointment</a>

        <!-- Appointments Table -->
        <h4>Pending Appointments</h4>
        <?php if (!empty($pendingAppointments)): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Patient</th>
                        <th>email</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pendingAppointments as $a): ?>
                        <tr>
                            <td><?= esc($a['id']) ?></td>
                            <td>
                                <a href="<?= site_url('superadmin/patientProfile/' . $a['patient_id'] . '/' . $doctor['id']) ?>">
                                    <?= esc($a['patient_name']) ?>
                                </a>
                            </td>
                            <td><?= esc($a['patient_email']) ?></td>

                            <td><?= esc($a['start_datetime']) ?></td>
                            <td><?= esc($a['end_datetime']) ?></td>
                            <td>
                                <a href="<?= site_url('superadmin/editAppointment/' . $a['id']) ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="<?= site_url('superadmin/addVisitDetails/' . $a['id']) ?>"
                                    class="btn btn-success btn-sm">Done</a>

                                <a href="<?= site_url('superadmin/cancelAppointment/' . $a['id']) ?>" class="btn btn-danger btn-sm">Cancel</a>
                                <a href="<?= site_url('superadmin/rescheduleAppointment/'.$a['id']) ?>" class="btn btn-info btn-sm">Reschedule</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No pending appointments.</p>
        <?php endif; ?>

        <h4>Completed Appointments</h4>
        <?php if (!empty($completedAppointments)): ?>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Patient</th>
                        <th>email</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>action</th>

                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($completedAppointments as $a): ?>
                        <tr>
                            <td><?= esc($a['id']) ?></td>
                            <td>
                                <a href="<?= site_url('superadmin/patientProfile/'.$a['patient_id']) ?>">
                                    <?= esc($a['patient_name']) ?>
                                </a>
                            </td>
                            <td><?= esc($a['patient_email']) ?></td>
                            <td><?= esc($a['start_datetime']) ?></td>
                            <td><?= esc($a['end_datetime']) ?></td>
                      <td>
    <?php if ($a['prescription_exists'] ?? false): ?>
        <a href="<?= site_url('superadmin/viewPrescription/'.$a['id']) ?>" class="btn btn-sm btn-success">View Prescription</a>
    <?php else: ?>
        <a href="<?= site_url('superadmin/addPrescription/'.$a['id']) ?>" class="btn btn-sm btn-primary">Add Prescription</a>
    <?php endif; ?>
</td>




                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No completed appointments yet.</p>
        <?php endif; ?>


        <h4>Cancelled Appointments</h4>
        <?php if (!empty($cancelledAppointments)): ?>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Patient</th>
                        <th>email</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cancelledAppointments as $a): ?>
                        <tr>
                            <td><?= esc($a['id']) ?></td>
                            <td>
                                <a href="<?= site_url('superadmin/patientProfile/' . $a['patient_id'] . '/' . $doctor['id']) ?>">
                                    <?= esc($a['patient_name']) ?>
                                </a>
                            </td>
                            <td><?= esc($a['patient_email']) ?></td>

                            <td><?= esc($a['start_datetime']) ?></td>
                            <td><?= esc($a['end_datetime']) ?></td>
                            <td><span class="badge bg-danger"><?= esc($a['status']) ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No cancelled appointments.</p>
        <?php endif; ?>

    </div>
</body>

</html>