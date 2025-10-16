<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Hospital</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <h3>Edit Hospital</h3>
  <form method="post" action="<?= site_url('superadmin/updateHospital/'.$hospital['id']) ?>">
    <div class="mb-3">
      <label>Name</label>
      <input type="text" name="name" class="form-control" value="<?= esc($hospital['name']) ?>" required>
    </div>
    <div class="mb-3">
      <label>Address</label>
      <textarea name="address" class="form-control" required><?= esc($hospital['address']) ?></textarea>
    </div>
    <div class="mb-3">
      <label>Contact</label>
      <input type="text" name="contact" class="form-control" value="<?= esc($hospital['contact']) ?>" required>
    </div>
    <div class="mb-3">
      <label>Email</label>
      <input type="email" name="email" class="form-control" value="<?= esc($hospital['email']) ?>" required>
    </div>
    <div class="mb-3">
      <label>Status</label>
      <select name="status" class="form-select">
        <option value="Active" <?= $hospital['status'] == 'Active' ? 'selected' : '' ?>>Active</option>
        <option value="Inactive" <?= $hospital['status'] == 'Inactive' ? 'selected' : '' ?>>Inactive</option>
      </select>
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
    <a href="<?= site_url('superadmin/listHospitals') ?>" class="btn btn-secondary">Cancel</a>
  </form>
</div>
</body>
</html>
