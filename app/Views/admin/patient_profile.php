<?= $this->extend('layouts/admin_layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h2><?= esc($patient['patient_name']) ?>'s Profile</h2>
    <p><strong>Email:</strong> <?= esc($patient['email']) ?></p>
    <hr>

    <h4>Visit History</h4>
    <table class="table table-bordered bg-white">
        <thead class="table-dark">
            <tr>
                <th>Doctor</th>
                <th>Reason</th>
                <th>BP</th>
                <th>Weight</th>
                <th>Doctor Comments</th>
                <th>Prescription</th>
            </tr>
        </thead>
        <tbody>
            <?php if($visits): ?>
                <?php foreach($visits as $v): ?>
                    <tr>
                        <td><?= esc($v['doctor_name']) ?></td>
                        <td><?= esc($v['reason']) ?></td>
                        <td><?= esc($v['blood_pressure']) ?></td>
                        <td><?= esc($v['weight']) ?></td>
                        <td><?= esc($v['doctor_comments']) ?></td>
                        <td>
                            <?php if($v['prescription_id']): ?>
                                <a href="<?= site_url('admin/viewPrescription/'.$v['prescription_id']) ?>" 
                                   class="btn btn-success btn-sm">View Prescription</a>
                            <?php else: ?>
                                <span class="text-muted">No Prescription</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6" class="text-center">No visits found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>
