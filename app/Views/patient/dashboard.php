<?php helper('form'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Patient Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        canvas {
            max-width: 100%;
            margin-bottom: 20px;
        }
    </style>
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
                <th>Doctor Comments</th>
                <th>Prescription</th>
                <th>Bill</th> 
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
                         <td>
                        <?php if (!empty($visit['bill_id'])): ?>
                            <a href="<?= site_url('patient/viewBill/'.$visit['bill_id']) ?>" 
                               class="btn btn-primary btn-sm">View Bill</a>
                        <?php else: ?>
                            <span class="text-muted">No Bill</span>
                        <?php endif; ?>
                    </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6" class="text-center">No visits found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Your Visit History</h4>
    <a href="<?= site_url('patient/bookAppointment') ?>" class="btn btn-primary">
        Book Appointment
    </a>
</div>

    <?php if (!empty($visits)): ?>
        <!-- Charts -->
        <div class="row mt-5">
            <div class="col-md-6">
                <h5>Blood Pressure Over Time</h5>
                <canvas id="bpChart" height="300"></canvas>
            </div>
            <div class="col-md-6">
                <h5>Weight Over Time</h5>
                <canvas id="weightChart" height="300"></canvas>
            </div>
        </div>

        <script>
            const visits = <?= json_encode($visits) ?>;
            const dates = visits.map(v => v.created_at);
            const bp = visits.map(v => parseFloat(v.blood_pressure));
            const weight = visits.map(v => parseFloat(v.weight));
            const doctors = visits.map(v => v.doctor_name || 'Unknown');

            // BP Chart
            const ctxBP = document.getElementById('bpChart').getContext('2d');
            new Chart(ctxBP, {
                type: 'line',
                data: {
                    labels: dates,
                    datasets: [{
                        label: 'Blood Pressure',
                        data: bp,
                        borderColor: 'red',
                        backgroundColor: 'rgba(255,0,0,0.2)',
                        tension: 0.3,
                        fill: true,
                        pointRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const i = context.dataIndex;
                                    return `BP: ${context.raw}, Doctor: ${doctors[i]}`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            title: { display: true, text: 'Date' }
                        },
                        y: {
                            title: { display: true, text: 'Blood Pressure' }
                        }
                    }
                }
            });

            // Weight Chart
            const ctxWeight = document.getElementById('weightChart').getContext('2d');
            new Chart(ctxWeight, {
                type: 'line',
                data: {
                    labels: dates,
                    datasets: [{
                        label: 'Weight (kg)',
                        data: weight,
                        borderColor: 'blue',
                        backgroundColor: 'rgba(0,0,255,0.2)',
                        tension: 0.3,
                        fill: true,
                        pointRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const i = context.dataIndex;
                                    return `Weight: ${context.raw} kg, Doctor: ${doctors[i]}`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            title: { display: true, text: 'Date' }
                        },
                        y: {
                            title: { display: true, text: 'Weight (kg)' }
                        }
                    }
                }
            });
        </script>
    <?php endif; ?>

</div>
</body>
</html>
