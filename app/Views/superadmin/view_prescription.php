<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>View Prescription</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">

<h3>Prescription Details</h3>

<p><strong>Patient ID:</strong> <?= esc($prescription['patient_id']) ?></p>
<p><strong>Appointment ID:</strong> <?= esc($prescription['appointment_id']) ?></p>
<p><strong>Doctor ID:</strong> <?= esc($prescription['doctor_id']) ?></p>

<div class="card">
    <div class="card-body">
        <pre><?= esc($prescription['prescription_text']) ?></pre>
    </div>
</div>

<a href="<?= site_url('superadmin/doctorProfile/'.$prescription['doctor_id']) ?>" class="btn btn-secondary mt-3">Back to doctor Profile</a>

</div>
</body>
</html>
