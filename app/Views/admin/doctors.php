<?= $this->extend('layouts/admin_layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h2>Doctors</h2>
    <a href="<?= site_url('admin/addDoctor') ?>" class="btn btn-primary mb-3">Add Doctor</a>
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
            <?php foreach($doctors as $d): ?>
                <tr>
                    <td><?= $d['id'] ?></td>
                    <td><?= $d['name'] ?></td>
                    <td><?= $d['email'] ?></td>
                    <td><?= $d['phone'] ?></td>
                    <td>
                        <a href="<?= site_url('admin/editDoctor/'.$d['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="<?= site_url('admin/deleteDoctor/'.$d['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>
