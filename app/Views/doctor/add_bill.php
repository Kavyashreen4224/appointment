<?= $this->extend('layouts/doctor_layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h2 class="mb-4">Add Bill for Appointment #<?= esc($appointment['id']) ?></h2>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <form action="<?= site_url('doctor/saveBill') ?>" method="post" id="billForm">
        <input type="hidden" name="appointment_id" value="<?= esc($appointment['id']) ?>">

        <div class="card p-4 shadow-sm">
            <div class="mb-3">
                <label class="form-label">Patient Name:</label>
                <input type="text" class="form-control" value="<?= esc($appointment['patient_name']) ?>" readonly>
            </div>

            <div class="mb-3">
                <label class="form-label">Consultation Fee (₹):</label>
                <input type="number" class="form-control" name="consultation_fee" id="consultation_fee" value="500" min="0" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Select Services:</label><br>
                <?php if (!empty($services)): ?>
                    <?php foreach ($services as $service): ?>
                        <div class="form-check">
                            <input class="form-check-input service-checkbox"
                                   type="checkbox"
                                   name="services[]"
                                   value="<?= esc($service['id']) ?>"
                                   data-price="<?= esc($service['price']) ?>">
                            <label class="form-check-label">
                                <?= esc($service['name']) ?> (₹<?= esc($service['price']) ?>)
                            </label>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted">No additional services available.</p>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label class="form-label">Total Amount (₹):</label>
                <input type="text" class="form-control" id="total_amount" value="500" readonly>
            </div>

            <div class="mb-3">
                <label class="form-label">Payment Status:</label>
                <select name="payment_status" class="form-select" required>
                    <option value="Pending">Pending</option>
                    <option value="Paid">Paid</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Payment Mode:</label>
                <select name="payment_mode" class="form-select" required>
                    <option value="Cash">Cash</option>
                    <option value="Card">Card</option>
                    <option value="UPI">UPI</option>
                    <option value="NetBanking">Net Banking</option>
                    <option value="Insurance">Insurance</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Save Bill</button>
            <a href="<?= site_url('doctor/appointments') ?>" class="btn btn-secondary">Back</a>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const consultationInput = document.getElementById('consultation_fee');
        const checkboxes = document.querySelectorAll('.service-checkbox');
        const totalInput = document.getElementById('total_amount');

        function calculateTotal() {
            let total = parseFloat(consultationInput.value || 0);
            checkboxes.forEach(cb => {
                if (cb.checked) total += parseFloat(cb.dataset.price);
            });
            totalInput.value = total.toFixed(2);
        }

        consultationInput.addEventListener('input', calculateTotal);
        checkboxes.forEach(cb => cb.addEventListener('change', calculateTotal));

        calculateTotal();
    });
</script>

<?= $this->endSection() ?>
