<?php helper('form'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Appointment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
    <h2>Appointment Details</h2>

    <div class="card mb-3">
        <div class="card-body">
            <h5>Patient: <?= esc($appointment['patient_name'] ?? 'N/A') ?></h5>
            <p><strong>Email:</strong> <?= esc($appointment['patient_email'] ?? 'N/A') ?></p>
            <p><strong>Doctor:</strong> <?= esc($appointment['doctor_name'] ?? 'N/A') ?></p>
            <p><strong>Appointment ID:</strong> <?= esc($appointment['id']) ?></p>
            <p><strong>Start:</strong> <?= esc($appointment['start_datetime']) ?></p>
            <p><strong>End:</strong> <?= esc($appointment['end_datetime']) ?></p>
            <p><strong>Status:</strong> <?= esc($appointment['status']) ?></p>
        </div>
    </div>

    <h4>Visit History</h4>
    <table class="table table-bordered bg-white">
        <thead class="table-dark">
            <tr>
                <th>Doctor</th>
                <th>Reason</th>
                <th>BP</th>
                <th>Weight</th>
                <th>Doctor Comments</th>
                <th>Prescription</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($visit_history)): ?>
                <?php foreach ($visit_history as $visit): ?>
                    <tr>
                        <td><?= esc($appointment['doctor_name']) ?></td>
                        <td><?= esc($visit['reason']) ?></td>
                        <td><?= esc($visit['blood_pressure']) ?></td>
                        <td><?= esc($visit['weight']) ?></td>
                        <td><?= esc($visit['doctor_comments']) ?></td>
                        <td>
                            <?php if (!empty($visit['prescription_id'])): ?>
                                <a href="<?= site_url('admin/viewPrescription/'.$visit['prescription_id']) ?>" 
                                   class="btn btn-success btn-sm">View Prescription</a>
                            <?php else: ?>
                                <span class="text-muted">No Prescription</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6" class="text-center">No visits found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <a href="<?= site_url('admin/appointments') ?>" class="btn btn-secondary mt-3">Back to Appointments</a>
</div>
</body>
</html>
