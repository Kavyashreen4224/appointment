<?= $this->extend('layouts/patient_layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
  <h3>Visit Details</h3>
  <div class="card p-3">

    <p><strong>Doctor:</strong> <?= esc($visit['doctor_name']) ?></p>

    <?php
      $pairs = json_decode($visit['complaints'], true);
      if (is_array($pairs)):
    ?>
      <table class="table table-bordered">
        <thead class="table-light">
          <tr>
            <th>Complaint</th>
            <th>Diagnosis</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($pairs as $pair): ?>
            <tr>
              <td><?= esc($pair['complaint']) ?></td>
              <td><?= esc($pair['diagnosis']) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p class="text-muted">No complaints recorded.</p>
    <?php endif; ?>

    <p><strong>Weight:</strong> <?= esc($visit['weight']) ?> kg</p>
    <p><strong>Blood Pressure:</strong> <?= esc($visit['blood_pressure']) ?></p>
    <p><strong>Doctor Comments:</strong> <?= esc($visit['doctor_comments']) ?></p>
  </div>

  <a href="<?= site_url('patient/appointments') ?>" class="btn btn-secondary mt-3">Back</a>
</div>

<?= $this->endSection() ?>
