<?= $this->extend('layouts/doctor_layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
  <h3>My Services</h3>
  <a href="<?= site_url('doctor/addService') ?>" class="btn btn-primary mb-3">Add New Service</a>

  <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
  <?php elseif (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
  <?php endif; ?>

  <table class="table table-bordered bg-white">
    <thead class="table-dark">
      <tr>
        <th>#</th>
        <th>Service</th>
        <th>Hospital Price</th>
        <th>Custom Fee</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($services)): ?>
        <?php foreach ($services as $i => $s): ?>
          <tr>
            <td><?= $i + 1 ?></td>
            <td><?= esc($s['service_name']) ?></td>
            <td>₹<?= esc($s['hospital_price']) ?></td>
            <td><?= $s['price'] != $s['hospital_price'] ? '₹' . esc($s['price']) : '<span class="text-muted">Same as hospital</span>' ?></td>
            <td>
              <a href="<?= site_url('doctor/editService/' . $s['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
              <a href="<?= site_url('doctor/deleteService/' . $s['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Remove this service?');">Delete</a>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr><td colspan="5" class="text-center text-muted">No services added yet.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?= $this->endSection() ?>
