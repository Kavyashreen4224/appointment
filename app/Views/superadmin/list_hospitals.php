<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Hospitals List</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">
  <h3 class="text-center mb-4">🏥 Hospitals Management</h3>

  <!-- ✅ Add Hospital Button -->
  <div class="d-flex justify-content-between mb-3">
    <a href="<?= site_url('superadmin/addHospital') ?>" class="btn btn-success">➕ Add Hospital</a>
    <a href="<?= site_url('superadmin/dashboard') ?>" class="btn btn-secondary">⬅ Back to Dashboard</a>
  </div>

  <!-- ✅ Success message -->
  <?php if(session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
  <?php endif; ?>

  <!-- ✅ Active Hospitals -->
  <div class="card shadow-sm mb-4">
    <div class="card-header bg-primary text-white">Active Hospitals</div>
    <div class="card-body">
      <table class="table table-bordered table-striped align-middle">
        <thead class="table-light">
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Address</th>
            <th>Contact</th>
            <th>Email</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if(!empty($hospitals)): ?>
            <?php foreach($hospitals as $h): ?>
              <tr>
                <td><?= $h['id'] ?></td>
                <td><a href="<?= site_url('superadmin/hospitalProfile/'.$h['id']) ?>"><?= esc($h['name']) ?></a></td>
                <td><?= esc($h['address']) ?></td>
                <td><?= esc($h['contact']) ?></td>
                <td><?= esc($h['email']) ?></td>
                <td><?= esc($h['status']) ?></td>
                <td>
                  <a href="<?= site_url('superadmin/editHospital/'.$h['id']) ?>" class="btn btn-warning btn-sm">Edit</a>
                  <a href="<?= site_url('superadmin/deleteHospital/'.$h['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this hospital?')">Delete</a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="7" class="text-center">No active hospitals found</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- 🗑️ Deleted Hospitals (Soft Deleted) -->
  <div class="card shadow-sm">
    <div class="card-header bg-danger text-white">Deleted Hospitals</div>
    <div class="card-body">
      <table class="table table-bordered table-striped align-middle">
        <thead class="table-light">
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Address</th>
            <th>Contact</th>
            <th>Email</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if(!empty($deletedHospitals)): ?>
            <?php foreach($deletedHospitals as $h): ?>
              <tr>
                <td><?= $h['id'] ?></td>
                <td><?= esc($h['name']) ?></td>
                <td><?= esc($h['address']) ?></td>
                <td><?= esc($h['contact']) ?></td>
                <td><?= esc($h['email']) ?></td>
                <td><?= esc($h['status']) ?></td>
                <td>
                  <a href="<?= site_url('superadmin/restoreHospital/'.$h['id']) ?>" class="btn btn-success btn-sm">Restore</a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="7" class="text-center">No deleted hospitals found</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

</div>

</body>
</html>
