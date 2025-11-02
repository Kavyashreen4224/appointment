<?= $this->extend('layouts/admin_layout') ?>
<?= $this->section('content') ?>

<h2 class="mb-4">Doctors List</h2>

<?php if (session()->getFlashdata('success')): ?>
  <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>

<a href="<?= site_url('admin/addDoctor') ?>" class="btn btn-primary mb-3">+ Add Doctor</a>

<table class="table table-bordered table-striped bg-white">
  <thead class="table-dark">
    <tr>
      <th>#</th>
      <th>Name</th>
      <th>Email</th>
      <th>Age</th>
      <th>Gender</th>
      <th>Expertise</th>
      <th>Availability</th>
      <th>Status</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($doctors)): ?>
      <?php foreach ($doctors as $i => $doctor): ?>
        <tr>
          <td><?= $i + 1 ?></td>
          <td><?= esc($doctor['name']) ?></td>
          <td><?= esc($doctor['email']) ?></td>
          <td><?= esc($doctor['age']) ?></td>
          <td><?= ucfirst($doctor['gender']) ?></td>
          <td><?= esc($doctor['expertise']) ?></td>
          <td><?= ucfirst($doctor['availability_type']) ?></td>
          <td>
            <span class="badge <?= $doctor['status'] == 'active' ? 'bg-success' : 'bg-secondary' ?>">
              <?= ucfirst($doctor['status']) ?>
            </span>
          </td>
          <td>
            <a href="<?= site_url('admin/editDoctor/' . $doctor['doctor_id']) ?>" class="btn btn-warning btn-sm">Edit</a>
            <a href="<?= site_url('admin/deleteDoctor/' . $doctor['doctor_id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this doctor?')">Delete</a>
            <a href="<?= site_url('admin/viewDoctor/' . $doctor['doctor_id']) ?>" class="btn btn-info btn-sm">View Profile</a>

          </td>
        </tr>
      <?php endforeach; ?>
    <?php else: ?>
      <tr><td colspan="9" class="text-center">No doctors found.</td></tr>
    <?php endif; ?>
  </tbody>
</table>

<?= $this->endSection() ?>
