<!DOCTYPE html>
<html>
<head>
  <title>Edit Appointment</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
  <h3>Edit Appointment #<?= $appointment['id'] ?></h3>
  <form action="<?= site_url('superadmin/updateAppointment/'.$appointment['id']) ?>" method="post">
    <div class="form-group">
        <label for="patient_id">Patient</label>
        <select name="patient_id" id="patient_id" class="form-control" required>
            <?php foreach($patients as $p): ?>
                <option value="<?= $p['id'] ?>" <?= $p['id'] == $appointment['patient_id'] ? 'selected' : '' ?>>
                    <?= esc($p['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="start_datetime">Start Date & Time</label>
        <input type="datetime-local" name="start_datetime" id="start_datetime" class="form-control" 
               value="<?= date('Y-m-d\TH:i', strtotime($appointment['start_datetime'])) ?>" required>
    </div>

    <div class="form-group">
        <label for="end_datetime">End Date & Time</label>
        <input type="datetime-local" name="end_datetime" id="end_datetime" class="form-control" 
               value="<?= date('Y-m-d\TH:i', strtotime($appointment['end_datetime'])) ?>" required>
    </div>

   

    <button type="submit" class="btn btn-primary mt-3">Update Appointment</button>
</form>

</div>
</body>
</html>
