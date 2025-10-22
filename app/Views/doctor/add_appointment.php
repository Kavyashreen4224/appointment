<?= $this->extend('layouts/doctor_layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h3>Add Appointment</h3>

    <form method="post" action="<?= site_url('doctor/saveAppointment') ?>">
        <div class="mb-3">
            <label for="patient_id" class="form-label">Select Patient</label>
            <select class="form-select" name="patient_id" required>
                <option value="">-- Select Patient --</option>
                <?php foreach($patients as $p): ?>
                    <option value="<?= esc($p['id']) ?>"><?= esc($p['name']) ?> (<?= esc($p['email']) ?>)</option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="start_datetime" class="form-label">Start Date & Time</label>
            <input type="datetime-local" class="form-control" name="start_datetime" required>
        </div>

        <div class="mb-3">
            <label for="end_datetime" class="form-label">End Date & Time</label>
            <input type="datetime-local" class="form-control" name="end_datetime" required>
        </div>

        <button type="submit" class="btn btn-primary">Save Appointment</button>
        <a href="<?= site_url('doctor/appointments') ?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?= $this->endSection() ?>
