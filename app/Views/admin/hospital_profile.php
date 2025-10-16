<?= $this->extend('layouts/admin_layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h2>Hospital Profile</h2>

    <div class="card mb-3">
        <div class="card-body">
            <h4><?= esc($hospital['name']) ?></h4>
            <p><strong>Address:</strong> <?= esc($hospital['address']) ?></p>
            <p><strong>Contact:</strong> <?= esc($hospital['contact']) ?></p>
            <p><strong>Email:</strong> <?= esc($hospital['email']) ?></p>
            <p><strong>Status:</strong> <?= esc($hospital['status']) ?></p>
        </div>
    </div>

    <a href="<?= site_url('admin/dashboard') ?>" class="btn btn-secondary">Back to Dashboard</a>
</div>

<?= $this->endSection() ?>
