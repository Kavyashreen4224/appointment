<!DOCTYPE html>
<html>
<head>
    <title>Hospital Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="<?= site_url('admin/dashboard') ?>">Hospital Admin</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="<?= site_url('admin/patients') ?>">Patients</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= site_url('admin/doctors') ?>">Doctors</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= site_url('admin/appointments') ?>">Appointments</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= site_url('logout') ?>">Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

<?= $this->renderSection('content') ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
