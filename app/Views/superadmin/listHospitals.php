<?= $this->extend('layouts/superadmin_layout') ?>
<?= $this->section('content') ?>

<div class="container-fluid">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3>🏥 Manage Hospitals</h3>
    <a href="<?= site_url('superadmin/addHospital') ?>" class="btn btn-success">➕ Add Hospital</a>
  </div>

  <table class="table table-bordered table-hover bg-white shadow-sm">
    <thead class="table-dark">
      <tr>
        <th>#</th>
        <th>Hospital Name</th>
        <th>Address</th>
        <th>Contact</th>
        <th>Email</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($hospitals)): ?>
        <?php foreach ($hospitals as $index => $hospital): ?>
          <tr>
            <td><?= $index + 1 ?></td>
            <td><?= esc($hospital['name']) ?></td>
            <td><?= esc($hospital['address']) ?></td>
            <td><?= esc($hospital['contact']) ?></td>
            <td><?= esc($hospital['email']) ?></td>
            <td>
              <span class="badge bg-<?= $hospital['status'] == 'active' ? 'success' : 'secondary' ?>">
                <?= ucfirst($hospital['status']) ?>
              </span>
            </td>
            <td>
              <a href="<?= site_url('superadmin/editHospital/' . $hospital['id']) ?>" class="btn btn-warning btn-sm">Edit</a>
              <a href="<?= site_url('superadmin/deleteHospital/' . $hospital['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this hospital?')">Delete</a>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr><td colspan="7" class="text-center text-muted">No hospitals found.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?= $this->endSection() ?>
