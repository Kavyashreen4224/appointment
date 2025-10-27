<?= $this->extend('layouts/doctor_layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h3>Edit Bill</h3>
    <hr>

    <form action="<?= site_url('doctor/updateBill/' . $bill['id']) ?>" method="post">
        <div class="form-group">
            <label>Consultation Fee</label>
            <input type="number" name="consultation_fee" class="form-control" value="<?= esc($bill['consultation_fee']) ?>" required>
        </div>

        <div class="form-group mt-3">
            <label>Select Additional Services:</label><br>
            <?php if (!empty($services)): ?>
                <?php foreach ($services as $service): ?>
                    <div class="form-check">
                        <input type="checkbox" name="services[]" value="<?= $service['id'] ?>"
                            class="form-check-input"
                            <?= in_array($service['id'], $selectedServiceIds) ? 'checked' : '' ?>>
                        <label class="form-check-label">
                            <?= esc($service['name']) ?> — ₹<?= esc($service['price']) ?>
                        </label>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-muted">No additional services available.</p>
            <?php endif; ?>
        </div>

        <div class="form-group mt-3">
            <label>Payment Status</label>
            <select name="payment_status" class="form-control" required>
                <option value="Pending" <?= $bill['payment_status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
                <option value="Paid" <?= $bill['payment_status'] === 'Paid' ? 'selected' : '' ?>>Paid</option>
            </select>
        </div>

        <div class="form-group mt-3">
            <label>Payment Mode</label>
            <select name="payment_mode" class="form-control" required>
                <option value="Cash" <?= $bill['payment_mode'] === 'Cash' ? 'selected' : '' ?>>Cash</option>
                <option value="Card" <?= $bill['payment_mode'] === 'Card' ? 'selected' : '' ?>>Card</option>
                <option value="UPI" <?= $bill['payment_mode'] === 'UPI' ? 'selected' : '' ?>>UPI</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success mt-3">Update Bill</button>
        <a href="<?= site_url('doctor/viewBill/' . $bill['id']) ?>" class="btn btn-secondary mt-3">Cancel</a>
    </form>
</div>

<?= $this->endSection() ?>
