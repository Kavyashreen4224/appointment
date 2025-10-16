<?php helper('form'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Appointment</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">

    <h3>Add Appointment - <?= esc($hospital['name']) ?></h3>
    <a href="<?= site_url('superadmin/manageAppointments/'.$hospital['id']) ?>" class="btn btn-secondary mb-3">Back to Appointments</a>

    <?php if(session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger">
            <?php foreach(session()->getFlashdata('errors') as $err) echo $err.'<br>'; ?>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= site_url('superadmin/storeHospitalAppointment') ?>">
        <input type="hidden" name="hospital_id" value="<?= esc($hospital['id']) ?>">

        <div class="mb-3">
            <label>Doctor</label>
            <select name="doctor_id" class="form-control" required>
                <option value="">Select Doctor</option>
                <?php foreach($doctors as $d): ?>
                    <option value="<?= esc($d['id']) ?>"><?= esc($d['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Patient</label>
            <select name="patient_id" class="form-control" required>
                <option value="">Select Patient</option>
                <?php foreach($patients as $p): ?>
                    <option value="<?= esc($p['user_id']) ?>"><?= esc($p['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Start Date & Time</label>
            <input type="datetime-local" name="start_datetime" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>End Date & Time</label>
            <input type="datetime-local" name="end_datetime" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Add Appointment</button>
    </form>

</div>
</body>
</html>
