<?= $this->extend('layouts/admin_layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h2>Add Doctor</h2>
    <form action="<?= site_url('admin/saveDoctor') ?>" method="post">
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
                <option value="">Select Gender</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="other">Other</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Expertise</label>
            <input type="text" name="expertise" class="form-control">
        </div>
        <div class="mb-3">
            <label>Availability</label>
            <input type="text" name="availability" class="form-control">
        </div>
        <button class="btn btn-success">Save Doctor</button>
    </form>
</div>

<?= $this->endSection() ?>
