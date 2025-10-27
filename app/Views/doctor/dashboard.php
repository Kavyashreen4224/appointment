<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Doctor Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .card-link {
      text-decoration: none;
      color: inherit;
    }
    .card-link:hover .card {
      transform: scale(1.03);
      transition: transform 0.2s ease-in-out;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.15);
    }
  </style>
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container">
    <a class="navbar-brand" href="#">Doctor Dashboard</a>
    <div class="ms-auto">
      <a href="<?= site_url('doctor/patients') ?>" class="btn btn-light btn-sm">Patients</a>
      <a href="<?= site_url('doctor/appointments') ?>" class="btn btn-light btn-sm">Appointments</a>
      <a href="<?= site_url('logout') ?>" class="btn btn-danger btn-sm">Logout</a>
    </div>
  </div>
</nav>

<div class="container mt-5">
  <h3>Welcome Dr. <?= esc($doctor['name']) ?></h3>
  <p class="text-muted">Email: <?= esc($doctor['email']) ?></p>
  <p class="text-muted">Hospital: <?= esc($doctor['hospital_name']) ?></p>

  <div class="row mt-4">
    <!-- ✅ Clickable Patients Card -->
    <div class="col-md-4">
      <a href="<?= site_url('doctor/patients') ?>" class="card-link">
        <div class="card text-center shadow-sm">
          <div class="card-body">
            <h4 class="card-title text-primary"><?= esc($total_patients) ?></h4>
            <p class="card-text">Total Patients</p>
          </div>
        </div>
      </a>
    </div>

    <!-- ✅ Clickable Appointments Card -->
    <div class="col-md-4">
      <a href="<?= site_url('doctor/appointments') ?>" class="card-link">
        <div class="card text-center shadow-sm">
          <div class="card-body">
            <h4 class="card-title text-success"><?= esc($total_appointments) ?></h4>
            <p class="card-text">Appointments</p>
          </div>
        </div>
      </a>
    </div>
  </div>
</div>

</body>
</html>

