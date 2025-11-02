<?= $this->extend('layouts/superadmin_layout') ?>
<?= $this->section('content') ?>

<h2 class="mb-4">Doctor List</h2>

<form method="get" class="mb-3">
  <div class="row">
    <div class="col-md-4">
      <select name="hospital_id" class="form-select" onchange="this.form.submit()">
        <option value="">-- Filter by Hospital --</option>
        <?php foreach ($hospitals as $hospital): ?>
          <option value="<?= $hospital['id'] ?>" <?= ($selectedHospital == $hospital['id']) ? 'selected' : '' ?>>
            <?= esc($hospital['name']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>
</form>

<table class="table table-bordered bg-white shadow-sm">
  <thead class="table-dark">
    <tr>
      <th>#</th>
      <th>Name</th>
      <th>Email</th>
      <th>Hospital</th>
      <th>Status</th>
      <th>Created At</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($doctors)): ?>
      <?php foreach ($doctors as $i => $doctor): ?>
        <tr>
          <td><?= $i + 1 ?></td>
          <td><?= esc($doctor['name']) ?></td>
          <td><?= esc($doctor['email']) ?></td>
          <td><?= esc($doctor['hospital_name']) ?></td>
          <td>
            <span class="badge <?= $doctor['status'] === 'active' ? 'bg-success' : 'bg-secondary' ?>">
              <?= ucfirst($doctor['status']) ?>
            </span>
          </td>
          <td><?= date('d M Y', strtotime($doctor['created_at'])) ?></td>
          <td>
            <a href="<?= site_url('superadmin/viewDoctor/'.$doctor['user_id']) ?>" class="btn btn-info btn-sm">View</a>
          </td>
        </tr>
      <?php endforeach; ?>
    <?php else: ?>
      <tr>
        <td colspan="7" class="text-center">No doctors found</td>
      </tr>
    <?php endif; ?>
  </tbody>
</table>

<?= $this->endSection() ?>
