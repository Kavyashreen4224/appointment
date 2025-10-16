<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Doctors List</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h3>Doctors in Hospital: <?= esc($hospital['name']) ?></h3>
    <a href="<?= site_url('superadmin/addDoctor/'.$hospital['id']) ?>" class="btn btn-success mb-3">Add Doctor</a>
    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Age</th>
                <th>Gender</th>
                <th>Expertise</th>
                <th>Availability</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($doctors)): ?>
                <?php foreach($doctors as $d): ?>
                    <tr>
                        <td><?= $d['id'] ?></td>
                        <td>
                            <a href="<?= site_url('superadmin/doctorProfile/'.$d['id']) ?>">
                                <?= esc($d['name']) ?>
                            </a>
                        </td>
                        <td><?= esc($d['age']) ?></td>
                        <td><?= esc($d['gender']) ?></td>
                        <td><?= esc($d['expertise']) ?></td>
                        <td><?= esc($d['availability']) ?></td>
                        <td>
                            <a href="<?= site_url('superadmin/editDoctor/'.$d['id']) ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="<?= site_url('superadmin/deleteDoctor/'.$d['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="7" class="text-center">No doctors found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    <a href="<?= site_url('superadmin/hospitalProfile/'.$hospital['id']) ?>" class="btn btn-secondary mt-2">Back to Hospital Profile</a>
</div>
</body>
</html>
