<?= $this->extend('layouts/superadmin_layout') ?>
<?= $this->section('content') ?>

<h2 class="mb-4">Edit Patient</h2>

<form method="post" action="<?= site_url('superadmin/updatePatient/' . $patient['id']) ?>" class="bg-white p-4 rounded shadow-sm">

  <div class="row">
    <div class="col-md-6 mb-3">
      <label class="form-label">Full Name</label>
      <input type="text" name="name" class="form-control" value="<?= esc($patient['name']) ?>" required>
    </div>

    <div class="col-md-6 mb-3">
      <label class="form-label">Email</label>
      <input type="email" name="email" class="form-control" value="<?= esc($patient['email']) ?>" required>
    </div>
  </div>

  <div class="row">
    <div class="col-md-6 mb-3">
      <label class="form-label">Password (leave blank to keep same)</label>
      <input type="password" name="password" class="form-control">
    </div>

    <div class="col-md-3 mb-3">
      <label class="form-label">Age</label>
      <input type="number" name="age" class="form-control" value="<?= esc($patient['age']) ?>" required>
    </div>

    <div class="col-md-3 mb-3">
      <label class="form-label">Gender</label>
      <select name="gender" class="form-select" required>
        <option value="male" <?= $patient['gender'] == 'male' ? 'selected' : '' ?>>Male</option>
        <option value="female" <?= $patient['gender'] == 'female' ? 'selected' : '' ?>>Female</option>
        <option value="other" <?= $patient['gender'] == 'other' ? 'selected' : '' ?>>Other</option>
      </select>
    </div>
  </div>

  <div class="row">
    <div class="col-md-6 mb-3">
      <label class="form-label">Hospital</label>
      <select name="hospital_id" class="form-select" required>
        <option value="">-- Select Hospital --</option>
        <?php foreach ($hospitals as $h): ?>
          <option value="<?= $h['id'] ?>" <?= ($patient['hospital_id'] == $h['id']) ? 'selected' : '' ?>>
            <?= esc($h['name']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="col-md-6 mb-3">
      <label class="form-label">Status</label>
      <select name="status" class="form-select" required>
        <option value="active" <?= $patient['status'] == 'active' ? 'selected' : '' ?>>Active</option>
        <option value="inactive" <?= $patient['status'] == 'inactive' ? 'selected' : '' ?>>Inactive</option>
      </select>
    </div>
  </div>

  <button type="submit" class="btn btn-primary">Update Patient</button>
  <a href="<?= site_url('superadmin/listPatients') ?>" class="btn btn-secondary">Cancel</a>
</form>

<?= $this->endSection() ?>
