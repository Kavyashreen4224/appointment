<?= $this->extend('layouts/doctor_layout') ?>
<?= $this->section('content') ?>

<div class="container">
  <h3>Edit Appointment</h3>

  <form action="<?= site_url('doctor/updateAppointment/' . $appointment['id']) ?>" method="post">
    <?= csrf_field() ?>

    <div class="mb-3">
      <label>Patient</label>
      <select name="patient_id" class="form-control" required>
        <?php foreach ($patients as $p): ?>
          <option value="<?= $p['id'] ?>" <?= $p['id'] == $appointment['patient_id'] ? 'selected' : '' ?>>
            <?= esc($p['patient_name']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="mb-3">
      <label>Start Date & Time</label>
      <input type="datetime-local" name="start_datetime" value="<?= date('Y-m-d\TH:i', strtotime($appointment['start_datetime'])) ?>" class="form-control" required>
    </div>

    <div class="mb-3">
      <label>End Date & Time</label>
      <input type="datetime-local" name="end_datetime" value="<?= date('Y-m-d\TH:i', strtotime($appointment['end_datetime'])) ?>" class="form-control" required>
    </div>

    <div class="mb-3">
      <label>Status</label>
      <select name="status" class="form-control">
        <option value="pending" <?= $appointment['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
        <option value="completed" <?= $appointment['status'] == 'completed' ? 'selected' : '' ?>>Completed</option>
        <option value="cancelled" <?= $appointment['status'] == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
      </select>
    </div>

    <button type="submit" class="btn btn-success">Update</button>
    <a href="<?= site_url('doctor/appointments') ?>" class="btn btn-secondary">Cancel</a>
  </form>
</div>

<?= $this->endSection() ?>
