<?= $this->extend('layouts/admin_layout') ?>
<?= $this->section('content') ?>

<h2 class="mb-4">Edit Doctor</h2>

<form action="<?= site_url('admin/updateDoctor/' . $doctor['doctor_id']) ?>" method="post" class="bg-white p-4 rounded shadow-sm">
  <input type="hidden" name="user_id" value="<?= $doctor['id'] ?>">

  <div class="row">
    <div class="col-md-6 mb-3">
      <label>Name</label>
      <input type="text" name="name" value="<?= esc($doctor['name']) ?>" class="form-control" required>
    </div>
    <div class="col-md-6 mb-3">
      <label>Email</label>
      <input type="email" name="email" value="<?= esc($doctor['email']) ?>" class="form-control" required>
    </div>
    <div class="col-md-3 mb-3">
      <label>Age</label>
      <input type="number" name="age" value="<?= esc($doctor['age']) ?>" class="form-control" required>
    </div>
    <div class="col-md-3 mb-3">
      <label>Gender</label>
      <select name="gender" class="form-control" required>
        <option value="male" <?= $doctor['gender'] == 'male' ? 'selected' : '' ?>>Male</option>
        <option value="female" <?= $doctor['gender'] == 'female' ? 'selected' : '' ?>>Female</option>
        <option value="other" <?= $doctor['gender'] == 'other' ? 'selected' : '' ?>>Other</option>
      </select>
    </div>
    <div class="col-md-6 mb-3">
      <label>Expertise</label>
      <input type="text" name="expertise" value="<?= esc($doctor['expertise']) ?>" class="form-control" required>
    </div>
    <div class="mb-3">
            <label>Availability Type</label>
            <select name="availability_type" class="form-control" required>
                <option value="">Select</option>
                <option value="fixed" <?= (isset($doctor['availability_type']) && $doctor['availability_type']=='fixed')?'selected':'' ?>>Fixed</option>
                <option value="dynamic" <?= (isset($doctor['availability_type']) && $doctor['availability_type']=='dynamic')?'selected':'' ?>>Dynamic</option>
            </select>
        </div>
  </div>

  <button type="submit" class="btn btn-primary">Update</button>
  <a href="<?= site_url('admin/listDoctors') ?>" class="btn btn-secondary">Cancel</a>
</form>

<?= $this->endSection() ?>
