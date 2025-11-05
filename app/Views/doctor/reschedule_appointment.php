<?= $this->extend('layouts/doctor_layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
  <h3>Reschedule Appointment #<?= $appointment['id'] ?></h3>

  <form method="post" action="<?= site_url('doctor/updateAppointment/' . $appointment['id']) ?>">
    <?= csrf_field() ?>

    <div class="mb-3">
      <label for="patient_name" class="form-label">Patient</label>
      <input type="text" id="patient_name" class="form-control" value="<?= esc($appointment['patient_name']) ?>" readonly>
    </div>

    <div class="mb-3">
      <label for="start_datetime" class="form-label">Start Date & Time</label>
      <input type="datetime-local" name="start_datetime" id="start_datetime" class="form-control"
             value="<?= date('Y-m-d\TH:i', strtotime($appointment['start_datetime'])) ?>" required>
    </div>

    <div class="mb-3">
      <label for="end_datetime" class="form-label">End Date & Time</label>
      <input type="datetime-local" name="end_datetime" id="end_datetime" class="form-control"
             value="<?= date('Y-m-d\TH:i', strtotime($appointment['end_datetime'])) ?>" required>
    </div>

    <button type="submit" class="btn btn-success">Save Changes</button>
    <a href="<?= site_url('doctor/appointments') ?>" class="btn btn-secondary">Cancel</a>
  </form>
</div>

<?= $this->endSection() ?>
