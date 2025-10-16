<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Admin Dashboard</h2>

    <a href="<?= site_url('admin/addDoctor') ?>" class="btn btn-primary mb-2">Add Doctor</a>
    <a href="<?= site_url('admin/addPatient') ?>" class="btn btn-primary mb-2">Add Patient</a>
    <a href="<?= site_url('admin/addAppointment') ?>" class="btn btn-primary mb-2">Add Appointment</a>

    <hr>

    <!-- Doctors Table -->
    <h4>Doctors</h4>
    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
        <?php foreach($doctors as $doc): ?>
        <tr>
            <td><?= $doc['id'] ?></td>
            <td><?= $doc['name'] ?></td>
            <td><?= $doc['email'] ?></td>
            <td>
                <a href="<?= site_url('admin/editDoctor/'.$doc['id']) ?>" class="btn btn-warning btn-sm">Edit</a>
                <a href="<?= site_url('admin/deleteDoctor/'.$doc['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this doctor?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <!-- Patients Table -->
    <h4>Patients</h4>
    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
        <?php foreach($patients as $pat): ?>
        <tr>
            <td><?= $pat['id'] ?></td>
            <td><?= $pat['name'] ?></td>
            <td><?= $pat['email'] ?></td>
            <td>
                <a href="<?= site_url('admin/editPatient/'.$pat['id']) ?>" class="btn btn-warning btn-sm">Edit</a>
                <a href="<?= site_url('admin/deletePatient/'.$pat['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this patient?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <!-- Appointments Table -->
    <h3>Appointments</h3>
<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>Doctor</th>
        <th>Patient</th>
        <th>Date</th>
        <th>Status</th>
        <th>Actions</th>
    </tr>
    <?php foreach($appointments as $app): ?>
    <tr>
        <td><?= $app['id'] ?></td>
        <td>
            <?php 
            $doc = array_filter($doctors, fn($d) => $d['id'] == $app['doctor_id']); 
            echo array_values($doc)[0]['name'] ?? 'N/A';
            ?>
        </td>
        <td>
            <?php 
            $pat = array_filter($patients, fn($p) => $p['id'] == $app['patient_id']); 
            echo array_values($pat)[0]['name'] ?? 'N/A';
            ?>
        </td>
        <td><?= date('d-m-Y H:i', strtotime($app['start_datetime'])) ?></td>
        <td><?= ucfirst($app['status']) ?></td>
        <td>
            <a href="<?= site_url('admin/editAppointment/'.$app['id']) ?>" class="btn btn-warning btn-sm">Edit</a>
            <a href="<?= site_url('admin/deleteAppointment/'.$app['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this appointment?')">Delete</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

</div>
</body>
</html>
