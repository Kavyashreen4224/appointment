<?= $this->extend('layouts/superadmin_layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h3>Add Admin</h3>

    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <form action="<?= site_url('superadmin/addAdminPost') ?>" method="post">
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Hospital</label>
            <select name="hospital_id" class="form-control" required>
                <?php foreach($hospitals as $hospital): ?>
                    <option value="<?= $hospital['id'] ?>"><?= esc($hospital['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Add Admin</button>
    </form>
</div>

<?= $this->endSection() ?>
