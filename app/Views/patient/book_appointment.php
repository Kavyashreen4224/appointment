<?= $this->extend('layouts/patient_layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
  <h3>Book an Appointment</h3>

  <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
  <?php endif; ?>

  <form action="<?= site_url('patient/saveAppointment') ?>" method="post" class="mt-3">

    <div class="mb-3">
      <label class="form-label">Select Doctor</label>
      <select name="doctor_id" class="form-select" required>
        <option value="">-- Choose Doctor --</option>
        <?php foreach ($doctors as $doctor): ?>
          <option value="<?= $doctor['doctor_id'] ?>">
            Dr. <?= esc($doctor['doctor_name']) ?> (<?= esc($doctor['expertise'] ?? 'General') ?>)
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label">Start Date & Time</label>
      <input type="datetime-local" name="start_datetime" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label">End Date & Time</label>
      <input type="datetime-local" name="end_datetime" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-success">Book Appointment</button>
    <a href="<?= site_url('patient/dashboard') ?>" class="btn btn-secondary">Cancel</a>
  </form>
</div>

<?= $this->endSection() ?>
