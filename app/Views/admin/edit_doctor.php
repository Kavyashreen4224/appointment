<?= $this->extend('layouts/admin_layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h2>Edit Doctor</h2>
    <form action="<?= site_url('admin/updateDoctor/'.$doctor['id']) ?>" method="post">
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="<?= esc($doctor['name']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="<?= esc($doctor['email']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Age</label>
            <input type="number" name="age" class="form-control" value="<?= esc($doctor['age']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Gender</label>
            <select name="gender" class="form-control" required>
                <option value="male" <?= $doctor['gender'] == 'male' ? 'selected' : '' ?>>Male</option>
                <option value="female" <?= $doctor['gender'] == 'female' ? 'selected' : '' ?>>Female</option>
                <option value="other" <?= $doctor['gender'] == 'other' ? 'selected' : '' ?>>Other</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Expertise</label>
            <input type="text" name="expertise" class="form-control" value="<?= esc($doctor['expertise']) ?>">
        </div>
        <div class="mb-3">
            <label>Availability</label>
            <input type="text" name="availability" class="form-control" value="<?= esc($doctor['availability']) ?>">
        </div>
        <button class="btn btn-success">Update Doctor</button>
    </form>
</div>

<?= $this->endSection() ?>
