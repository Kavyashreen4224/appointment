<?= $this->extend('layouts/patient_layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">

  <h2 class="mb-3">Welcome, <?= esc($patient['patient_name']) ?> 👋</h2>

  <!-- Patient Info -->
  <div class="alert alert-info">
    <strong>Hospital:</strong> <?= esc($hospital['name']) ?><br>
    <strong>Email:</strong> <?= esc($patient['email']) ?><br>
    <strong>Age:</strong> <?= esc($patient['age']) ?> |
    <strong>Gender:</strong> <?= esc($patient['gender']) ?>
  </div>

  

  <!-- Quick Stats -->
<div class="row g-4 mb-4">
  <div class="col-md-3">
    <div class="card text-center p-3 shadow-sm">
      <h5>Total</h5>
      <h2><?= esc($totalAppointments) ?></h2>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card text-center p-3 shadow-sm">
      <h5>Pending</h5>
      <h2 class="text-warning"><?= esc($pendingAppointments) ?></h2>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card text-center p-3 shadow-sm">
      <h5>Completed</h5>
      <h2 class="text-success"><?= esc($completedAppointments) ?></h2>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card text-center p-3 shadow-sm">
      <h5>Cancelled</h5>
      <h2 class="text-danger"><?= esc($cancelledAppointments) ?></h2>
    </div>
  </div>
</div>


  <!-- 📅 Upcoming Appointments -->
  <div class="card shadow-sm mb-4">
    <div class="card-header bg-primary text-white"><strong>Upcoming Appointments</strong></div>
    <div class="card-body">
      <?php if (!empty($upcomingAppointments)): ?>
        <table class="table table-bordered">
          <thead class="table-light">
            <tr>
              <th>#</th>
              <th>Doctor</th>
              <th>Date</th>
              <th>Time</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($upcomingAppointments as $i => $a): ?>
              <tr>
                <td><?= $i + 1 ?></td>
                <td><?= esc($a['doctor_name']) ?></td>
                <td><?= date('d M Y', strtotime($a['start_datetime'])) ?></td>
                <td><?= date('h:i A', strtotime($a['start_datetime'])) ?></td>
                <td><span class="badge bg-warning text-dark"><?= ucfirst($a['status']) ?></span></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php else: ?>
        <p class="text-muted mb-0">No upcoming appointments 🎉</p>
      <?php endif; ?>
    </div>
  </div>

  <!-- 📊 Health Tracking Graphs -->
  <div class="row g-4">
    <div class="col-md-6">
      <div class="card shadow-sm p-3">
        <h5 class="text-center mb-3">Weight Trend (kg)</h5>
        <canvas id="weightChart" height="150"></canvas>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card shadow-sm p-3">
        <h5 class="text-center mb-3">Blood Pressure Trend (mmHg)</h5>
        <canvas id="bpChart" height="150"></canvas>
      </div>
    </div>
  </div>

</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const dates = <?= $dates ?>;
const weights = <?= $weights ?>;
const systolic = <?= $bpSystolic ?>;
const diastolic = <?= $bpDiastolic ?>;

// Weight Chart
new Chart(document.getElementById('weightChart'), {
  type: 'line',
  data: {
    labels: dates,
    datasets: [{
      label: 'Weight (kg)',
      data: weights,
      borderColor: '#28a745',
      tension: 0.3,
      fill: false,
      pointRadius: 5,
      borderWidth: 2
    }]
  },
  options: {
    scales: {
      y: { beginAtZero: false }
    }
  }
});

// BP Chart
new Chart(document.getElementById('bpChart'), {
  type: 'line',
  data: {
    labels: dates,
    datasets: [
      {
        label: 'Systolic',
        data: systolic,
        borderColor: '#007bff',
        tension: 0.3,
        fill: false,
        borderWidth: 2
      },
      {
        label: 'Diastolic',
        data: diastolic,
        borderColor: '#dc3545',
        tension: 0.3,
        fill: false,
        borderWidth: 2
      }
    ]
  },
  options: {
    scales: {
      y: { beginAtZero: false }
    }
  }
});
</script>

<?= $this->endSection() ?>
