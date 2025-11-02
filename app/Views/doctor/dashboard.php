<?= $this->extend('layouts/doctor_layout') ?>
<?= $this->section('content') ?>

<h2 class="mb-4">Welcome, Dr. <?= esc($doctor['doctor_name']) ?> 👋</h2>

<?php if (!empty($hospital)): ?>
  <div class="alert alert-info">
    <strong>Hospital:</strong> <?= esc($hospital['name']) ?><br>
    <strong>Address:</strong> <?= esc($hospital['address']) ?><br>
    <strong>Contact:</strong> <?= esc($hospital['contact']) ?><br>
    <strong>Email:</strong> <?= esc($hospital['email']) ?>
  </div>
<?php endif; ?>

<div class="row g-4">
  <div class="col-md-6">
    <div class="card text-center p-3 shadow-sm">
      <h5>Total Appointments</h5>
      <h2><?= esc($appointmentCount) ?></h2>
      <a href="<?= site_url('doctor/appointments') ?>" class="btn btn-primary btn-sm mt-2">View</a>
    </div>
  </div>

  <div class="col-md-6">
    <div class="card text-center p-3 shadow-sm">
      <h5>Patients Seen</h5>
      <h2><?= esc($patientCount) ?></h2>
      <a href="<?= site_url('doctor/patients') ?>" class="btn btn-primary btn-sm mt-2">View</a>
    </div>
  </div>
</div>

<h4 class="mt-5">Upcoming Appointments</h4>
<table class="table table-bordered bg-white">
  <thead class="table-dark">
    <tr>
      <th>Patient</th>
      <th>Start Time</th>
      <th>End Time</th>
      <th>Status</th>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($upcomingAppointments)): ?>
      <?php foreach ($upcomingAppointments as $app): ?>
        <tr>
          <td><?= esc($app['patient_name']) ?></td>
          <td><?= esc($app['start_datetime']) ?></td>
          <td><?= esc($app['end_datetime']) ?></td>
          <td><?= esc(ucfirst($app['status'])) ?></td>
        </tr>
      <?php endforeach; ?>
    <?php else: ?>
      <tr><td colspan="4" class="text-center">No upcoming appointments</td></tr>
    <?php endif; ?>
  </tbody>
</table>

<?= $this->endSection() ?>
