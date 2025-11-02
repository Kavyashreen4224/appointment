<?= $this->extend('layouts/superadmin_layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h2>Patient Profile: <?= esc($patient['name']) ?></h2>
    <hr>

    <h5>🧾 Basic Details</h5>
    <p><strong>Name:</strong> <?= esc($patient['name']) ?></p>
    <p><strong>Email:</strong> <?= esc($patient['email']) ?></p>
    <p><strong>Registered At:</strong> <?= date('d M Y', strtotime($patient['created_at'])) ?></p>

    <h5 class="mt-4">🏥 Hospital Details</h5>
    <p><strong>Hospital:</strong> <?= esc($patient['hospital_name']) ?></p>
    <p><strong>Status:</strong> <span class="badge <?= $patient['status'] === 'active' ? 'bg-success' : 'bg-secondary' ?>"><?= ucfirst($patient['status']) ?></span></p>
</div>

<?= $this->endSection() ?>
