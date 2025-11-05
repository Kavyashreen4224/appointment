<?= $this->extend('layouts/patient_layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
  <h3>Prescription</h3>

  <div class="card p-3 mb-3">
    <p><strong>Doctor:</strong> <?= esc($prescription['doctor_name']) ?></p>
    <p><strong>Notes:</strong> <?= nl2br(esc($prescription['notes'])) ?></p>
  </div>

  <?php if (!empty($items)): ?>
    <table class="table table-bordered">
      <thead class="table-dark">
        <tr>
          <th>Medicine</th>
          <th>Dosage</th>
          <th>Frequency</th>
          <th>Duration</th>
          <th>Instructions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($items as $item): ?>
          <tr>
            <td><?= esc($item['medicine_name']) ?></td>
            <td><?= esc($item['dosage']) ?></td>
            <td><?= esc($item['frequency']) ?></td>
            <td><?= esc($item['duration']) ?></td>
            <td><?= esc($item['usage_instruction']) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p class="text-muted">No medicines prescribed.</p>
  <?php endif; ?>

  <a href="<?= site_url('patient/appointments') ?>" class="btn btn-secondary mt-3">Back</a>
</div>

<?= $this->endSection() ?>
