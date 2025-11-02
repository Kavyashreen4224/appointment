<?= $this->extend('layouts/admin_layout') ?>
<?= $this->section('content') ?>

<h3 class="mb-3">Patients List</h3>

<a href="<?= site_url('admin/addPatient') ?>" class="btn btn-success mb-3">+ Add Patient</a>

<table class="table table-bordered table-striped bg-white">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Age</th>
            <th>Gender</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($patients as $p): ?>
            <tr>
                <td><?= esc($p['patient_id']) ?></td>
                <td><?= esc($p['name']) ?></td>
                <td><?= esc($p['email']) ?></td>
                <td><?= esc($p['age']) ?></td>
                <td><?= esc($p['gender']) ?></td>
                <td>
                    <a href="<?= site_url('admin/viewPatient/'.$p['patient_id']) ?>" class="btn btn-info btn-sm">View</a>
                    <a href="<?= site_url('admin/editPatient/'.$p['patient_id']) ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="<?= site_url('admin/deletePatient/'.$p['patient_id']) ?>" 
                       onclick="return confirm('Are you sure you want to delete this patient?')" 
                       class="btn btn-danger btn-sm">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?= $this->endSection() ?>
