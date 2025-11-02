<?= $this->extend('layouts/superadmin_layout') ?>
<?= $this->section('content') ?>

<h2 class="mb-4">Doctor Profile</h2>

<div class="card shadow-sm p-4 bg-white">
  
  <!-- Doctor Information -->
  <h5 class="mb-3 text-primary border-bottom pb-2">👨‍⚕️ Doctor Information</h5>
  <div class="row mb-3">
    <div class="col-md-6">
      <p><strong>Full Name:</strong> <?= esc($doctor['name']) ?></p>
      <p><strong>Email Address:</strong> <?= esc($doctor['email']) ?></p>
    </div>
    <div class="col-md-6">
      <p><strong>Account Created On:</strong> <?= date('d M Y', strtotime($doctor['created_at'])) ?></p>
      <p>
        <strong>Account Status:</strong>
        <span class="badge <?= $doctor['status'] === 'active' ? 'bg-success' : 'bg-secondary' ?>">
          <?= ucfirst($doctor['status']) ?>
        </span>
      </p>
    </div>
  </div>

  <!-- Hospital Information -->
  <h5 class="mb-3 text-primary border-bottom pb-2">🏥 Associated Hospital</h5>
  <div class="row mb-3">
    <div class="col-md-6">
      <p><strong>Hospital Name:</strong> <?= esc($doctor['hospital_name']) ?></p>
      <p><strong>Hospital Contact:</strong> <?= esc($doctor['contact']) ?></p>
    </div>
    <div class="col-md-6">
      <p><strong>Address:</strong> <?= esc($doctor['address']) ?></p>
    </div>
  </div>

  <!-- Optional Section (Future Enhancement) -->
  <h5 class="mb-3 text-primary border-bottom pb-2">📋 Other Details</h5>
  <p class="text-muted">Additional details like specialization, experience, or department can be displayed here later.</p>
</div>

<a href="<?= site_url('superadmin/listDoctors') ?>" class="btn btn-secondary mt-4">← Back to Doctor List</a>

<?= $this->endSection() ?>
