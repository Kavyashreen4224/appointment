<?= $this->extend('layouts/doctor_layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h2>Edit Patient</h2>

    <form method="post" action="<?= base_url('doctor/updatePatient/'.$patient['patient_id']) ?>">
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" value="<?= esc($patient['name']) ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" value="<?= esc($patient['email']) ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Age</label>
            <input type="number" name="age" value="<?= esc($patient['age']) ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Gender</label>
            <select name="gender" class="form-select">
                <option value="male" <?= $patient['gender']=='male'?'selected':'' ?>>Male</option>
                <option value="female" <?= $patient['gender']=='female'?'selected':'' ?>>Female</option>
                <option value="other" <?= $patient['gender']=='other'?'selected':'' ?>>Other</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update Patient</button>
    </form>
</div>

<?= $this->endSection() ?>
