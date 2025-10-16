<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Patients List</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3>My Patients</h3>
    <a href="<?= site_url('doctor/addPatient') ?>" class="btn btn-primary">Add Patient</a>
  </div>

  <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
  <?php endif; ?>

  <table class="table table-bordered bg-white">
    <thead class="table-primary">
      <tr>
        <th>#</th>
        <th>Name</th>
        <th>Email</th>
        <th>Age</th>
        <th>Gender</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($patients): ?>
        <?php foreach ($patients as $p): ?>
          <tr>
            <td><?= esc($p['id']) ?></td>
            <td><?= esc($p['name']) ?></td>
            <td><?= esc($p['email']) ?></td>
            <td><?= esc($p['age']) ?></td>
            <td><?= esc($p['gender']) ?></td>
            <td>
              <a href="<?= site_url('doctor/editPatient/' . $p['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
              <a href="<?= site_url('doctor/deletePatient/' . $p['id']) ?>" class="btn btn-sm btn-danger"
                 onclick="return confirm('Are you sure you want to delete this patient?')">Delete</a>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr><td colspan="6" class="text-center">No patients found.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>

  <a href="<?= site_url('doctor/dashboard') ?>" class="btn btn-secondary mt-3">Back to Dashboard</a>
</div>

</body>
</html>
