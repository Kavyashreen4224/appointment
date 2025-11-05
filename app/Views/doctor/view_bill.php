<?= $this->extend('layouts/doctor_layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
  <h3>Bill Details</h3>

  <div class="card mb-3">
    <div class="card-body">
      <p><strong>Patient:</strong> <?= esc($bill['patient_name']) ?> (<?= esc($bill['patient_email']) ?>)</p>
      <p><strong>Appointment ID:</strong> <?= esc($bill['appointment_id']) ?></p>
      <p><strong>Status:</strong> <?= esc($bill['payment_status']) ?></p>
      <p><strong>Total Amount:</strong> ₹<?= esc(number_format($bill['total_amount'], 2)) ?></p>
    </div>
  </div>

  <h5>Services</h5>
  <table class="table table-bordered bg-white">
    <thead class="table-dark">
      <tr>
        <th>Service Name</th>
        <th>Price (₹)</th>
        <th>Quantity</th>
        <th>Subtotal (₹)</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($billServices as $s): ?>
        <tr>
          <td><?= esc($s['service_name']) ?></td>
          <td><?= esc($s['price']) ?></td>
          <td><?= esc($s['quantity']) ?></td>
          <td><?= esc(number_format($s['price'] * $s['quantity'], 2)) ?></td>
        </tr>
      <?php endforeach; ?>
      <tr>
        <td colspan="3" class="text-end fw-bold">Total</td>
        <td class="fw-bold">₹<?= esc(number_format($bill['total_amount'], 2)) ?></td>
      </tr>
    </tbody>
  </table>

  <a href="<?= site_url('doctor/appointments') ?>" class="btn btn-secondary mt-3">Back to Appointments</a>
</div>

<?= $this->endSection() ?>
