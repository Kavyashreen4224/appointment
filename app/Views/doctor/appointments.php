<?= $this->extend('layouts/doctor_layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h2>My Appointments</h2>

    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <a href="<?= site_url('doctor/addAppointment') ?>" class="btn btn-success mb-3">Add Appointment</a>

    <table class="table table-bordered table-striped mt-3">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Patient Name</th>
                <th>Start Date & Time</th>
                <th>End Date & Time</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($appointments)): ?>
                <?php foreach($appointments as $app): ?>
                    <tr>
                        <td><?= esc($app['id']) ?></td>
                        <td>
    <a href="<?= site_url('doctor/patient/' . $app['patient_id']) ?>">
        <?= esc($app['patient_name']) ?>
    </a>
</td>

                        <td><?= esc($app['start_datetime']) ?></td>
                        <td><?= esc($app['end_datetime']) ?></td>
                        <td>
                            <?php if($app['status'] == 'pending'): ?>
                                <span class="badge bg-warning text-dark">Pending</span>
                            <?php elseif($app['status'] == 'completed'): ?>
                                <span class="badge bg-success">Completed</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Cancelled</span>
                            <?php endif; ?>
                        </td>
                        <td>
    <?php if(!empty($app['prescription_id'])): ?>
        <a href="<?= site_url('doctor/viewPrescription/'.$app['prescription_id']) ?>" 
           class="btn btn-sm btn-success">
           View Prescription
        </a>
    <?php else: ?>
        <a href="<?= site_url('doctor/addPrescription/'.$app['id']) ?>" 
           class="btn btn-sm btn-primary">
           Add Prescription
        </a>
    <?php endif; ?>
</td>

                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">No appointments found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
      <a href="<?= site_url('doctor/dashboard') ?>" class="btn btn-secondary mt-3">Back to Dashboard</a>
</div>

<?= $this->endSection() ?>
