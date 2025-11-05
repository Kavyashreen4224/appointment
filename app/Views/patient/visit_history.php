<?= $this->extend('layouts/patient_layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
  <h3 class="mb-3">Visit History</h3>

  <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
  <?php endif; ?>

  <?php if (!empty($visits)): ?>
    <div class="table-responsive">
      <table class="table table-bordered bg-white">
        <thead class="table-dark">
          <tr>
            <th>Date</th>
            <th>Doctor</th>
            <th>Complaints</th>
            <th>Diagnosis</th>
            <th>Weight (kg)</th>
            <th>BP</th>
            <th>Comments</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($visits as $v): ?>
            <tr>
              <td><?= date('d M Y', strtotime($v['created_at'])) ?></td>
              <td><?= esc($v['doctor_name'] ?? 'N/A') ?></td>
              <td>
  <?php
  $complaints = $v['complaints_data'] ?? $v['complaints'];
  if (is_string($complaints)) {
      $complaints = explode(',', $complaints);
  } elseif (!is_array($complaints)) {
      $complaints = [];
  }
  ?>
  <ul class="mb-0">
    <?php foreach ($complaints as $item): ?>
      <?php if (is_array($item) && isset($item['complaint'])): ?>
        <li><?= esc($item['complaint']) ?></li>
      <?php else: ?>
        <li><?= esc(trim($item)) ?></li>
      <?php endif; ?>
    <?php endforeach; ?>
  </ul>
</td>

<td>
  <?php
  $diagnosis = $v['complaints_data'] ?? $v['diagnosis'];
  if (is_string($diagnosis)) {
      $diagnosis = explode(',', $diagnosis);
  } elseif (!is_array($diagnosis)) {
      $diagnosis = [];
  }
  ?>
  <ul class="mb-0">
    <?php foreach ($diagnosis as $item): ?>
      <?php if (is_array($item) && isset($item['diagnosis'])): ?>
        <li><?= esc($item['diagnosis']) ?></li>
      <?php else: ?>
        <li><?= esc(trim($item)) ?></li>
      <?php endif; ?>
    <?php endforeach; ?>
  </ul>
</td>

         
              <td><?= esc($v['weight']) ?></td>
              <td><?= esc($v['blood_pressure']) ?></td>
              <td><?= esc($v['doctor_comments']) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php else: ?>
    <p class="text-muted text-center mt-3">No visit history available.</p>
  <?php endif; ?>
</div>

<?= $this->endSection() ?>
