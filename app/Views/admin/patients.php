<?= $this->extend('layouts/admin_layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h2>Patients</h2>
    <a href="<?= site_url('admin/addPatient') ?>" class="btn btn-primary mb-3">Add Patient</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($patients as $p): ?>
                <tr>
                    <td><?= $p['id'] ?></td>
                    <td><?= $p['name'] ?></td>
                    <td><?= $p['email'] ?></td>
                    <td><?= $p['phone'] ?></td>
                    <td>
                        <a href="<?= site_url('admin/editPatient/'.$p['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="<?= site_url('admin/deletePatient/'.$p['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                        <a href="<?= site_url('admin/visits/'.$p['id']) ?>" class="btn btn-sm btn-info">Visits</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>
