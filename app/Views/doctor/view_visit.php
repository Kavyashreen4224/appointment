<?= $this->extend('layouts/doctor_layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
  <h3>Visit Details</h3>
  <hr>

  <?php if (!empty($visit)): ?>
    <div class="card p-3 shadow-sm">
      <h5 class="mb-3 text-primary">Visit Summary</h5>
      
      <p><strong>Appointment ID:</strong> <?= esc($visit['appointment_id']) ?></p>
      <p><strong>Patient Name:</strong> <?= esc($visit['patient_name']) ?></p>
      <p><strong>Doctor Name:</strong> <?= esc($visit['doctor_name']) ?></p>
      <p><strong>Visit Date:</strong> <?= date('d M Y, h:i A', strtotime($visit['created_at'])) ?></p>

      <hr>

      <h6 class="fw-bold">Complaints & Diagnosis</h6>
      <?php
        $complaints = json_decode($visit['complaints'], true);
        if (!empty($complaints)):
      ?>
        <ul>
          <?php foreach ($complaints as $cd): ?>
            <li><strong><?= esc($cd['complaint']) ?>:</strong> <?= esc($cd['diagnosis']) ?></li>
          <?php endforeach; ?>
        </ul>
      <?php else: ?>
        <p class="text-muted">No complaints recorded.</p>
      <?php endif; ?>

      <hr>

      <div class="row">
        <div class="col-md-4">
          <p><strong>Weight:</strong> <?= esc($visit['weight'] ?? '-') ?> kg</p>
        </div>
        <div class="col-md-4">
          <p><strong>Blood Pressure:</strong> <?= esc($visit['blood_pressure'] ?? '-') ?></p>
        </div>
      </div>

      <hr>

      <h6 class="fw-bold">Doctor’s Comments</h6>
      <p><?= esc($visit['doctor_comments'] ?? 'No comments provided.') ?></p>
    </div>

    <div class="mt-4">
      <a href="<?= site_url('doctor/appointments') ?>" class="btn btn-secondary">Back to Appointments</a>
    </div>

  <?php else: ?>
    <div class="alert alert-warning">No visit details found for this appointment.</div>
  <?php endif; ?>
</div>

<?= $this->endSection() ?>
