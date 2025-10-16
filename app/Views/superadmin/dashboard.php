<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>SuperAdmin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="#">SuperAdmin Dashboard</a>
    <div class="ms-auto">
      <a href="<?= site_url('logout') ?>" class="btn btn-danger">Logout</a>
    </div>
  </div>
</nav>

<div class="container mt-4">
  <div class="row g-4">
    <div class="col-md-4">
      <div class="card text-center p-3">
        <h5>Hospitals</h5>
        <h2><?= $hospitalCount ?></h2>
        <a href="<?= site_url('superadmin/listHospitals') ?>" class="btn btn-primary btn-sm mt-2">Manage</a>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card text-center p-3">
        <h5>Admins</h5>
        <h2><?= $adminCount ?></h2>
        <a href="<?= site_url('superadmin/listAdmins') ?>" class="btn btn-primary btn-sm mt-2">Manage</a>
      </div>
    </div>
  </div>
</div>
</body>
</html>
