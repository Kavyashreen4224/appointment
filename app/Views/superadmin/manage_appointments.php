<?php helper('form'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Appointments</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">

    <h3>Appointments for <?= esc($hospital['name']) ?></h3>
    <a href="<?= site_url('superadmin/hospitalProfile/'.$hospital['id']) ?>" class="btn btn-secondary mb-3">Back to Hospital Profile</a>
    <a href="<?= site_url('superadmin/addHospitalAppointment/'.$hospital['id']) ?>" class="btn btn-success mb-3">Add Appointment</a>

    <!-- Tabs -->
    <ul class="nav nav-tabs" id="appointmentTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button">Pending</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed" type="button">Completed</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="cancelled-tab" data-bs-toggle="tab" data-bs-target="#cancelled" type="button">Cancelled</button>
        </li>
    </ul>

    <div class="tab-content mt-3" id="appointmentTabsContent">

        <!-- Pending Tab -->
        <div class="tab-pane fade show active" id="pending">
            <?php if (!empty($pendingAppointments)): ?>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Doctor</th>
                        <th>Patient</th>
                        <th>Email</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($pendingAppointments as $a): ?>
                    <tr>
                        <td><?= esc($a['id']) ?></td>
                        <td><?= esc($a['doctor_name']) ?></td>
                        <td>
                            <a href="<?= site_url('superadmin/patientProfile/'.$a['patient_id']) ?>">
                                <?= esc($a['patient_name']) ?>
                            </a>
                        </td>
                        <td><?= esc($a['patient_email']) ?></td>
                        <td><?= esc($a['start_datetime']) ?></td>
                        <td><?= esc($a['end_datetime']) ?></td>
                        <td>
                            <a href="<?= site_url('superadmin/editAppointment/'.$a['id']) ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="<?= site_url('superadmin/cancelAppointment/'.$a['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Cancel this appointment?')">Cancel</a>
                            <a href="<?= site_url('superadmin/addVisitDetails/'.$a['id']) ?>" class="btn btn-success btn-sm">Done</a>
                            <a href="<?= site_url('superadmin/rescheduleAppointment/'.$a['id']) ?>" class="btn btn-info btn-sm">Reschedule</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
                <p>No pending appointments.</p>
            <?php endif; ?>
        </div>

        <!-- Completed Tab -->
        <div class="tab-pane fade" id="completed">
            <?php if (!empty($completedAppointments)): ?>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Doctor</th>
                        <th>Patient</th>
                        <th>Email</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($completedAppointments as $a): ?>
                    <tr>
                        <td><?= esc($a['id']) ?></td>
                        <td><?= esc($a['doctor_name']) ?></td>
                        <td>
                            <a href="<?= site_url('superadmin/patientProfile/'.$a['patient_id']) ?>">
                                <?= esc($a['patient_name']) ?>
                            </a>
                        </td>
                        <td><?= esc($a['patient_email']) ?></td>
                        <td><?= esc($a['start_datetime']) ?></td>
                        <td><?= esc($a['end_datetime']) ?></td>
                        <td><span class="badge bg-success"><?= esc($a['status']) ?></span></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
                <p>No completed appointments.</p>
            <?php endif; ?>
        </div>

        <!-- Cancelled Tab -->
        <div class="tab-pane fade" id="cancelled">
            <?php if (!empty($cancelledAppointments)): ?>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Doctor</th>
                        <th>Patient</th>
                        <th>Email</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($cancelledAppointments as $a): ?>
                    <tr>
                        <td><?= esc($a['id']) ?></td>
                        <td><?= esc($a['doctor_name']) ?></td>
                        <td>
                            <a href="<?= site_url('superadmin/patientProfile/'.$a['patient_id']) ?>">
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

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
