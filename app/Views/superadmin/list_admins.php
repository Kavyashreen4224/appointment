<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admins List</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
  <h3>Admins</h3>
  <a href="<?= site_url('superadmin/addAdmin') ?>" class="btn btn-success mb-3">Add Admin</a>
  <?php if(session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
  <?php endif; ?>
  <table class="table table-bordered table-striped">
    <thead>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Hospital</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($admins as $a): ?>
      <tr>
        <td><?= $a['id'] ?></td>
       <td><a href="<?= site_url('superadmin/viewAdmin/'.$a['id']) ?>" class="text-decoration-none fw-bold text-primary"><?= esc($a['name']) ?></a></td>
        <td><?= $a['email'] ?></td>
        <td><?= $a['hospital_id'] ?></td>
        <td>
          <a href="<?= site_url('superadmin/editAdmin/'.$a['id']) ?>" class="btn btn-warning btn-sm">Edit</a>
          <a href="<?= site_url('superadmin/deleteAdmin/'.$a['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <a href="<?= site_url('superadmin/dashboard') ?>" class="btn btn-secondary mt-2">Back to Dashboard</a>
</div>
</body>
</html>
