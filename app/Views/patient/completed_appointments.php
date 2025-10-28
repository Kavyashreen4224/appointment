<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Completed Appointments</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
    <h3>Completed Appointments</h3>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php elseif (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <table class="table table-bordered table-striped mt-3 bg-white">
        <thead class="table-dark">
            <tr>
                <th>Appointment ID</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Doctor</th>
                <th>Status</th>
                <th>Prescription</th>
                <th>Bill</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($appointments)): ?>
                <?php foreach ($appointments as $a): ?>
                    <tr>
                        <td><?= esc($a['id']) ?></td>
                        <td><?= esc($a['start_datetime']) ?></td>
                        <td><?= esc($a['end_datetime']) ?></td>
                        <td><?= esc($a['doctor_name']) ?></td>
                        <td><span class="badge bg-success">Completed</span></td>

                        <!-- Prescription -->
                        <td>
                            <?php if (!empty($a['prescription_id'])): ?>
                                <a href="<?= site_url('patient/downloadPrescription/' . $a['prescription_id']) ?>" 
                                   class="btn btn-sm btn-outline-success">Download</a>
                            <?php else: ?>
                                <span class="text-muted">Not Available</span>
                            <?php endif; ?>
                        </td>

                        <!-- Bill -->
                        <td>
                            <?php if (!empty($a['bill_id'])): ?>
                                <a href="<?= site_url('patient/viewBill/' . $a['bill_id']) ?>" 
                                   class="btn btn-sm btn-outline-primary">View Bill</a>
                            <?php else: ?>
                                <span class="text-muted">Not Available</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="7" class="text-center">No completed appointments found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <a href="<?= site_url('patient/dashboard') ?>" class="btn btn-secondary mt-3">Back to Dashboard</a>
</div>
</body>
</html>
