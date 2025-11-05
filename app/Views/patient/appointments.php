<?= $this->extend('layouts/patient_layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
  <h3 class="mb-3">My Appointments</h3>

  <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
  <?php endif; ?>

  <!-- Tabs -->
  <ul class="nav nav-tabs" id="appointmentTabs" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button" role="tab">🟡 Pending</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed" type="button" role="tab">🟢 Completed</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="cancelled-tab" data-bs-toggle="tab" data-bs-target="#cancelled" type="button" role="tab">🔴 Cancelled</button>
    </li>
  </ul>

  <div class="tab-content mt-3" id="appointmentTabsContent">
   <!-- 🟡 Pending -->
<div class="tab-pane fade show active" id="pending" role="tabpanel">
  <?= view('patient/partials/appointment_table', ['appointments' => $pending, 'status' => 'pending']) ?>
</div>

<!-- 🟢Completed -->
<div class="tab-pane fade" id="completed" role="tabpanel">
  <?= view('patient/partials/appointment_table', ['appointments' => $completed, 'status' => 'completed']) ?>
</div>

<!--  Cancelled -->
<div class="tab-pane fade" id="cancelled" role="tabpanel">
  <?= view('patient/partials/appointment_table', ['appointments' => $cancelled, 'status' => 'cancelled']) ?>
</div>

  </div>
</div>

<?= $this->endSection() ?>
