<?= $this->extend('layouts/superadmin_layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
  <h3>Add New Admin</h3>
  <hr>

  <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
  <?php endif; ?>

  <form action="<?= site_url('superadmin/saveAdmin') ?>" method="post">
    <div class="mb-3">
      <label class="form-label">Full Name</label>
      <input type="text" name="name" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Email</label>
      <input type="email" name="email" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Password</label>
      <input type="password" name="password" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Assign Hospital</label>
      <select name="hospital_id" class="form-select" required>
        <option value="">-- Select Hospital --</option>
        <?php foreach ($hospitals as $hospital): ?>
          <option value="<?= $hospital['id'] ?>"><?= esc($hospital['name']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <button type="submit" class="btn btn-primary">Save Admin</button>
    <a href="<?= site_url('superadmin/listAdmins') ?>" class="btn btn-secondary">Cancel</a>
  </form>
</div>

<?= $this->endSection() ?>
