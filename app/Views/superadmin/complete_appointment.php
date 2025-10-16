<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Complete Appointment</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {background: #eef2f7; font-family: 'Poppins', sans-serif;}
.card {border-radius: 12px; padding: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.2);}
</style>
</head>
<body>
<div class="container mt-5">
  <div class="card mx-auto" style="max-width:700px;">
    <h3 class="text-center mb-4">Complete Appointment</h3>

    <form action="<?= site_url('superadmin/saveCompleteAppointment/'.$appointment['id']) ?>" method="post">
      <div class="mb-3">
        <label class="form-label">Blood Pressure</label>
        <input type="text" name="blood_pressure" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Weight (kg)</label>
        <input type="text" name="weight" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Doctor Comments</label>
        <textarea name="doctor_comments" class="form-control" rows="4" required></textarea>
      </div>

      <div class="mb-3">
        <label class="form-label">Prescription</label>
        <textarea name="prescription" class="form-control" rows="4" placeholder="Optional"></textarea>
      </div>

      <button type="submit" class="btn btn-success w-100">Mark as Completed</button>
      <a href="<?= site_url('superadmin/listAppointments') ?>" class="btn btn-secondary w-100 mt-2">Back to List</a>
    </form>
  </div>
</div>
</body>
</html>
