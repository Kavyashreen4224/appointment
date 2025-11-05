<?= $this->extend('layouts/patient_layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
  <h3>Bill Details</h3>

  <div class="card p-3 mb-3">
    <p><strong>Doctor:</strong> <?= esc($bill['doctor_name']) ?></p>
    <p><strong>Patient:</strong> <?= esc($bill['patient_name']) ?></p>
    <p><strong>Consultation Fee:</strong> ₹<?= esc($bill['consultation_fee']) ?></p>
    <p><strong>Total Amount:</strong> ₹<?= esc($bill['total_amount']) ?></p>
    <p><strong>Status:</strong> <?= esc($bill['payment_status']) ?></p>
    <p><strong>Payment Mode:</strong> <?= esc($bill['payment_mode'] ?? 'Not Paid') ?></p>
  </div>

  <?php if (!empty($services)): ?>
    <table class="table table-bordered">
      <thead class="table-dark">
        <tr>
          <th>Service</th>
          <th>Price</th>
          <th>Quantity</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($services as $s): ?>
          <tr>
            <td><?= esc($s['service_name']) ?></td>
            <td>₹<?= esc($s['price']) ?></td>
            <td><?= esc($s['quantity']) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p class="text-muted">No service details available.</p>
  <?php endif; ?>

  <a href="<?= site_url('patient/appointments') ?>" class="btn btn-secondary mt-3">Back</a>
</div>

<?= $this->endSection() ?>
