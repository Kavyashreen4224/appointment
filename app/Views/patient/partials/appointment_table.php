<?php if (!empty($appointments)): ?>
  <div class="table-responsive mt-3">
    <table class="table table-bordered align-middle">
      <thead class="table-dark">
        <tr>
          <th>#</th>
          <th>Doctor</th>
          <th>Date</th>
          <th>Time</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($appointments as $i => $a): ?>
          <tr>
            <td><?= $i + 1 ?></td>
            <td><?= esc($a['doctor_name']) ?></td>
            <td><?= date('d M Y', strtotime($a['start_datetime'])) ?></td>
            <td><?= date('h:i A', strtotime($a['start_datetime'])) ?> - <?= date('h:i A', strtotime($a['end_datetime'])) ?></td>
            <td>
              <?php if ($a['status'] === 'pending'): ?>
                <span class="badge bg-warning text-dark">Pending</span>
              <?php elseif ($a['status'] === 'completed'): ?>
                <span class="badge bg-success">Completed</span>
              <?php elseif ($a['status'] === 'cancelled'): ?>
                <span class="badge bg-danger">Cancelled</span>
              <?php endif; ?>
            </td>

            <td>
              <?php if ($a['status'] === 'pending'): ?>
                <a href="<?= site_url('patient/cancelAppointment/' . $a['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Cancel this appointment?');">Cancel</a>

              <?php elseif ($a['status'] === 'completed'): ?>
                <?php if (!empty($a['visit_id'])): ?>
                  <a href="<?= site_url('patient/viewVisit/' . $a['visit_id']) ?>" class="btn btn-sm btn-outline-secondary">Visit</a>
                <?php endif; ?>

                <?php if (!empty($a['prescription_id'])): ?>
                  <a href="<?= site_url('patient/viewPrescription/' . $a['prescription_id']) ?>" class="btn btn-sm btn-success">Prescription</a>
                <?php endif; ?>

                <?php if (!empty($a['bill_id'])): ?>
                  <a href="<?= site_url('patient/viewBill/' . $a['bill_id']) ?>" class="btn btn-sm btn-primary">Bill</a>
                <?php endif; ?>

              <?php elseif ($a['status'] === 'cancelled'): ?>
                <span class="text-muted">No Actions</span>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php else: ?>
  <p class="text-muted text-center mt-3">No <?= ucfirst($status) ?> appointments found.</p>
<?php endif; ?>
