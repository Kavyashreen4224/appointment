<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Upcoming Appointments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container mt-4">
        <h3 class="mb-3">Upcoming Appointments</h3>
        <a href="<?= site_url('patient/dashboard') ?>" class="btn btn-secondary mb-3">← Back to Dashboard</a>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php elseif (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <table class="table table-bordered bg-white shadow-sm">
            <thead class="table-primary">
                <tr>
                    <th>ID</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Doctor</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($appointments)): ?>
                    <?php foreach ($appointments as $a): ?>
                        <tr>
                            <td><?= esc($a['id']) ?></td>
                            <td><?= date('d M Y, h:i A', strtotime($a['start_datetime'])) ?></td>
                            <td><?= date('d M Y, h:i A', strtotime($a['end_datetime'])) ?></td>
                            <td><?= esc($a['doctor_name'] ?? 'Unknown') ?></td>
                            <td>
                                <?php if ($a['status'] == 'pending'): ?>
                                    <span class="badge bg-warning text-dark"><?= ucfirst($a['status']) ?></span>
                                <?php elseif ($a['status'] == 'rescheduled'): ?>
                                    <span class="badge bg-info text-dark"><?= ucfirst($a['status']) ?></span>
                                <?php else: ?>
                                    <span class="badge bg-danger"><?= ucfirst($a['status']) ?></span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <?php if ($a['status'] !== 'cancelled'): ?>
                                    <!-- Cancel -->
                                    <a href="<?= site_url('patient/cancelAppointment/' . $a['id']) ?>"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Are you sure you want to cancel this appointment?')">
                                        Cancel
                                    </a>

                                    <a href="<?= site_url('patient/rescheduleAppointment/' . $a['id']) ?>"
                                        class="btn btn-warning btn-sm">
                                        Reschedule
                                    </a>

                                <?php else: ?>
                                    <span class="text-muted">Cancelled</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted">No upcoming appointments found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>

</html>