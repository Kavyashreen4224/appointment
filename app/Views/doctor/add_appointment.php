<?= $this->extend('layouts/doctor_layout') ?>
<?= $this->section('content') ?>

<div class="container">
  <h3>Add Appointment</h3>

  <form action="<?= site_url('doctor/saveAppointment') ?>" method="post">
    <?= csrf_field() ?>

    <div class="mb-3">
      <label>Patient</label>
      <select name="patient_id" class="form-control" required>
        <option value="">Select Patient</option>
        <?php foreach ($patients as $p): ?>
          <option value="<?= $p['id'] ?>"><?= esc($p['patient_name']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="mb-3">
      <label>Start Date & Time</label>
      <input type="datetime-local" name="start_datetime" class="form-control" required>
    </div>

    <div class="mb-3">
      <label>End Date & Time</label>
      <input type="datetime-local" name="end_datetime" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-success">Save</button>
    <a href="<?= site_url('doctor/appointments') ?>" class="btn btn-secondary">Cancel</a>
  </form>
</div>

<?= $this->endSection() ?>
