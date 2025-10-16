<!DOCTYPE html>
<html>
<head>
    <title>Patient Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">
    <h2>Welcome, <?= esc($patient['name']) ?></h2>
    <p><strong>Email:</strong> <?= esc($patient['email']) ?></p>

    <hr>

    <h4>Your Visit History</h4>
    <table class="table table-bordered bg-white">
        <thead class="table-dark">
            <tr>
               
                <th>Doctor</th>
                <th>Reason</th>
                <th>BP</th>
                <th>Weight</th>
                <th>doctor comments</th>
                <th>Prescription</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($visits)): ?>
                <?php foreach ($visits as $visit): ?>
                    <tr>
                        
                        <td><?= esc($visit['doctor_name']) ?></td>
                        <td><?= esc($visit['reason']) ?></td>
                        <td><?= esc($visit['blood_pressure']) ?></td>
                        <td><?= esc($visit['weight']) ?></td>
                        <td><?= esc($visit['doctor_comments']) ?></td>
                        
                        <td>
                            <?php if (!empty($visit['prescription_id'])): ?>
                                <a href="<?= site_url('patient/downloadPrescription/'.$visit['prescription_id']) ?>" 
                                   class="btn btn-success btn-sm">Download</a>
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
</div>

</body>
</html>
