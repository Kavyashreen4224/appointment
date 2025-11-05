<?= $this->extend('layouts/doctor_layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
  <h3>Edit Service Fee</h3>

  <div class="card p-3 mb-3">
    <p><strong>Service:</strong> <?= esc($service['service_name']) ?></p>
    <p><strong>Hospital Price:</strong> ₹<?= esc($service['hospital_price']) ?></p>
  </div>

  <form action="<?= site_url('doctor/updateService/' . $service['id']) ?>" method="post">
    <div class="mb-3">
      <label class="form-label">Custom Fee (optional)</label>
      <input type="number" step="0.01" name="custom_fee" class="form-control" 
             value="<?= esc($service['custom_fee']) ?>" placeholder="Leave blank to use hospital price">
    </div>

    <button type="submit" class="btn btn-primary">Save</button>
    <a href="<?= site_url('doctor/services') ?>" class="btn btn-secondary">Cancel</a>
  </form>
</div>

<?= $this->endSection() ?>
