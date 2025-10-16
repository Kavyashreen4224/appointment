<?= $this->extend('layouts/doctor_layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h2>Appointment Details</h2>

    <!-- Back Button -->
    <a href="<?= site_url('doctor/appointments') ?>" class="btn btn-secondary mb-3">Back to Appointments</a>

    <!-- Appointment Info -->
    <div class="card mb-3">
        <div class="card-body">
            <h5>Patient: <?= esc($appointment['patient_name']) ?></h5>
            <p><strong>Start:</strong> <?= esc($appointment['start_datetime']) ?></p>
            <p><strong>End:</strong> <?= esc($appointment['end_datetime']) ?></p>
            <p><strong>Status:</strong> <?= esc(ucfirst($appointment['status'])) ?></p>
        </div>
    </div>

    <!-- Visit History -->
    <div class="card mb-3">
        <div class="card-header">
            <h5>Visit History</h5>
        </div>
        <div class="card-body">
            <?php if(!empty($visit_history)): ?>
                <ul class="list-group">
                    <?php foreach($visit_history as $visit): ?>
                        <li class="list-group-item">
                            <strong>Date:</strong> <?= esc($visit['created_at']) ?><br>
                            <strong>Reason:</strong> <?= esc($visit['reason']) ?><br>
                            <strong>Weight:</strong> <?= esc($visit['weight']) ?> kg<br>
                            <strong>Blood Pressure:</strong> <?= esc($visit['blood_pressure']) ?><br>
                            <strong>Doctor Comments:</strong> <?= esc($visit['doctor_comments']) ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No visit history found.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Add Visit -->
    <div class="card mb-3">
        <div class="card-header">
            <h5>Add Visit</h5>
        </div>
        <div class="card-body">
            <form action="<?= site_url('doctor/addVisit/'.$appointment['id']) ?>" method="post">
                <input type="hidden" name="patient_id" value="<?= esc($appointment['patient_id']) ?>">
                <div class="mb-3">
                    <label for="reason" class="form-label">Reason</label>
                    <textarea name="reason" id="reason" class="form-control" required></textarea>
                </div>

                <div class="mb-3">
                    <label for="weight" class="form-label">Weight (kg)</label>
                    <input type="number" step="0.01" name="weight" id="weight" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="blood_pressure" class="form-label">Blood Pressure</label>
                    <input type="text" name="blood_pressure" id="blood_pressure" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="doctor_comments" class="form-label">Doctor Comments</label>
                    <textarea name="doctor_comments" id="doctor_comments" class="form-control"></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Add Visit</button>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
