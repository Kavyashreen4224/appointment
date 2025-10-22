<?= $this->extend('layouts/admin_layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Doctors List</h2>
        <a href="<?= site_url('admin/addDoctor') ?>" class="btn btn-primary">Add Doctor</a>
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
                <th>Expertise</th>
                <th>Availability</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if($doctors): ?>
                <?php foreach($doctors as $d): ?>
                    <tr>
                        <td><?= esc($d['id']) ?></td>
                       <td>
                            <a href="<?= site_url('admin/doctorProfile/'.$d['id']) ?>">
                                <?= esc($d['name']) ?>
                            </a>
                        </td>
                        <td><?= esc($d['email']) ?></td>
                        <td><?= esc($d['age']) ?></td>
                        <td><?= esc($d['gender']) ?></td>
                        <td><?= esc($d['expertise']) ?></td>
                        <td><?= esc($d['availability']) ?></td>
                        <td>
                            <a href="<?= site_url('admin/editDoctor/'.$d['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="<?= site_url('admin/deleteDoctor/'.$d['id']) ?>" class="btn btn-sm btn-danger"
                               onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="8" class="text-center">No doctors found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>
