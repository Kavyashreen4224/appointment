<?= $this->extend('layouts/doctor_layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h2>Add New Patient</h2>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>
    
    <form method="post" action="<?= site_url('doctor/savePatient') ?>">
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Age</label>
            <input type="number" name="age" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Gender</label>
            <select name="gender" class="form-select" required>
                <option value="">Select Gender</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="other">Other</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Add Patient</button>
    </form>
</div>

<?= $this->endSection() ?>
