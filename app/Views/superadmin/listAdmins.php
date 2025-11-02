<?= $this->extend('layouts/superadmin_layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Manage Admins</h3>
        <a href="<?= site_url('superadmin/addAdmin') ?>" class="btn btn-success">Add Admin</a>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php elseif (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <table class="table table-bordered table-striped mt-3">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Hospital</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($admins)): ?>
                <?php $i = 1;
                foreach ($admins as $admin): ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><?= esc($admin['admin_name']) ?></td>
                        <td><?= esc($admin['admin_email']) ?></td>
                        <td><?= esc($admin['hospital_name']) ?></td>
                        <td>
                            <span class="badge <?= $admin['status'] === 'active' ? 'bg-success' : 'bg-secondary' ?>">
                                <?= ucfirst($admin['status']) ?>
                            </span>
                        </td>
                        <td><?= date('d M Y', strtotime($admin['created_at'])) ?></td>
                        <td>
                            <a href="<?= site_url('superadmin/editAdmin/' . $admin['hospital_user_id']) ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="<?= site_url('superadmin/deleteAdmin/' . $admin['hospital_user_id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>

                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center text-muted">No admins found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>