<?= $this->extend('layouts/doctor_layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
  <h3>Add Service</h3>

  <form action="<?= site_url('doctor/saveService') ?>" method="post">
    <div class="mb-3">
      <label class="form-label">Select Service</label>
      <select name="service_id" class="form-select" required>
        <option value="">Select...</option>
        <?php foreach ($hospitalServices as $s): ?>
          <option value="<?= $s['service_id'] ?>">
            <?= esc($s['name']) ?> (₹<?= esc($s['price']) ?>)
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label">Custom Fee (optional)</label>
      <input type="number" step="0.01" name="custom_fee" class="form-control" placeholder="Enter your fee (leave blank to use hospital price)">
    </div>

    <button type="submit" class="btn btn-success">Add</button>
    <a href="<?= site_url('doctor/services') ?>" class="btn btn-secondary">Cancel</a>
  </form>
</div>

<?= $this->endSection() ?>
