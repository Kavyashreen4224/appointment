<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Add Prescription</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
  <h3>Add Prescription</h3>
  <form method="post" action="<?= site_url('superadmin/savePrescription') ?>">
    <input type="hidden" name="appointment_id" value="<?= esc($appointment_id) ?>">
    <input type="hidden" name="doctor_id" value="<?= esc($doctor_id) ?>">
    <input type="hidden" name="patient_id" value="<?= esc($patient_id) ?>">
    <input type="hidden" name="visit_id" value="<?= esc($visit['id'] ?? '') ?>">

    <div class="mb-3">
      <label for="prescription_text" class="form-label">Prescription Details</label>
      <textarea name="prescription_text" id="prescription_text" rows="5" class="form-control" required></textarea>
    </div>

    <button type="submit" class="btn btn-success">Save Prescription</button>
  </form>
</div>
</body>
</html>
