<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= $title ?? 'Admin Panel' ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      background-color: #f8f9fa;
    }
    .sidebar {
      min-height: 100vh;
      background-color: #343a40;
      color: #fff;
      padding-top: 20px;
    }
    .sidebar a {
      color: #ddd;
      display: block;
      padding: 10px 20px;
      text-decoration: none;
    }
    .sidebar a:hover {
      background-color: #495057;
      color: #fff;
    }
    .active-link {
      background-color: #0d6efd;
      color: #fff !important;
    }
  </style>
</head>
<body>

  <!-- Navbar -->
  <nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand ms-3" href="<?= site_url('admin/dashboard') ?>">Admin Panel</a>
      <div class="d-flex me-3">
        <a href="<?= site_url('logout') ?>" class="btn btn-danger btn-sm">Logout</a>
      </div>
    </div>
  </nav>

  <div class="container-fluid">
    <div class="row">
      <!-- Sidebar -->
      <div class="col-md-3 col-lg-2 sidebar">
        <a href="<?= site_url('admin/dashboard') ?>" class="<?= (uri_string()=='admin/dashboard') ? 'active-link' : '' ?>">Dashboard</a>
        <a href="<?= site_url('admin/listDoctors') ?>" class="<?= (uri_string()=='admin/listDoctors') ? 'active-link' : '' ?>">Doctors</a>
        <a href="<?= site_url('admin/listPatients') ?>" class="<?= (uri_string()=='admin/listPatients') ? 'active-link' : '' ?>">Patients</a>
        <a href="<?= site_url('admin/listAppointments') ?>" class="<?= (uri_string()=='admin/listAppointments') ? 'active-link' : '' ?>">Appointments</a>
        <a href="<?= site_url('logout') ?>" class="<?= (uri_string()=='logout') ? 'active-link' : '' ?>">Logout</a>
      </div>

      <!-- Main content -->
      <div class="col-md-9 col-lg-10 p-4">
        <?= $this->renderSection('content') ?>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
