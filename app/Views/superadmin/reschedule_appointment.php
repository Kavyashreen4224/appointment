<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Reschedule Appointment</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h3>Reschedule Appointment ID: <?= esc($appointment['id']) ?></h3>

    <form action="<?= site_url('superadmin/saveReschedule/'.$appointment['id']) ?>" method="post">
        <div class="mb-3">
            <label>Start Date & Time</label>
            <input type="datetime-local" name="start_datetime" class="form-control" value="<?= date('Y-m-d\TH:i', strtotime($appointment['start_datetime'])) ?>" required>
        </div>
        <div class="mb-3">
            <label>End Date & Time</label>
            <input type="datetime-local" name="end_datetime" class="form-control" value="<?= date('Y-m-d\TH:i', strtotime($appointment['end_datetime'])) ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
        <a href="<?= site_url('superadmin/doctorProfile/'.$appointment['doctor_id']) ?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
