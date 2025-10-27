<?= $this->extend('layouts/doctor_layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h2>Add Visit Details</h2>

    <div class="card shadow-sm p-4">
        <form action="<?= site_url('doctor/saveVisit') ?>" method="post">
            <input type="hidden" name="appointment_id" value="<?= esc($appointment['id']) ?>">
            <input type="hidden" name="patient_id" value="<?= esc($appointment['patient_id']) ?>">
            <input type="hidden" name="doctor_id" value="<?= esc($appointment['doctor_id']) ?>">

            <div class="mb-3">
                <label class="form-label">Patient Name</label>
                <input type="text" class="form-control" value="<?= esc($appointment['patient_name']) ?>" readonly>
            </div>

            <div class="mb-3">
                <label class="form-label">Reason for Visit</label>
                <textarea name="reason" class="form-control" required></textarea>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Weight (kg)</label>
                    <input type="text" name="weight" class="form-control">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Blood Pressure</label>
                    <input type="text" name="blood_pressure" class="form-control">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Doctor Comments</label>
                <textarea name="doctor_comments" class="form-control"></textarea>
            </div>

            <button type="submit" class="btn btn-success">Save Visit</button>
            <a href="<?= site_url('doctor/appointments') ?>" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
