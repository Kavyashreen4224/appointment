<?= $this->extend('layouts/doctor_layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h2>Patients List</h2>
    <a href="<?= base_url('doctor/addPatient') ?>" class="btn btn-success mb-3">Add Patient</a>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th><th>Name</th><th>Email</th><th>Age</th><th>Gender</th><th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($patients as $p): ?>
                <tr>
                    <td><?= $p['patient_id'] ?></td>
                    <td><?= esc($p['name']) ?></td>
                    <td><?= esc($p['email']) ?></td>
                    <td><?= esc($p['age']) ?></td>
                    <td><?= esc($p['gender']) ?></td>
                    <td>
                        <a href="<?= base_url('doctor/editPatient/'.$p['patient_id']) ?>" class="btn btn-primary btn-sm">Edit</a>
                        <a href="<?= base_url('doctor/deletePatient/'.$p['patient_id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this patient?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>
