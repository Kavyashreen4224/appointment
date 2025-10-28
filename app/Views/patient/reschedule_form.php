<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Reschedule Appointment</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
      <h4>Reschedule Appointment #<?= esc($appointment['id']) ?></h4>
    </div>
    <div class="card-body">
      <form action="<?= site_url('patient/updateReschedule/'.$appointment['id']) ?>" method="post">
        <div class="mb-3">
          <label for="start_datetime" class="form-label">New Start Date & Time</label>
          <input type="datetime-local" name="start_datetime" id="start_datetime" class="form-control" required
                 value="<?= date('Y-m-d\TH:i', strtotime($appointment['start_datetime'])) ?>">
        </div>
        <div class="mb-3">
          <label for="end_datetime" class="form-label">New End Date & Time</label>
          <input type="datetime-local" name="end_datetime" id="end_datetime" class="form-control" required
                 value="<?= date('Y-m-d\TH:i', strtotime($appointment['end_datetime'])) ?>">
        </div>

        <button type="submit" class="btn btn-success">Update</button>
        <a href="<?= site_url('patient/upcomingAppointments') ?>" class="btn btn-secondary">Cancel</a>
      </form>
    </div>
  </div>
</div>

</body>
</html>
