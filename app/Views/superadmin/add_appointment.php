<!DOCTYPE html>
<html>
<head>
  <title>Add Appointment</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
  <h3>Add Appointment for Dr. <?= esc($doctor['expertise']) ?></h3>
  <form action="<?= site_url('superadmin/storeAppointment') ?>" method="post">
    <input type="hidden" name="doctor_id" value="<?= esc($doctor['id']) ?>">
    <div class="mb-3">
        <label>Patient</label>
        <select name="patient_id" class="form-control" required>
    <?php foreach($patients as $p): ?>
        <option value="<?= esc($p['id']) ?>"><?= esc($p['name']) ?></option>
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
    <button type="submit" class="btn btn-primary">Create Appointment</button>
    <a href="<?= site_url('superadmin/doctorProfile/'.$doctor['id']) ?>" class="btn btn-secondary">Cancel</a>
</form>

</div>
</body>
</html>
