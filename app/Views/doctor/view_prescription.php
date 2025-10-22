<?= $this->extend('layouts/doctor_layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h2>Prescription Details</h2>

    <div class="card mb-3">
        <div class="card-body">
            <h5>Patient: <?= esc($patient['patient_name']) ?></h5>
            <p><strong>Email:</strong> <?= esc($patient['email']) ?></p>
            <p><strong>Appointment ID:</strong> <?= esc($visit['appointment_id'] ?? 'N/A') ?></p>
            <p><strong>Date:</strong> <?= esc($visit['created_at'] ?? '') ?></p>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h5>Prescription:</h5>
            <p><?= nl2br(esc($prescription['prescription_text'])) ?></p>
        </div>
    </div>

    <a href="<?= site_url('doctor/appointments') ?>" class="btn btn-secondary mt-3">Back to Appointments</a>
</div>

<?= $this->endSection() ?>
