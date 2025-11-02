<?= $this->extend('layouts/admin_layout') ?>
<?= $this->section('content') ?>

<h2 class="mb-4">Appointments</h2>

<form method="get" class="row g-3 mb-4">
  <div class="col-md-4">
    <label>Doctor</label>
    <select name="doctor_id" class="form-select">
      <option value="">All</option>
      <?php foreach ($doctors as $doc): ?>
        <option value="<?= $doc['id'] ?>" <?= ($selectedDoctor == $doc['id']) ? 'selected' : '' ?>>
          <?= esc($doc['name']) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>

  <div class="col-md-4">
    <label>Patient</label>
    <select name="patient_id" class="form-select">
      <option value="">All</option>
      <?php foreach ($patients as $pat): ?>
        <option value="<?= $pat['id'] ?>" <?= ($selectedPatient == $pat['id']) ? 'selected' : '' ?>>
          <?= esc($pat['name']) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>

  <div class="col-md-4">
    <label>Status</label>
    <select name="status" class="form-select">
      <option value="">All</option>
      <option value="pending" <?= ($selectedStatus == 'pending') ? 'selected' : '' ?>>Pending</option>
      <option value="completed" <?= ($selectedStatus == 'completed') ? 'selected' : '' ?>>Completed</option>
      <option value="cancelled" <?= ($selectedStatus == 'cancelled') ? 'selected' : '' ?>>Cancelled</option>
    </select>
  </div>

  <div class="col-12 text-end">
    <button type="submit" class="btn btn-primary mt-2">Filter</button>
    <a href="<?= site_url('admin/listAppointments') ?>" class="btn btn-secondary mt-2">Reset</a>
  </div>
</form>

<table class="table table-bordered table-striped bg-white">
  <thead class="table-dark">
    <tr>
      <th>ID</th>
      <th>Doctor</th>
      <th>Doctor Email</th>
      <th>Patient</th>
      <th>Patient Email</th>
      <th>Start Time</th>
      <th>End Time</th>
      <th>Status</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($appointments)): ?>
      <?php foreach ($appointments as $appt): ?>
        <tr>
          <td><?= esc($appt['id']) ?></td>
          <td><?= esc($appt['doctor_name']) ?></td>
          <td><?= esc($appt['doctor_email']) ?></td>
          <td><?= esc($appt['patient_name']) ?></td>
          <td><?= esc($appt['patient_email']) ?></td>
          <td><?= esc($appt['start_datetime']) ?></td>
          <td><?= esc($appt['end_datetime']) ?></td>
          <td>
            <span class="badge 
              <?= $appt['status'] == 'pending' ? 'bg-warning' : 
                  ($appt['status'] == 'completed' ? 'bg-success' : 'bg-danger') ?>">
              <?= ucfirst($appt['status']) ?>
            </span>
          </td>
          <td>
            <a href="#" class="btn btn-sm btn-info">View</a>
            <a href="#" class="btn btn-sm btn-danger">Delete</a>
          </td>
        </tr>
      <?php endforeach; ?>
    <?php else: ?>
      <tr><td colspan="9" class="text-center">No appointments found.</td></tr>
    <?php endif; ?>
  </tbody>
</table>


<?= $this->endSection() ?>
