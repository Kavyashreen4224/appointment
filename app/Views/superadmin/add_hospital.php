<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Hospital</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <h3>Add Hospital</h3>

  <?php if(session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger">
      <ul>
      <?php foreach(session()->getFlashdata('errors') as $error): ?>
        <li><?= esc($error) ?></li>
      <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <form method="post" action="<?= site_url('superadmin/saveHospital') ?>">
    <div class="mb-3">
      <label>Name</label>
      <input type="text" name="name" class="form-control" required value="<?= old('name') ?>">
    </div>
    <div class="mb-3">
      <label>Address</label>
      <textarea name="address" class="form-control" required><?= old('address') ?></textarea>
    </div>
    <div class="mb-3">
      <label>Contact</label>
      <input type="text" name="contact" class="form-control" required value="<?= old('contact') ?>">
    </div>
    <div class="mb-3">
      <label>Email</label>
      <input type="email" name="email" class="form-control" required value="<?= old('email') ?>">
    </div>
    <button type="submit" class="btn btn-success">Save</button>
    <a href="<?= site_url('superadmin/listHospitals') ?>" class="btn btn-secondary">Cancel</a>
  </form>
</div>
</body>
</html>
