<?php helper('form'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Patient</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">

    <h3>Edit Patient</h3>
    <a href="<?= site_url('superadmin/listPatients/'.$patient['hospital_id']) ?>" class="btn btn-secondary mb-3">Back to Patients List</a>

    <!-- Validation Errors -->
    <?php if(session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach(session()->getFlashdata('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?= site_url('superadmin/updatePatient/'.$patient['id']) ?>" method="post">
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="<?= set_value('name', $user['name']) ?>">
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?= set_value('email', $user['email']) ?>">
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password <small>(Leave blank to keep current password)</small></label>
            <input type="password" class="form-control" id="password" name="password">
        </div>

        <div class="mb-3">
            <label for="age" class="form-label">Age</label>
            <input type="number" class="form-control" id="age" name="age" value="<?= set_value('age', $patient['age']) ?>">
        </div>

        <div class="mb-3">
            <label for="gender" class="form-label">Gender</label>
            <select name="gender" id="gender" class="form-select">
                <option value="">Select Gender</option>
                <option value="Male" <?= set_select('gender','Male',$patient['gender']=='Male') ?>>Male</option>
                <option value="Female" <?= set_select('gender','Female',$patient['gender']=='Female') ?>>Female</option>
                <option value="Other" <?= set_select('gender','Other',$patient['gender']=='Other') ?>>Other</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update Patient</button>
    </form>

</div>
</body>
</html>
