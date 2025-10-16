<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Doctor Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
    <div class="col-md-4">
      <div class="card text-center shadow-sm">
        <div class="card-body">
          <h4 class="card-title text-primary"><?= esc($total_patients) ?></h4>
          <p class="card-text">Total Patients</p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card text-center shadow-sm">
        <div class="card-body">
          <h4 class="card-title text-success"><?= esc($total_appointments) ?></h4>
          <p class="card-text">Appointments</p>
        </div>
      </div>
    </div>
  </div>

  <h4 class="mt-5">Recent Appointments</h4>
  <table class="table table-bordered mt-3 bg-white">
    <thead class="table-primary">
      <tr>
        <th>#</th>
        <th>Patient Name</th>
        <th>Date</th>
        <th>Status</th>
        <th>View</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($appointments): ?>
        <?php foreach ($appointments as $a): ?>
          <tr>
            <td><?= esc($a['id']) ?></td>
            <td><?= esc($a['patient_name'] ?? 'N/A') ?></td>
            <td><?= esc($a['appointment_date'] ?? 'N/A') ?></td>
            <td>
              <span class="badge bg-<?= $a['status'] == 'completed' ? 'success' : 'warning' ?>">
                <?= esc(ucfirst($a['status'])) ?>
              </span>
            </td>
            <td><a href="<?= site_url('doctor/appointment/' . $a['id']) ?>" class="btn btn-sm btn-outline-primary">View</a></td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr><td colspan="5" class="text-center">No appointments yet.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

</body>
</html>
