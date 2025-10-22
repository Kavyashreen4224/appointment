<?= $this->extend('layouts/admin_layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h2>Dr. <?= esc($doctor['doctor_name']) ?>'s Profile</h2>
    <p><strong>Email:</strong> <?= esc($doctor['email']) ?></p>
    <p><strong>Expertise:</strong> <?= esc($doctor['expertise']) ?></p>
    <p><strong>Availability:</strong> <?= esc($doctor['availability']) ?></p>
    <hr>

    <h4>Appointments</h4>
    <table class="table table-bordered bg-white">
        <thead class="table-dark">
            <tr>
                <th>Patient</th>
                <th>Start</th>
                <th>End</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if($appointments): ?>
                <?php foreach($appointments as $a): ?>
                    <tr>
                        <td>
                            <a href="<?= site_url('admin/patientProfile/'.$a['patient_id']) ?>">
                                <?= esc($a['patient_name']) ?>
                            </a>
                        </td>
                        <td><?= esc($a['start_datetime']) ?></td>
                        <td><?= esc($a['end_datetime']) ?></td>
                        <td>
                            <?php if($a['status']=='pending'): ?>
                                <span class="badge bg-warning text-dark">Pending</span>
                            <?php elseif($a['status']=='completed'): ?>
                                <span class="badge bg-success">Completed</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Cancelled</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?= site_url('admin/viewAppointment/'.$a['id']) ?>" class="btn btn-sm btn-primary">View Details</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5" class="text-center">No appointments found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>
