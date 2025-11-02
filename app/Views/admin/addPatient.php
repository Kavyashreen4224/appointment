<?= $this->extend('layouts/admin_layout') ?>
<?= $this->section('content') ?>

<h3 class="mb-3">Add New Patient</h3>

<form method="post" action="<?= site_url('admin/addPatientPost') ?>">
    <div class="mb-3">
        <label>Name</label>
        <input type="text" name="name" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Password</label>
        <input type="password" name="password" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Age</label>
        <input type="number" name="age" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Gender</label>
        <select name="gender" class="form-control" required>
            <option value="">-- Select --</option>
            <option value="male">Male</option>
            <option value="female">Female</option>
            <option value="other">Other</option>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Add Patient</button>
</form>

<?= $this->endSection() ?>
