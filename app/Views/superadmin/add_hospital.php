<!DOCTYPE html>
<html>
<head>
  <title>Add Hospital</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
  <h2>Add Hospital</h2>
  <form method="post" action="<?= site_url('superadmin/saveHospital') ?>">
    <div class="mb-3">
      <label>Name</label>
      <input type="text" name="name" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Address</label>
      <textarea name="address" class="form-control" required></textarea>
    </div>
    <div class="mb-3">
      <label>Contact</label>
      <input type="text" name="contact" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Email</label>
      <input type="email" name="email" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Save</button>
    <a href="<?= site_url('superadmin/listHospitals') ?>" class="btn btn-secondary">Back</a>
  </form>
</div>
</body>
</html>
