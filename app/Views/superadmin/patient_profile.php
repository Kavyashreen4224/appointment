<?php helper('form'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Patient Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        canvas {
            max-width: 100%;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <h3>Patient Profile</h3>

        <a href="<?= !empty($doctor_id) ? site_url('superadmin/doctorProfile/'.$doctor_id) : '#' ?>" class="btn btn-secondary mb-3">Back to Doctor Profile</a>

        <!-- Patient Info -->
        <div class="card mb-3">
            <div class="card-body">
                <h4><?= esc($patient['name']) ?></h4>
                <p><strong>Email:</strong> <?= esc($patient['email']) ?></p>
                <p><strong>Age:</strong> <?= esc($patient['age']) ?></p>
                <p><strong>Gender:</strong> <?= esc($patient['gender']) ?></p>
                <img src="<?= base_url('assets/img/placeholder.png') ?>" width="120" class="rounded-circle">
            </div>
        </div>

      <h4>Visit History</h4>
<?php if(!empty($visits)): ?>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Date</th>
                <th>Reason</th>
                <th>BP</th>
                <th>Weight</th>
                <th>Doctor Comments</th>
                <th>Doctor</th>
                <th>Prescription</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($visits as $v): ?>
                <tr>
                    <td><?= esc($v['created_at']) ?></td>
                    <td><?= esc($v['reason']) ?></td>
                    <td><?= esc($v['blood_pressure']) ?></td>
                    <td><?= esc($v['weight']) ?></td>
                    <td><?= esc($v['doctor_comments']) ?></td>
                    <td><?= esc($v['doctor_name']) ?></td>
                    <td>
                        <?php if(!empty($v['prescription_id'])): ?>
                            <a href="<?= site_url('superadmin/downloadPrescription/'.$v['prescription_id']) ?>" class="btn btn-sm btn-success">
                                Download
                            </a>
                        <?php else: ?>
                            <span class="text-muted">Not added</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>


            <!-- Charts -->
            <div class="row">
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
                                title: {
                                    display: true,
                                    text: 'Date'
                                }
                            },
                            y: {
                                title: {
                                    display: true,
                                    text: 'Blood Pressure'
                                }
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
                                title: {
                                    display: true,
                                    text: 'Date'
                                }
                            },
                            y: {
                                title: {
                                    display: true,
                                    text: 'Weight (kg)'
                                }
                            }
                        }
                    }
                });
            </script>

        <?php else: ?>
            <p>No visit history found.</p>
        <?php endif; ?>
    </div>
</body>

</html>