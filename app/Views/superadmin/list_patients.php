<?php helper('form'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Patients List</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">

    <h3>Patients in <?= esc($hospital['name']) ?></h3>

    <a href="<?= site_url('superadmin/hospitalProfile/'.$hospital['id']) ?>" class="btn btn-secondary mb-3">Back to Hospital Profile</a>
    <a href="<?= site_url('superadmin/addPatient/'.$hospital['id']) ?>" class="btn btn-primary mb-3">Add Patient</a>

    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <?php if(!empty($patients)): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Age</th>
                    <th>Gender</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($patients as $p): ?>
                    <tr>
                        <td>
                            <a href="<?= site_url('superadmin/patientProfile/'.$p['id'].'/'.$p['hospital_id']) ?>">
                                <?= esc($p['name']) ?>
                            </a>
                        </td>
                        <td><?= esc($p['email']) ?></td>
                        <td><?= esc($p['age']) ?></td>
                        <td><?= esc($p['gender']) ?></td>
                        <td>
                            <a href="<?= site_url('superadmin/editPatient/'.$p['id']) ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="<?= site_url('superadmin/deletePatient/'.$p['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No patients found.</p>
    <?php endif; ?>

</div>
</body>
</html>
