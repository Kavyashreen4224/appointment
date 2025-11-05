<?= $this->extend('layouts/doctor_layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h2>Prescription Details</h2>

    <div class="card mb-3">
        <div class="card-body">
            <h5>Patient: <?= esc($patient['patient_name']) ?></h5>
            <p><strong>Email:</strong> <?= esc($patient['email']) ?></p>
            <p><strong>Appointment ID:</strong> <?= esc($visit['appointment_id'] ?? 'N/A') ?></p>
            <p><strong>Date:</strong> <?= esc($visit['created_at'] ?? '') ?></p>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h5>Prescription:</h5>
            <p><?= nl2br(esc($prescription['notes'])) ?></p>
            <?php if (!empty($items)): ?>
  <h5 class="mt-4">Prescribed Medicines</h5>
  <table class="table table-bordered bg-white">
    <thead class="table-light">
      <tr>
        <th>#</th>
        <th>Medicine</th>
        <th>Dosage</th>
        <th>Frequency</th>
        <th>Duration</th>
        <th>Instructions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($items as $index => $item): ?>
        <tr>
          <td><?= $index + 1 ?></td>
          <td><?= esc($item['medicine_name']) ?></td>
          <td><?= esc($item['dosage']) ?></td>
          <td><?= esc($item['frequency']) ?></td>
          <td><?= esc($item['duration']) ?></td>
          <td><?= esc($item['usage_instruction']) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php endif; ?>

        </div>
    </div>

    <a href="<?= site_url('doctor/appointments') ?>" class="btn btn-secondary mt-3">Back to Appointments</a>
</div>

<?= $this->endSection() ?>
