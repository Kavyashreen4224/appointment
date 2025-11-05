<?= $this->extend('layouts/doctor_layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
  <h3>Appointments</h3>
  <a href="<?= site_url('doctor/addAppointment') ?>" class="btn btn-primary mb-3">Add Appointment</a>

  <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
  <?php endif; ?>

  <table class="table table-bordered bg-white">
    <thead class="table-dark">
      <tr>
        <th>ID</th>
        <th>Patient</th>
        <th>Start</th>
        <th>End</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($appointments)): ?>
        <?php foreach ($appointments as $a): ?>
          <tr>
            <td><?= $a['id'] ?></td>
            <td><?= esc($a['patient_name']) ?></td>
            <td><?= esc($a['start_datetime']) ?></td>
            <td><?= esc($a['end_datetime']) ?></td>
            <td>
              <?php if ($a['status'] === 'pending'): ?>
                <span class="badge bg-warning text-dark">Pending</span>
              <?php elseif ($a['status'] === 'completed'): ?>
                <span class="badge bg-success">Completed</span>
              <?php elseif ($a['status'] === 'cancelled'): ?>
                <span class="badge bg-danger">Cancelled</span>
              <?php else: ?>
                <span class="badge bg-secondary"><?= esc($a['status']) ?></span>
              <?php endif; ?>
            </td>

            <td>
  <?php if ($a['status'] === 'pending'): ?>
    <a href="<?= site_url('doctor/rescheduleAppointment/' . $a['id']) ?>" class="btn btn-sm btn-info">Reschedule</a>
    <a href="<?= site_url('doctor/markDone/' . $a['id']) ?>" class="btn btn-sm btn-success">Done</a>
    <a href="<?= site_url('doctor/cancelAppointment/' . $a['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Cancel this appointment?');">Cancel</a>

  <?php elseif ($a['status'] === 'completed'): ?>
    <!-- View Visit Details -->
    <a href="<?= site_url('doctor/viewVisit/' . $a['id']) ?>" class="btn btn-sm btn-outline-secondary">View Visit</a>

    <!-- Prescription -->
   <?php if (!empty($a['visit_id'])): ?>
    <?php if (empty($a['prescription_id'])): ?>
      <a href="<?= site_url('doctor/addPrescription/' . $a['visit_id']) ?>" class="btn btn-sm btn-success">Add Prescription</a>
    <?php else: ?>
      <a href="<?= site_url('doctor/viewPrescription/' . $a['visit_id']) ?>" class="btn btn-sm btn-outline-success">View Prescription</a>
    <?php endif; ?>
<?php endif; ?>


    <!-- Bill -->
    <?php if (empty($a['bill_id'])): ?>
      <a href="<?= site_url('doctor/addBill/' . $a['id']) ?>" class="btn btn-sm btn-primary">Add Bill</a>
    <?php else: ?>
      <a href="<?= site_url('doctor/viewBill/' . $a['bill_id']) ?>" class="btn btn-sm btn-outline-primary">View Bill</a>
    <?php endif; ?>

  <?php elseif ($a['status'] === 'cancelled'): ?>
    <span class="text-muted">No Actions</span>
  <?php endif; ?>
</td>

          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="6" class="text-center text-muted">No appointments found.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?= $this->endSection() ?>
