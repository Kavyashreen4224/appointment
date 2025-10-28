<?= $this->extend('layouts/admin_layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h2><?= esc($patient['patient_name']) ?>'s Profile</h2>
    <p><strong>Email:</strong> <?= esc($patient['email']) ?></p>
    <hr>

    <h4>Visit History</h4>
    <table class="table table-bordered bg-white">
        <thead class="table-dark">
            <tr>
                <th>Date</th>
                <th>Doctor</th>
                <th>Reason</th>
                <th>BP</th>
                <th>Weight</th>
                <th>Doctor Comments</th>
                <th>Prescription</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($visits): ?>
                <?php foreach ($visits as $v): ?>
                    <tr>
                        <td><?= esc($v['created_at']) ?></td>
                        <td><?= esc($v['doctor_name']) ?></td>
                        <td><?= esc($v['reason']) ?></td>
                        <td><?= esc($v['blood_pressure']) ?></td>
                        <td><?= esc($v['weight']) ?></td>
                        <td><?= esc($v['doctor_comments']) ?></td>
                        <td>
                            <?php if ($v['prescription_id']): ?>
                                <a href="<?= site_url('admin/viewPrescription/'.$v['prescription_id']) ?>" 
                                   class="btn btn-success btn-sm">View Prescription</a>
                            <?php else: ?>
                                <span class="text-muted">No Prescription</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="7" class="text-center">No visits found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <?php if ($visits): ?>
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
    <?php endif; ?>

</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
<?php if ($visits): ?>
    const visits = <?= json_encode($visits) ?>;
    const dates = visits.map(v => v.created_at);
    const bp = visits.map(v => parseFloat(v.blood_pressure));
    const weight = visits.map(v => parseFloat(v.weight));
    const doctors = visits.map(v => v.doctor_name || 'Unknown');

    // Blood Pressure Chart
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
                x: { title: { display: true, text: 'Date' }},
                y: { title: { display: true, text: 'Blood Pressure' }}
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
                x: { title: { display: true, text: 'Date' }},
                y: { title: { display: true, text: 'Weight (kg)' }}
            }
        }
    });
<?php endif; ?>
</script>

<?= $this->endSection() ?>
