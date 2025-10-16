<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Appointments List</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {background: #eef2f7; font-family: 'Poppins', sans-serif;}
.table-container {margin-top: 50px;}
.card {border-radius: 12px; padding: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.2);}
</style>
</head>
<body>
<div class="container table-container">
  <div class="card">
    <h3 class="text-center mb-4">Appointments List</h3>

    <?php if(session()->getFlashdata('success')): ?>
      <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <a href="<?= site_url('superadmin/addAppointment') ?>" class="btn btn-primary mb-3">Add Appointment</a>

    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>ID</th>
          <th>Doctor</th>
          <th>Patient</th>
          <th>Start</th>
          <th>End</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($appointments as $a): ?>
        <tr>
          <td><?= $a['id'] ?></td>
          <td><?= $a['doctor_name'] ?> (<?= $a['expertise'] ?? '' ?>)</td>
          <td><?= $a['patient_name'] ?></td>
          <td><?= $a['start_datetime'] ?></td>
          <td><?= $a['end_datetime'] ?></td>
          <td><?= ucfirst($a['status']) ?></td>
          <td>
            <a href="<?= site_url('superadmin/editAppointment/'.$a['id']) ?>" class="btn btn-warning btn-sm">Edit</a>
            <a href="<?= site_url('superadmin/cancelAppointment/'.$a['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Cancel</a>
            <?php if($a['status']=='pending'): ?>
            <a href="<?= site_url('superadmin/completeAppointment/'.$a['id']) ?>" class="btn btn-success btn-sm">Complete</a>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
</body>
</html>
