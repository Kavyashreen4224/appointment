<?= $this->extend('layouts/superadmin_layout') ?>
<?= $this->section('content') ?>

<h2 class="mb-4">Appointments</h2>

<form method="get" class="row g-3 mb-4">
  <div class="col-md-3">
    <select name="hospital_id" class="form-select">
      <option value="">All Hospitals</option>
      <?php foreach ($hospitals as $h): ?>
        <option value="<?= $h['id'] ?>" <?= ($selectedHospital == $h['id']) ? 'selected' : '' ?>>
          <?= esc($h['name']) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>

  <div class="col-md-3">
    <select name="doctor_id" class="form-select">
      <option value="">All Doctors</option>
      <?php foreach ($doctors as $d): ?>
        <option value="<?= $d['id'] ?>" <?= ($selectedDoctor == $d['id']) ? 'selected' : '' ?>>
          <?= esc($d['name']) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>

  <div class="col-md-3">
    <select name="status" class="form-select">
      <option value="">All Status</option>
      <option value="pending" <?= ($selectedStatus == 'pending') ? 'selected' : '' ?>>Pending</option>
      <option value="completed" <?= ($selectedStatus == 'completed') ? 'selected' : '' ?>>Completed</option>
      <option value="cancelled" <?= ($selectedStatus == 'cancelled') ? 'selected' : '' ?>>Cancelled</option>
    </select>
  </div>

  <div class="col-md-3">
    <button type="submit" class="btn btn-primary w-100">Filter</button>
  </div>
</form>

<table class="table table-bordered table-hover bg-white shadow-sm">
  <thead class="table-dark">
    <tr>
      <th>#</th>
      <th>Hospital</th>
      <th>Doctor</th>
      <th>Patient</th>
      <th>Start</th>
      <th>End</th>
      <th>Status</th>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($appointments)): ?>
      <?php foreach ($appointments as $i => $a): ?>
        <tr>
          <td><?= $i + 1 ?></td>
          <td><?= esc($a['hospital_name']) ?></td>
          <td><?= esc($a['doctor_name']) ?></td>
          <td><?= esc($a['patient_name']) ?></td>
          <td><?= date('d M Y, h:i A', strtotime($a['start_datetime'])) ?></td>
          <td><?= date('d M Y, h:i A', strtotime($a['end_datetime'])) ?></td>
          <td>
            <span class="badge 
              <?= $a['status'] == 'pending' ? 'bg-warning' : 
                  ($a['status'] == 'completed' ? 'bg-success' : 'bg-danger') ?>">
              <?= ucfirst($a['status']) ?>
            </span>
          </td>
        </tr>
      <?php endforeach; ?>
    <?php else: ?>
      <tr><td colspan="7" class="text-center">No appointments found.</td></tr>
    <?php endif; ?>
  </tbody>
</table>

<?= $this->endSection() ?>
