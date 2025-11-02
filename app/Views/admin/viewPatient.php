<?= $this->extend('layouts/admin_layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h2 class="mb-3 text-primary"><?= esc($patient['patient_name']) ?>'s Profile</h2>

    <!-- 🧍‍♀️ Basic Details -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="card-title text-secondary mb-3">Personal Details</h5>
            <p><strong>Name:</strong> <?= esc($patient['patient_name']) ?></p>
            <p><strong>Email:</strong> <?= esc($patient['email']) ?></p>
            <p><strong>Age:</strong> <?= esc($patient['age']) ?></p>
            <p><strong>Gender:</strong> <?= ucfirst($patient['gender']) ?></p>
        </div>
    </div>

    <!-- 🏥 Hospital Details -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="card-title text-secondary mb-3">Hospital Details</h5>
            <p><strong>Hospital Name:</strong> <?= esc($patient['hospital_name']) ?></p>
            <p><strong>Address:</strong> <?= esc($patient['hospital_address']) ?></p>
            <p><strong>Status:</strong> 
                <span class="badge <?= $patient['hospital_status'] === 'active' ? 'bg-success' : 'bg-danger' ?>">
                    <?= ucfirst($patient['hospital_status']) ?>
                </span>
            </p>
        </div>
    </div>

    <!-- 📋 Visit History -->
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title text-secondary mb-3">Visit History</h5>

            <?php if (!empty($appointments)): ?>
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Doctor</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($appointments as $i => $a): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td>
                                    <a href="<?= site_url('admin/viewDoctor/' . $a['doctor_id']) ?>" class="text-decoration-none text-primary">
                                        <?= esc($a['doctor_name']) ?>
                                    </a>
                                </td>
                                <td><?= date('d M Y, h:i A', strtotime($a['start_datetime'])) ?></td>
                                <td><?= date('d M Y, h:i A', strtotime($a['end_datetime'])) ?></td>
                                <td>
                                    <?php if ($a['status'] === 'completed'): ?>
                                        <span class="badge bg-success">Completed</span>
                                    <?php elseif ($a['status'] === 'pending'): ?>
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Cancelled</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-muted">No appointments found.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
