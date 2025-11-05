<?= $this->extend('layouts/admin_layout') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
  <div>
    <h2 class="fw-bold text-primary mb-1">Welcome back, <?= esc($adminName) ?> 👋</h2>
    <p class="text-muted mb-2">You are managing <strong><?= esc($hospital['name']) ?></strong></p>
    <small class="text-secondary">Email: <?= esc($adminEmail) ?></small>
  </div>
  

</div>



<?php if (!empty($hospital)): ?>
  <div class="alert alert-info">
    <strong>Hospital:</strong> <?= esc($hospital['name']) ?><br>
    <strong>Address:</strong> <?= esc($hospital['address']) ?><br>
    <strong>Contact:</strong> <?= esc($hospital['contact']) ?><br>
    <strong>Email:</strong> <?= esc($hospital['email']) ?>
  </div>
<?php endif; ?>

<div class="row g-4">
  <div class="col-md-4">
    <div class="card text-center p-3 shadow-sm">
      <h5>Doctors</h5>
      <h2><?= esc($doctorCount) ?></h2>
      <a href="<?= site_url('admin/listDoctors') ?>" class="btn btn-primary btn-sm mt-2">Manage</a>
    </div>
  </div>

  <div class="col-md-4">
    <div class="card text-center p-3 shadow-sm">
      <h5>Patients</h5>
      <h2><?= esc($patientCount) ?></h2>
      <a href="<?= site_url('admin/listPatients') ?>" class="btn btn-primary btn-sm mt-2">Manage</a>
    </div>
  </div>

  <div class="col-md-4">
    <div class="card text-center p-3 shadow-sm">
      <h5>Appointments</h5>
      <h2><?= esc($appointmentCount) ?></h2>
      <a href="<?= site_url('admin/listAppointments') ?>" class="btn btn-primary btn-sm mt-2">Manage</a>
    </div>
  </div>
</div>

<?= $this->endSection() ?>
