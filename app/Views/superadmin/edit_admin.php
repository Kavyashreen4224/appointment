<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {background: #eef2f7; font-family: 'Poppins', sans-serif;}
.card {border-radius: 12px; padding: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.2);}
</style>
</head>
<body>
<div class="container mt-5">
  <div class="card mx-auto" style="max-width:600px;">
    <h3 class="text-center mb-4">Edit Admin</h3>
    <?php if(session()->getFlashdata('success')): ?>
      <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <form action="<?= site_url('superadmin/updateAdmin/'.$admin['id']) ?>" method="post">
      <div class="mb-3">
        <label class="form-label">Full Name</label>
        <input type="text" name="name" class="form-control" value="<?= $admin['name'] ?>" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="<?= $admin['email'] ?>" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Password (Leave blank if no change)</label>
        <input type="password" name="password" class="form-control" placeholder="Password">
      </div>
      <div class="mb-3">
        <label class="form-label">Assign Hospital</label>
        <select name="hospital_id" class="form-select" required>
          <option value="">Select Hospital</option>
          <?php foreach($hospitals as $hospital): ?>
            <option value="<?= $hospital['id'] ?>" <?= $hospital['id']==$admin['hospital_id']?'selected':'' ?>><?= $hospital['name'] ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <button type="submit" class="btn btn-primary w-100">Update Admin</button>
      <a href="<?= site_url('superadmin/listAdmins') ?>" class="btn btn-secondary w-100 mt-2">Back</a>
    </form>
  </div>
</div>
</body>
</html>
