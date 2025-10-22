<?= $this->extend('layouts/doctor_layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h2>Add Prescription</h2>

    <div class="card mb-3">
        <div class="card-body">
            <h5>Patient: <?= esc($patient['patient_name']) ?></h5>
            <p><strong>Email:</strong> <?= esc($patient['email'] ?? 'N/A') ?></p>
            <p><strong>Appointment ID:</strong> <?= esc($appointment['id']) ?></p>
            <p><strong>Appointment Date:</strong> <?= esc($appointment['start_datetime']) ?></p>
        </div>
    </div>

    <form action="<?= site_url('doctor/savePrescription') ?>" method="post">
        <input type="hidden" name="visit_id" value="<?= esc($visit['id']) ?>">
        
        <div class="mb-3">
            <label for="prescription_text" class="form-label">Prescription</label>
            <textarea name="prescription_text" id="prescription_text" class="form-control" rows="6" required></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Save Prescription</button>
        <a href="<?= site_url('doctor/appointments') ?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?= $this->endSection() ?>
