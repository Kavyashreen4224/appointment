<?= $this->extend('layouts/superadmin_layout') ?>
<?= $this->section('content') ?>

<h2 class="mb-4">Welcome, SuperAdmin 👋</h2>

<div class="row g-4">
  <div class="col-md-4">
    <div class="card text-center p-3 shadow-sm">
      <h5>Hospitals</h5>
      <h2><?= $hospitalCount ?></h2>
      <a href="<?= site_url('superadmin/listHospitals') ?>" class="btn btn-primary btn-sm mt-2">Manage</a>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card text-center p-3 shadow-sm">
      <h5>Admins</h5>
      <h2><?= $adminCount ?></h2>
      <a href="<?= site_url('superadmin/listAdmins') ?>" class="btn btn-primary btn-sm mt-2">Manage</a>
    </div>
  </div>
</div>

<?= $this->endSection() ?>
