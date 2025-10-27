<?= $this->extend('layouts/doctor_layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h2>My Appointments</h2>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <a href="<?= site_url('doctor/addAppointment') ?>" class="btn btn-success mb-3">Add Appointment</a>
<form method="get" action="<?= site_url('doctor/appointments') ?>" class="row g-2 mb-3 align-items-end">
    <!-- Filter Type -->
    <div class="col-md-4">
        <label class="form-label fw-semibold">Filter Type</label>
        <select name="filter" class="form-select" onchange="toggleDateInput()">
            <option value="">-- Select Filter --</option>
            <option value="today" <?= $filter == 'today' ? 'selected' : '' ?>>Today</option>
            <option value="yesterday" <?= $filter == 'yesterday' ? 'selected' : '' ?>>Yesterday</option>
            <option value="this_week" <?= $filter == 'this_week' ? 'selected' : '' ?>>This Week</option>
            <option value="this_month" <?= $filter == 'this_month' ? 'selected' : '' ?>>This Month</option>
            <option value="3_months" <?= $filter == '3_months' ? 'selected' : '' ?>>Last 3 Months</option>
            <option value="6_months" <?= $filter == '6_months' ? 'selected' : '' ?>>Last 6 Months</option>
            <option value="this_year" <?= $filter == 'this_year' ? 'selected' : '' ?>>This Year</option>
            <option value="custom" <?= $filter == 'custom' ? 'selected' : '' ?>>Custom Date</option>
        </select>
    </div>

    <!-- Custom Date -->
    <div class="col-md-4">
        <label class="form-label fw-semibold">Select Date</label>
        <input type="date" name="custom_date" value="<?= esc($custom_date ?? '') ?>"
               class="form-control" id="customDate" <?= $filter == 'custom' ? '' : 'disabled' ?>>
    </div>

    <!-- Submit -->
    <div class="col-md-4">
        <button type="submit" class="btn btn-primary w-100">Apply Filter</button>
    </div>
</form>

<script>
function toggleDateInput() {
    const filter = document.querySelector('select[name="filter"]').value;
    const isCustom = filter === 'custom';
    document.getElementById('customDate').disabled = !isCustom;
}
</script>




    <table class="table table-bordered table-striped mt-3">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Patient Name</th>
                <th>Start Date & Time</th>
                <th>End Date & Time</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($appointments)): ?>
                <?php foreach ($appointments as $app): ?>
                    <tr>
                        <td><?= esc($app['id']) ?></td>
                        <td>
                            <a href="<?= site_url('doctor/patient/' . $app['patient_id']) ?>">
                                <?= esc($app['patient_name']) ?>
                            </a>
                        </td>

                        <td><?= esc($app['start_datetime']) ?></td>
                        <td><?= esc($app['end_datetime']) ?></td>

                        <td>
                            <?php if ($app['status'] == 'pending'): ?>
                                <span class="badge bg-warning text-dark">Pending</span>
                            <?php elseif ($app['status'] == 'completed'): ?>
                                <span class="badge bg-success">Completed</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Cancelled</span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <!-- Appointment Status Buttons -->
                            <?php if ($app['status'] == 'pending'): ?>
                                <a href="<?= site_url('doctor/markDone/' . $app['id']) ?>"
                                    class="btn btn-sm btn-success">Done</a>

                                <a href="<?= site_url('doctor/cancelAppointment/' . $app['id']) ?>"
                                    class="btn btn-sm btn-danger">Cancel</a>

                                <a href="<?= site_url('doctor/reschedule/' . $app['id']) ?>"
                                    class="btn btn-sm btn-warning">Reschedule</a>
                            <?php elseif ($app['status'] == 'completed'): ?>
                                <!-- Prescription -->
                                <?php if (!empty($app['prescription_id'])): ?>
                                    <a href="<?= site_url('doctor/viewPrescription/' . $app['prescription_id']) ?>"
                                        class="btn btn-sm btn-outline-success">View Prescription</a>
                                <?php else: ?>
                                    <a href="<?= site_url('doctor/addPrescription/' . $app['id']) ?>"
                                        class="btn btn-sm btn-primary">Add Prescription</a>
                                <?php endif; ?>

                                <!-- Bill -->
                                <?php if (!empty($app['bill_id'])): ?>
                                    <a href="<?= site_url('doctor/viewBill/' . $app['bill_id']) ?>"
                                        class="btn btn-sm btn-outline-info">View Bill</a>
                                <?php else: ?>
                                    <a href="<?= site_url('doctor/addBill/' . $app['id']) ?>"
                                        class="btn btn-sm btn-warning">Add Bill</a>
                                <?php endif; ?>
                            <?php elseif ($app['status'] == 'cancelled'): ?>
                                <span class="text-danger">Cancelled</span>
                            <?php endif; ?>

                            <!-- View Details -->
                            <a href="<?= site_url('doctor/viewAppointment/' . $app['id']) ?>"
                                class="btn btn-sm btn-secondary">Details</a>
                        </td>

                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">No appointments found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <a href="<?= site_url('doctor/dashboard') ?>" class="btn btn-secondary mt-3">Back to Dashboard</a>
</div>

<?= $this->endSection() ?>