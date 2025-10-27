<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Bill</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <div class="card shadow p-4">
    <h3 class="mb-3">Bill Details</h3>
    <p><strong>Patient:</strong> <?= esc($patient['patient_name']) ?></p>
    <p><strong>Doctor:</strong> <?= esc($doctor['doctor_name']) ?></p>
    <p><strong>Date:</strong> <?= esc($visit['created_at']) ?></p>
    <hr>
    <p><strong>Total Amount:</strong> ₹<?= esc($bill['total_amount']) ?></p>
    <p><strong>Description:</strong> <?= nl2br(esc($bill['description'] ?? 'N/A')) ?></p>

    <a href="<?= site_url('patient/dashboard') ?>" class="btn btn-secondary mt-3">Back to Dashboard</a>
  </div>
</div>

</body>
</html>
