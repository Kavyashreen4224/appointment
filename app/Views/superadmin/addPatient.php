<?= $this->extend('layouts/superadmin_layout') ?>
<?= $this->section('content') ?>

<h2 class="mb-4">Add New Patient</h2>

<form method="post" action="<?= site_url('superadmin/savePatient') ?>" class="bg-white p-4 rounded shadow-sm">
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
    <label class="form-label">Select Hospital</label>
    <select name="hospital_id" class="form-select" required>
      <option value="">-- Choose Hospital --</option>
      <?php foreach ($hospitals as $h): ?>
        <option value="<?= $h['id'] ?>"><?= esc($h['name']) ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <button type="submit" class="btn btn-primary">Save</button>
  <a href="<?= site_url('superadmin/listPatients') ?>" class="btn btn-secondary">Cancel</a>
</form>

<?= $this->endSection() ?>
