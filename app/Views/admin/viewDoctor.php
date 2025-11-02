<?= $this->extend('layouts/admin_layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Doctor Profile</h4>
        </div>

        <div class="card-body">
            <h5 class="text-primary mb-3"><?= esc($doctor['doctor_name']) ?></h5>

            <div class="row mb-3">
                <div class="col-md-6"><strong>Email:</strong> <?= esc($doctor['doctor_email']) ?></div>
                <div class="col-md-6"><strong>Gender:</strong> <?= ucfirst($doctor['gender']) ?></div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6"><strong>Age:</strong> <?= esc($doctor['age']) ?></div>
                <div class="col-md-6"><strong>Expertise:</strong> <?= esc($doctor['expertise']) ?></div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Availability Type:</strong>
                    <span class="badge <?= $doctor['availability_type'] === 'fixed' ? 'bg-success' : 'bg-info' ?>">
                        <?= ucfirst($doctor['availability_type']) ?>
                    </span>
                </div>
                <div class="col-md-6"><strong>Hospital:</strong> <?= esc($doctor['hospital_name']) ?></div>
            </div>

            <hr>
            <h5 class="text-secondary mt-4 mb-3">🏥 Hospital Details</h5>
            <p><strong>Address:</strong> <?= esc($doctor['hospital_address']) ?></p>
            <p><strong>Contact:</strong> <?= esc($doctor['hospital_contact']) ?></p>
            <p><strong>Email:</strong> <?= esc($doctor['hospital_email']) ?></p>

            <hr>
            <h5 class="text-secondary mt-4 mb-3">🩺 Appointments</h5>

            <?php if (!empty($appointments)): ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Patient Name</th>
                                <th>Email</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($appointments as $a): ?>
                                <tr>
                                    <td><?= esc($a['id']) ?></td>
                                    <td>
                                        <a href="<?= site_url('admin/viewPatient/' . $a['patient_id']) ?>" class="text-decoration-none text-primary fw-semibold">
                                            <?= esc($a['patient_name']) ?>
                                        </a>
                                    </td>

                                    <td><?= esc($a['patient_email']) ?></td>
                                    <td><?= date('d M Y, h:i A', strtotime($a['start_datetime'])) ?></td>
                                    <td><?= date('d M Y, h:i A', strtotime($a['end_datetime'])) ?></td>
                                    <td>
                                        <span class="badge 
                                            <?= $a['status'] === 'completed' ? 'bg-success' : ($a['status'] === 'cancelled' ? 'bg-danger' : 'bg-warning text-dark') ?>">
                                            <?= ucfirst($a['status']) ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-muted">No appointments found for this doctor.</p>
            <?php endif; ?>

            <hr>
            <div class="text-muted small">
                <p><strong>Created At:</strong> <?= date('d M Y, h:i A', strtotime($doctor['created_at'])) ?></p>
                <p><strong>Last Updated:</strong> <?= date('d M Y, h:i A', strtotime($doctor['updated_at'])) ?></p>
            </div>

            <a href="<?= site_url('admin/listDoctors') ?>" class="btn btn-secondary mt-3">Back to List</a>
        </div>
    </div>
</div>

<?= $this->endSection() ?>