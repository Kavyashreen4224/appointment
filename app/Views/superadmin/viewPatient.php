<?= $this->extend('layouts/superadmin_layout') ?>
<?= $this->section('content') ?>

<h2 class="mb-4">Patient Profile</h2>

<div class="card shadow-sm p-4 bg-white">

  <!-- Personal Info -->
  <h5 class="mb-3 text-primary border-bottom pb-2">👤 Personal Information</h5>
  <div class="row mb-3">
    <div class="col-md-6">
      <p><strong>Name:</strong> <?= esc($patient['patient_name']) ?></p>
      <p><strong>Email:</strong> <?= esc($patient['patient_email']) ?></p>
    </div>
    <div class="col-md-6">
      <p><strong>Gender:</strong> <?= ucfirst($patient['gender']) ?></p>
      <p><strong>Age:</strong> <?= esc($patient['age']) ?></p>
    </div>
  </div>

  <!-- Hospital Info -->
  <h5 class="mb-3 text-primary border-bottom pb-2">🏥 Associated Hospital</h5>
  <div class="row mb-3">
    <div class="col-md-6">
      <p><strong>Hospital Name:</strong> <?= esc($patient['hospital_name']) ?></p>
      <p><strong>Email:</strong> <?= esc($patient['hospital_email']) ?></p>
    </div>
    <div class="col-md-6">
      <p><strong>Contact:</strong> <?= esc($patient['hospital_contact']) ?></p>
      <p><strong>Address:</strong> <?= esc($patient['hospital_address']) ?></p>
    </div>
  </div>

  <!-- Account Info -->
  <h5 class="mb-3 text-primary border-bottom pb-2">🕒 System Information</h5>
  <div class="row">
    <div class="col-md-6">
      <p><strong>Role:</strong> <?= ucfirst($patient['user_role']) ?></p>
    </div>
    <div class="col-md-6">
      <p><strong>Status:</strong>
        <span class="badge <?= $patient['patient_status'] === 'active' ? 'bg-success' : 'bg-secondary' ?>">
          <?= ucfirst($patient['patient_status']) ?>
        </span>
      </p>
    </div>
  </div>

  <p><strong>Linked on:</strong> <?= date('d M Y', strtotime($patient['linked_on'])) ?></p>

</div>

<a href="<?= site_url('superadmin/listPatients') ?>" class="btn btn-secondary mt-4">← Back to Patient List</a>

<?= $this->endSection() ?>
