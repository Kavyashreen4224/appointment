<?= $this->extend('layouts/admin_layout') ?>
<?= $this->section('content') ?>

<h3 class="mb-3">Edit Patient</h3>

<form method="post" action="<?= site_url('admin/updatePatient/'.$patient['patient_id']) ?>">
    <div class="mb-3">
        <label>Name</label>
        <input type="text" name="name" class="form-control" value="<?= esc($patient['name']) ?>" required>
    </div>

    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" value="<?= esc($patient['email']) ?>" required>
    </div>

    <div class="mb-3">
        <label>Age</label>
        <input type="number" name="age" class="form-control" value="<?= esc($patient['age']) ?>" required>
    </div>

    <div class="mb-3">
        <label>Gender</label>
        <select name="gender" class="form-control" required>
            <option value="male" <?= $patient['gender'] === 'male' ? 'selected' : '' ?>>Male</option>
            <option value="female" <?= $patient['gender'] === 'female' ? 'selected' : '' ?>>Female</option>
            <option value="other" <?= $patient['gender'] === 'other' ? 'selected' : '' ?>>Other</option>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Update Patient</button>
</form>

<?= $this->endSection() ?>
