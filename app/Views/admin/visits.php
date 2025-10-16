<?= $this->extend('layouts/admin_layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h2>Patient Visits</h2>
    <a href="<?= site_url('admin/patients') ?>" class="btn btn-secondary mb-3">Back to Patients</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Doctor</th>
                <th>Prescription</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($visits as $v): ?>
                <tr>
                    <td><?= $v['id'] ?></td>
                    <td><?= $v['doctor_name'] ?></td>
                    <td><?= $v['prescription_text'] ?? 'No Prescription' ?></td>
                    <td>
                        <a href="<?= site_url('admin/addPrescription/'.$v['id']) ?>" class="btn btn-sm btn-primary">Add Prescription</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>
