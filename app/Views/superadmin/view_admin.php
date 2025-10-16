<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?= esc($title) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
.profile-card {
  max-width: 500px;
  margin: 50px auto;
  border-radius: 15px;
  box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}
.profile-header {
  background: linear-gradient(90deg, #007bff, #6610f2);
  color: white;
  text-align: center;
  padding: 30px 20px;
  border-radius: 15px 15px 0 0;
}
.profile-body {
  padding: 20px;
}
.profile-body p {
  font-size: 16px;
  margin-bottom: 10px;
}
.back-btn {
  display: block;
  text-align: center;
  margin-top: 20px;
}
</style>
</head>
<body>

<div class="profile-card bg-light">
  <div class="profile-header">
    <h3><?= esc($admin['name']) ?></h3>
    <p>Admin</p>
  </div>
  <div class="profile-body">
    <p><strong>Email:</strong> <?= esc($admin['email']) ?></p>
    <p><strong>Hospital:</strong> <?= esc($hospital['name'] ?? 'Not Assigned') ?></p>
    <p><strong>Created At:</strong> <?= esc($admin['created_at']) ?></p>
    <p><strong>Status:</strong> <?= $admin['deleted_at'] ? '<span class="text-danger">Inactive</span>' : '<span class="text-success">Active</span>' ?></p>
    <a href="<?= site_url('superadmin/listAdmins') ?>" class="btn btn-secondary back-btn">← Back to List</a>
  </div>
</div>

</body>
</html>
