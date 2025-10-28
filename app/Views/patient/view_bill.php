<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Bill</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <div class="card shadow p-4">
    <h3 class="mb-3 text-center text-primary">Hospital Bill Summary</h3>

    <div class="mb-3">
      <p><strong>Patient:</strong> <?= esc($patient['patient_name']) ?></p>
      <p><strong>Doctor:</strong> <?= esc($doctor['doctor_name']) ?></p>
      <p><strong>Date:</strong> <?= date('d M Y, h:i A', strtotime($visit['created_at'])) ?></p>
    </div>

    <hr>

    <h5 class="text-secondary mb-3">Charges Breakdown</h5>
    <table class="table table-bordered table-striped">
      <thead class="table-light">
        <tr>
          <th>Service</th>
          <th class="text-end">Amount (₹)</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Consultation Fee</td>
          <td class="text-end"><?= number_format($bill['consultation_fee'] ?? 0, 2) ?></td>
        </tr>

        <?php if (!empty($billServices)): ?>
          <?php foreach ($billServices as $srv): ?>
            <tr>
              <td><?= esc($srv['service_name']) ?></td>
              <td class="text-end"><?= number_format($srv['price'], 2) ?></td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="2" class="text-center text-muted">No additional services added</td>
          </tr>
        <?php endif; ?>
      </tbody>
      <tfoot class="table-light">
        <tr>
          <th>Total</th>
          <th class="text-end text-success">₹<?= number_format($bill['total_amount'] ?? 0, 2) ?></th>
        </tr>
      </tfoot>
    </table>

    <div class="mt-3">
      <p><strong>Payment Mode:</strong> <?= esc($bill['payment_mode']) ?></p>
      <p><strong>Payment Status:</strong> 
        <span class="<?= $bill['payment_status'] === 'Paid' ? 'text-success' : 'text-danger' ?>">
          <?= esc($bill['payment_status']) ?>
        </span>
      </p>
      <?php if (!empty($bill['payment_date'])): ?>
        <p><strong>Payment Date:</strong> <?= date('d M Y, h:i A', strtotime($bill['payment_date'])) ?></p>
      <?php endif; ?>
    </div>

    <div class="text-center mt-4">
      <a href="<?= site_url('patient/dashboard') ?>" class="btn btn-secondary">Back to Dashboard</a>
    </div>
  </div>
</div>

</body>
</html>
