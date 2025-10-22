<?= $this->extend('layouts/admin_layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Patients List</h2>
        <a href="<?= site_url('admin/addPatient') ?>" class="btn btn-primary">Add Patient</a>
    </div>

    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Age</th>
                <th>Gender</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if($patients): ?>
                <?php foreach($patients as $p): ?>
                    <tr>
                        <td><?= esc($p['id']) ?></td>
                        <td>
                            <a href="<?= site_url('admin/patientProfile/'.$p['id']) ?>">
                                <?= esc($p['name']) ?>
                            </a>
                        </td>
                        <td><?= esc($p['email']) ?></td>
                        <td><?= esc($p['age']) ?></td>
                        <td><?= esc($p['gender']) ?></td>
                        <td>
                            <a href="<?= site_url('admin/editPatient/'.$p['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="<?= site_url('admin/deletePatient/'.$p['id']) ?>" class="btn btn-sm btn-danger"
                               onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6" class="text-center">No patients found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>
