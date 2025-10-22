<?= $this->extend('layouts/admin_layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h2>All Appointments</h2>

   <table class="table table-bordered table-striped mt-3">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Patient</th>
            <th>Doctor</th>
            <th>Start Date & Time</th>
            <th>End Date & Time</th>
            <th>Status</th>
            <th>Prescription</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if(!empty($appointments)): ?>
            <?php foreach($appointments as $app): ?>
                <tr>
                    <td><?= esc($app['id']) ?></td>
                    <td>
                        <a href="<?= site_url('admin/patientProfile/' . $app['patient_id']) ?>">
                            <?= esc($app['patient_name']) ?>
                        </a>
                    </td>
                    <td>
                        <a href="<?= site_url('admin/doctorProfile/' . $app['doctor_id']) ?>">
                            <?= esc($app['doctor_name']) ?>
                        </a>
                    </td>
                    <td><?= esc($app['start_datetime']) ?></td>
                    <td><?= esc($app['end_datetime']) ?></td>
                    <td><?= esc($app['status']) ?></td>
                    <td>
                        <?php if(!empty($app['prescription_id'])): ?>
                            <a href="<?= site_url('admin/viewPrescription/'.$app['prescription_id']) ?>" 
                               class="btn btn-success btn-sm">View Prescription</a>
                        <?php else: ?>
                            <span class="text-muted">No Prescription</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="<?= site_url('admin/viewAppointment/'.$app['id']) ?>" 
                           class="btn btn-primary btn-sm">View Appointment</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="8" class="text-center">No appointments found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

</div>

<?= $this->endSection() ?>
