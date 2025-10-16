<!DOCTYPE html>
<html>
<head>
  <title>Visit Details</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
  <h3>Visit Details for Appointment #<?= $appointment['id'] ?></h3>

  <form method="post" action="<?= site_url('superadmin/addVisitDetails/'.$appointment['id']) ?>">
    <div class="mb-3">
      <label class="form-label">Notes / Visit Summary</label>
      <textarea name="notes" class="form-control" rows="5"><?= esc($appointment['notes']) ?></textarea>
    </div>

    <button type="submit" class="btn btn-success">Save</button>
    <a href="<?= site_url('superadmin/doctorProfile/'.$appointment['doctor_id']) ?>" class="btn btn-secondary">Back</a>
  </form>
</div>
</body>
</html>
    