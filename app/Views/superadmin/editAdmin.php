<?= $this->extend('layouts/superadmin_layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
  <h3>Edit Admin</h3>
  <hr>

  <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
  <?php endif; ?>

  <form action="<?= site_url('superadmin/updateAdmin/'.$admin['hospital_user_id']) ?>" method="post">
    <div class="mb-3">
      <label class="form-label">Full Name</label>
      <input type="text" name="name" class="form-control" value="<?= esc($admin['name']) ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Email</label>
      <input type="email" name="email" class="form-control" value="<?= esc($admin['email']) ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label">New Password (optional)</label>
      <input type="password" name="password" class="form-control" placeholder="Leave blank to keep existing">
    </div>

    <div class="mb-3">
      <label class="form-label">Assigned Hospital</label>
      <select name="hospital_id" class="form-select" required>
        <?php foreach ($hospitals as $hospital): ?>
          <option value="<?= $hospital['id'] ?>" <?= ($hospital['id'] == $admin['hospital_id']) ? 'selected' : '' ?>>
            <?= esc($hospital['name']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <button type="submit" class="btn btn-primary">Update</button>
    <a href="<?= site_url('superadmin/listAdmins') ?>" class="btn btn-secondary">Cancel</a>
  </form>
</div>

<?= $this->endSection() ?>
