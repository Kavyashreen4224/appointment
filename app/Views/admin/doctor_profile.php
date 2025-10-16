<?= $this->extend('layouts/admin_layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h2>Doctor Profile</h2>

    <div class="card mb-3">
        <div class="card-body">
            <h4><?= esc($doctor['doctor_name']) ?></h4>
            <p><strong>Email:</strong> <?= esc($doctor['doctor_email']) ?></p>
            <p><strong>Hospital:</strong> <?= esc($doctor['hospital_name']) ?></p>
            <p><strong>Expertise:</strong> <?= esc($doctor['expertise']) ?></p>
            <p><strong>Availability:</strong> <?= esc($doctor['availability']) ?></p>
        </div>
    </div>

    <h4>Appointments</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Patient Name</th>
                <th>Patient Email</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($appointments)): ?>
                <?php foreach($appointments as $appt): ?>
                    <tr>
                        <td><?= $appt['id'] ?></td>
                        <td><?= esc($appt['patient_name']) ?></td>
                        <td><?= esc($appt['patient_email']) ?></td>
                        <td><?= esc($appt['start_datetime']) ?></td>
                        <td><?= esc($appt['end_datetime']) ?></td>
                        <td><?= esc($appt['status']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6" class="text-center">No appointments found</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>
