<?= $this->extend('layouts/doctor_layout') ?>
<?= $this->section('content') ?>

<div class="container mt-5">
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Appointment Details</h4>
            <span class="badge 
                <?= $appointment['status'] === 'completed' ? 'bg-success' : 
                    ($appointment['status'] === 'pending' ? 'bg-warning text-dark' : 'bg-danger') ?>">
                <?= ucfirst($appointment['status']) ?>
            </span>
        </div>

        <div class="card-body p-4">
            <!-- Doctor & Patient Info -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5 class="text-primary"><i class="bi bi-person-badge"></i> Doctor</h5>
                    <p class="mb-1"><strong>Name:</strong> <?= esc($appointment['doctor_name']) ?></p>
                </div>
                <div class="col-md-6">
                    <h5 class="text-primary"><i class="bi bi-person-fill"></i> Patient</h5>
                    <p class="mb-1"><strong>Name:</strong> <?= esc($appointment['patient_name']) ?></p>
                </div>
            </div>

            <!-- Appointment Timing -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <p><strong>Start Date & Time:</strong> <?= esc($appointment['start_datetime']) ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong>End Date & Time:</strong> <?= esc($appointment['end_datetime']) ?></p>
                </div>
            </div>

            <!-- Visit Details -->
            <?php if (!empty($appointment['reason'])): ?>
                <div class="mt-4">
                    <h5 class="text-secondary border-bottom pb-2">Visit Details</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Reason:</strong> <?= esc($appointment['reason']) ?></p>
                            <p><strong>Doctor Comments:</strong> <?= esc($appointment['doctor_comments']) ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Weight:</strong> <?= esc($appointment['weight']) ?> kg</p>
                            <p><strong>Blood Pressure:</strong> <?= esc($appointment['blood_pressure']) ?></p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Prescription -->
            <?php if (!empty($appointment['prescription_id'])): ?>
                <div class="mt-4">
                    <h5 class="text-secondary border-bottom pb-2">Prescription</h5>
                    <p><?= nl2br(esc($appointment['prescription_text'])) ?></p>
                    <a href="<?= site_url('doctor/viewPrescription/' . $appointment['prescription_id']) ?>" 
                       class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-file-earmark-medical"></i> View Full Prescription
                    </a>
                </div>
            <?php endif; ?>

            <!-- Billing -->
            <?php if (!empty($appointment['bill_id'])): ?>
                <div class="mt-4">
                    <h5 class="text-secondary border-bottom pb-2">Billing Information</h5>
                    <ul class="list-unstyled mb-3">
                        <li><strong>Total Amount:</strong> ₹<?= esc($appointment['total_amount']) ?></li>
                        <li>
                            <strong>Payment Status:</strong>
                            <?php if ($appointment['payment_status'] === 'Paid'): ?>
                                <span class="badge bg-success">Paid</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Pending</span>
                            <?php endif; ?>
                        </li>
                        <li><strong>Payment Mode:</strong> <?= esc($appointment['payment_mode'] ?? 'N/A') ?></li>
                    </ul>

                    <a href="<?= site_url('doctor/viewBill/' . $appointment['bill_id']) ?>" 
                       class="btn btn-outline-info btn-sm">
                        <i class="bi bi-receipt"></i> View Bill
                    </a>
                </div>
            <?php else: ?>
                <div class="alert alert-warning mt-4">
                    No billing details added yet.
                </div>
            <?php endif; ?>

            <div class="mt-4 text-end">
                <a href="<?= site_url('doctor/appointments') ?>" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Appointments
                </a>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
