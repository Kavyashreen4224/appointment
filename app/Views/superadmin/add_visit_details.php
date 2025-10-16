<?php helper('form'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Visit Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">

    <!-- Page Heading -->
    <h3>
        Add Visit Details for 
        <?= isset($patient_user['name']) ? esc($patient_user['name']) : 'Unknown Patient' ?>
    </h3>

    <!-- Back Button -->
    <a href="<?= !empty($doctor_id) ? site_url('superadmin/doctorProfile/'.$doctor_id) : '#' ?>" 
       class="btn btn-secondary mb-3">Back to Doctor Profile</a>

    <!-- Patient Info Summary -->
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title mb-3">Patient Information</h5>
            <p><strong>Name:</strong> <?= esc($patient_user['name']) ?></p>
            <p><strong>Email:</strong> <?= esc($patient_user['email']) ?></p>
            <p><strong>Gender:</strong> <?= esc($patient['gender']) ?></p>
            <p><strong>Age:</strong> <?= esc($patient['age']) ?></p>
        </div>
    </div>

    <!-- Visit Details Form -->
    <div class="card mb-3">
        <div class="card-body">
            <?= \Config\Services::validation()->listErrors() ?>

            <?= form_open('superadmin/saveVisitDetails/' . $appointment['id']) ?>

            <div class="mb-3">
                <label for="reason" class="form-label">Reason for Visit</label>
                <textarea name="reason" class="form-control" required><?= esc($visit['reason'] ?? '') ?></textarea>
            </div>

            <div class="mb-3">
                <label for="blood_pressure" class="form-label">Blood Pressure</label>
                <input type="text" name="blood_pressure" class="form-control" 
                       value="<?= esc($visit['blood_pressure'] ?? '') ?>" required>
            </div>

            <div class="mb-3">
                <label for="weight" class="form-label">Weight (kg)</label>
                <input type="number" step="0.01" name="weight" class="form-control" 
                       value="<?= esc($visit['weight'] ?? '') ?>" required>
            </div>

            <div class="mb-3">
                <label for="doctor_comments" class="form-label">Doctor Comments</label>
                <textarea name="doctor_comments" class="form-control"><?= esc($visit['doctor_comments'] ?? '') ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary">
                <?= !empty($visit) ? 'Update' : 'Save' ?>
            </button>

            <?= form_close() ?>
        </div>
    </div>

</div>
</body>
</html>
