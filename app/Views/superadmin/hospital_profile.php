<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Hospital Profile</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">
  <h2 class="text-center mb-4">🏥 <?= esc($hospital['name']) ?> - Profile</h2>

  <div class="card shadow-sm mb-4">
    <div class="card-body">
      <h5 class="card-title"><?= esc($hospital['name']) ?></h5>
      <p><strong>Address:</strong> <?= esc($hospital['address']) ?></p>
      <p><strong>Contact:</strong> <?= esc($hospital['contact']) ?></p>
      <p><strong>Email:</strong> <?= esc($hospital['email']) ?></p>
      <p><strong>Status:</strong> <?= esc($hospital['status']) ?></p>
    </div>
  </div>

  <div class="d-flex justify-content-around">
    <a href="<?= site_url('superadmin/listDoctors/'.$hospital['id']) ?>" class="btn btn-primary btn-lg">👨‍⚕️ Manage Doctors</a>
    <a href="<?= site_url('superadmin/managePatients/'.$hospital['id']) ?>" class="btn btn-success btn-lg">🧍‍♀️ Manage Patients</a>
    <a href="<?= site_url('superadmin/manageAppointments/'.$hospital['id']) ?>" class="btn btn-info btn-lg">📅 Manage Appointments</a>
  </div>

  <h3>Hospital Admins</h3>

<?php if (!empty($admins)): ?>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>#</th>
            <th>Admin Name</th>
            <th>Email</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($admins as $i => $admin): ?>
        <tr>
            <td><?= $i + 1 ?></td>
           <td>
  <a href="<?= site_url('superadmin/adminProfiles/'.$admin['id']) ?>">
    <?= esc($admin['name']) ?>
  </a>
</td>

            <td><?= esc($admin['email'] ?? 'N/A') ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php else: ?>
<p>No admins found for this hospital.</p>
<?php endif; ?>


  <div class="text-center mt-4">
    <a href="<?= site_url('superadmin/listHospitals') ?>" class="btn btn-secondary">⬅ Back to Hospital List</a>
  </div>


</div>

</body>
</html>
