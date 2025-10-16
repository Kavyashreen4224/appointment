<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Profile</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {background: #f4f6f9; font-family: 'Poppins', sans-serif;}
.card {border-radius: 12px; padding: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.2);}
</style>
</head>
<body>
<div class="container mt-5">
  <div class="card mx-auto" style="max-width:600px;">
    <h3 class="text-center mb-4">Admin Profile</h3>
    <table class="table table-bordered">
      <tr><th>ID</th><td><?= $admin['id'] ?></td></tr>
      <tr><th>Name</th><td><?= $admin['name'] ?></td></tr>
      <tr><th>Email</th><td><?= $admin['email'] ?></td></tr>
      <tr><th>Hospital</th><td><?= $hospital['name'] ?? 'N/A' ?></td></tr>
      <tr><th>Role</th><td><?= $admin['role'] ?></td></tr>
      <tr><th>Created At</th><td><?= $admin['created_at'] ?></td></tr>
    </table>
    <a href="<?= site_url('superadmin/listAdmins') ?>" class="btn btn-secondary mt-2">Back to List</a>
  </div>
</div>
</body>
</html>
