<?= $this->extend('layouts/admin_layout') ?>
<?= $this->section('content') ?>

<h2 class="mb-4">Add Doctor</h2>

<form action="<?= site_url('admin/saveDoctor') ?>" method="post" class="bg-white p-4 rounded shadow-sm">
  <div class="row">
    <div class="col-md-6 mb-3">
      <label>Name</label>
      <input type="text" name="name" class="form-control" required>
    </div>
    <div class="col-md-6 mb-3">
      <label>Email</label>
      <input type="email" name="email" class="form-control" required>
    </div>
    <div class="col-md-6 mb-3">
      <label>Password</label>
      <input type="password" name="password" class="form-control" required>
    </div>
    <div class="col-md-3 mb-3">
      <label>Age</label>
      <input type="number" name="age" class="form-control" required>
    </div>
    <div class="col-md-3 mb-3">
      <label>Gender</label>
      <select name="gender" class="form-control" required>
        <option value="">Select</option>
        <option value="male">Male</option>
        <option value="female">Female</option>
        <option value="other">Other</option>
      </select>
    </div>
    <div class="col-md-6 mb-3">
      <label>Expertise</label>
      <input type="text" name="expertise" class="form-control" required>
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

  <button type="submit" class="btn btn-success">Save</button>
  <a href="<?= site_url('admin/listDoctors') ?>" class="btn btn-secondary">Cancel</a>
</form>

<?= $this->endSection() ?>
