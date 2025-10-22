<?= $this->extend('layouts/admin_layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h2>Hospital Dashboard</h2>

    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card text-center bg-primary text-white">
                <div class="card-body">
                    <h5>Total Doctors</h5>
                    <h3><?= esc($totalDoctors) ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center bg-success text-white">
                <div class="card-body">
                    <h5>Total Patients</h5>
                    <h3><?= esc($totalPatients) ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center bg-warning text-dark">
                <div class="card-body">
                    <h5>Total Appointments</h5>
                    <h3><?= esc($totalAppointments) ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center bg-danger text-white">
                <div class="card-body">
                    <h5>Total Visits</h5>
                    <h3><?= esc($totalVisits) ?></h3>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-5">
        <a href="<?= site_url('admin/patients') ?>" class="btn btn-primary">View Patients</a>
        <a href="<?= site_url('admin/doctors') ?>" class="btn btn-secondary">View Doctors</a>
        <a href="<?= site_url('admin/appointments') ?>" class="btn btn-success">View Appointments</a>
    </div>
</div>

<?= $this->endSection() ?>
