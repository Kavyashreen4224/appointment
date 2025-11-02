<?= $this->extend('layouts/superadmin_layout') ?>
<?= $this->section('content') ?>

<h2 class="mb-4">Patients List</h2>

<form method="get" class="mb-3">
    <select name="hospital_id" class="form-select w-auto d-inline">
        <option value="">-- All Hospitals --</option>
        <?php foreach ($hospitals as $h): ?>
            <option value="<?= $h['id'] ?>" <?= (isset($_GET['hospital_id']) && $_GET['hospital_id'] == $h['id']) ? 'selected' : '' ?>>
                <?= esc($h['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <button type="submit" class="btn btn-primary btn-sm">Filter</button>
   
</form>

<table class="table table-bordered table-hover bg-white">
    <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Hospital</th>
            <th>Status</th>
            <th>Registered</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($patients)): ?>
            <?php foreach ($patients as $i => $patient): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= esc($patient['name']) ?></td>
                    <td><?= esc($patient['email']) ?></td>
                    <td><?= esc($patient['hospital_name']) ?></td>
                    <td><span class="badge <?= $patient['status'] === 'active' ? 'bg-success' : 'bg-secondary' ?>"><?= ucfirst($patient['status']) ?></span></td>
                    <td><?= date('d M Y', strtotime($patient['created_at'])) ?></td>
                    <td>
                        <a href="<?= site_url('superadmin/patientProfile/'.$patient['id']) ?>" class="btn btn-info btn-sm">View</a>
                        <a href="<?= site_url('superadmin/deletePatient/'.$patient['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="7" class="text-center">No patients found.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<?= $this->endSection() ?>
