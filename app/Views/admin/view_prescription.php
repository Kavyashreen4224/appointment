<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Prescription</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
    <h2>Prescription Details</h2>
    <hr>

    <p><strong>Doctor:</strong> <?= esc($prescription['doctor_name']) ?></p>
    <p><strong>Patient:</strong> <?= esc($prescription['patient_name']) ?></p>
    <p><strong>Date:</strong> <?= esc($prescription['created_at']) ?></p>

    <div class="card mt-3">
        <div class="card-body">
            <h5 class="card-title">Prescription Notes</h5>
            <p><?= nl2br(esc($prescription['prescription_text'] ?? 'No details')) ?></p>
        </div>
    </div>

    <a href="<?= site_url('admin/patientProfile/'.$prescription['patient_id']) ?>" 
       class="btn btn-secondary mt-3">Back</a>
</div>
</body>
</html>
