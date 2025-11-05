<?= $this->extend('layouts/doctor_layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
  <h3>Doctor Profile</h3>

  <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
  <?php endif; ?>

  <form action="<?= site_url('doctor/updateProfile') ?>" method="post" class="bg-white p-4 rounded shadow-sm">
    <div class="row mb-3">
      <div class="col-md-6">
        <label class="form-label">Name</label>
        <input type="text" name="name" class="form-control" value="<?= esc($doctor['name']) ?>" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="<?= esc($doctor['email']) ?>" required>
      </div>
    </div>

    <div class="row mb-3">
      <div class="col-md-4">
        <label class="form-label">Age</label>
        <input type="number" name="age" class="form-control" value="<?= esc($doctor['age']) ?>">
      </div>
      <div class="col-md-4">
        <label class="form-label">Gender</label>
        <select name="gender" class="form-select">
          <option value="male" <?= $doctor['gender'] === 'male' ? 'selected' : '' ?>>Male</option>
          <option value="female" <?= $doctor['gender'] === 'female' ? 'selected' : '' ?>>Female</option>
          <option value="other" <?= $doctor['gender'] === 'other' ? 'selected' : '' ?>>Other</option>
        </select>
      </div>
      <div class="col-md-4">
        <label class="form-label">Hospital</label>
        <input type="text" class="form-control" value="<?= esc($doctor['hospital_name']) ?>" readonly>
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label">Expertise</label>
      <input type="text" name="expertise" class="form-control" value="<?= esc($doctor['expertise']) ?>">
    </div>

    <div class="mb-3">
      <label class="form-label">Availability Type</label>
      <select name="availability_type" class="form-select">
        <option value="fixed" <?= $doctor['availability_type'] === 'fixed' ? 'selected' : '' ?>>Fixed</option>
        <option value="dynamic" <?= $doctor['availability_type'] === 'dynamic' ? 'selected' : '' ?>>Dynamic</option>
      </select>
    </div>

    <button type="submit" class="btn btn-primary">Update Profile</button>
  </form>
</div>

<?= $this->endSection() ?>
