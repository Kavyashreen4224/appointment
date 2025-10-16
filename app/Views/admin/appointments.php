<?= $this->extend('layouts/admin_layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h2>Appointments</h2>
    <a href="<?= site_url('admin/addAppointment') ?>" class="btn btn-primary mb-3">Add Appointment</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Patient</th>
                <th>Doctor</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($appointments as $a): ?>
                <tr>
                    <td><?= $a['id'] ?></td>
                    <td><?= $a['patient_name'] ?></td>
                    <td><?= $a['doctor_name'] ?></td>
                    <td><?= $a['appointment_date'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>
