<?= $this->extend('layouts/doctor_layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">

  <!-- 👋 Doctor Greeting -->
  <h2 class="mb-3">Welcome, Dr. <?= esc($doctor['doctor_name']) ?> 👋</h2>

  <!-- 🏥 Hospital Info -->
  <?php if (!empty($hospital)): ?>
    <div class="alert alert-info">
      <strong>Hospital:</strong> <?= esc($hospital['name']) ?><br>
      <strong>Address:</strong> <?= esc($hospital['address']) ?><br>
      <strong>Contact:</strong> <?= esc($hospital['contact']) ?><br>
      <strong>Email:</strong> <?= esc($hospital['email']) ?>
    </div>
  <?php endif; ?>

  <!-- 📊 Quick Stats -->
  <div class="row g-4 mb-4">
    <div class="col-md-6 col-lg-3">
      <div class="card text-center p-3 shadow-sm">
        <h5>Total Appointments</h5>
        <h2><?= esc($appointmentCount) ?></h2>
        <a href="<?= site_url('doctor/appointments') ?>" class="btn btn-sm btn-outline-primary mt-2">View</a>
      </div>
    </div>

    <div class="col-md-6 col-lg-3">
      <div class="card text-center p-3 shadow-sm">
        <h5>Patients Seen</h5>
        <h2><?= esc($patientCount) ?></h2>
        <a href="<?= site_url('doctor/patients') ?>" class="btn btn-sm btn-outline-primary mt-2">Manage</a>
      </div>
    </div>
  </div>

  <!-- 📅 Today’s Upcoming Appointments -->
 
<!-- 📅 Pending Appointments for Today -->
<div class="card shadow-sm mt-4">
  <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
    <strong>Today's Pending Appointments</strong>
    <a href="<?= site_url('doctor/appointments') ?>" class="btn btn-light btn-sm">View All</a>
  </div>

  <div class="card-body">
    <?php if (!empty($upcomingAppointments)): ?>
      <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th>#</th>
              <th>Patient</th>
              <th>Start Time</th>
              <th>End Time</th>
              <th>status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($upcomingAppointments as $index => $a): ?>
              <tr>
                <td><?= $index + 1 ?></td>
                <td><?= esc($a['patient_name']) ?></td>
                <td><?= date('h:i A', strtotime($a['start_datetime'])) ?></td>
                <td><?= date('h:i A', strtotime($a['end_datetime'])) ?></td>
                  <td><?= esc($a['status']) ?></td>
                <td>
                  <a href="<?= site_url('doctor/markDone/' . $a['id']) ?>" class="btn btn-sm btn-success">Mark Done</a>
                  <a href="<?= site_url('doctor/rescheduleAppointment/' . $a['id']) ?>" class="btn btn-sm btn-info">Reschedule</a>
                  <a href="<?= site_url('doctor/cancelAppointment/' . $a['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Cancel this appointment?');">Cancel</a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <p class="text-muted text-center mb-0">No pending appointments for today 🎉</p>
    <?php endif; ?>
  </div>
</div>

</div>

</div>

<?= $this->endSection() ?>
