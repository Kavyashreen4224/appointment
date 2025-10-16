<?= $this->extend('layouts/admin_layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h2>Add Prescription</h2>
    <form method="post">
        <div class="mb-3">
            <label>Prescription Text</label>
            <textarea name="prescription_text" class="form-control" required></textarea>
        </div>
        <button type="submit" class="btn btn-success">Save</button>
        <a href="<?= site_url('admin/visits/'.$visit_id) ?>" class="btn btn-secondary">Back</a>
    </form>
</div>

<?= $this->endSection() ?>
