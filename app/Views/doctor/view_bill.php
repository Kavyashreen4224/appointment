<?= $this->extend('layouts/doctor_layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h2 class="mb-4">Bill Details</h2>

    <div class="card shadow-sm p-4">
        <h5><strong>Bill ID:</strong> <?= esc($bill['id']) ?></h5>
        <p><strong>Appointment ID:</strong> <?= esc($bill['appointment_id']) ?></p>
        <p><strong>Patient Name:</strong> <?= esc($bill['patient_name']) ?></p>
        <p><strong>Doctor Name:</strong> <?= esc($bill['doctor_name']) ?></p>
        <p><strong>Consultation Fee:</strong> ₹<?= esc(number_format($bill['consultation_fee'], 2)) ?></p>

        <hr>
        <h5>Services</h5>
        <?php if (!empty($bill_services)): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Service Name</th>
                        <th>Price (₹)</th>
                    </tr>

                </thead>
                <tbody>
                    <?php foreach ($bill_services as $service): ?>
                        <tr>
                            <td><?= esc($service['service_name']) ?></td>
                            <td><?= esc(number_format($service['price'], 2)) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No additional services added.</p>
        <?php endif; ?>
 <p><strong>Total Amount:</strong> ₹<?= esc($bill['total_amount']) ?></p>
        <hr>
        <h5 class="mt-4">Update Payment Details</h5>
        <form action="<?= site_url('doctor/updatePaymentStatus/' . $bill['id']) ?>" method="post">
            <?= csrf_field() ?>
            <div class="row">
                <div class="col-md-4">
                    <label>Payment Status:</label>
                    <select name="payment_status" class="form-control" required>
                        <option value="Pending" <?= $bill['payment_status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="Paid" <?= $bill['payment_status'] == 'Paid' ? 'selected' : '' ?>>Paid</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label>Payment Mode:</label>
                    <select name="payment_mode" class="form-control" required>
                        <option value="Cash" <?= $bill['payment_mode'] == 'Cash' ? 'selected' : '' ?>>Cash</option>
                        <option value="Card" <?= $bill['payment_mode'] == 'Card' ? 'selected' : '' ?>>Card</option>
                        <option value="UPI" <?= $bill['payment_mode'] == 'UPI' ? 'selected' : '' ?>>UPI</option>
                        <option value="NetBanking" <?= $bill['payment_mode'] == 'NetBanking' ? 'selected' : '' ?>>NetBanking</option>
                        <option value="Insurance" <?= $bill['payment_mode'] == 'Insurance' ? 'selected' : '' ?>>Insurance</option>
                        <option value="Other" <?= $bill['payment_mode'] == 'Other' ? 'selected' : '' ?>>Other</option>
                    </select>
                </div>

                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-success w-100">Update Payment</button>
                </div>
            </div>
        </form>



        <?php if ($bill['payment_date']): ?>
            <p><strong>Payment Date:</strong> <?= esc($bill['payment_date']) ?></p>
        <?php endif; ?>
        <a href="<?= site_url('doctor/editBill/' .$bill['id']) ?>" class="btn btn-warning btn-sm mt-2">Edit Bill</a>


        <a href="<?= site_url('doctor/appointments') ?>" class="btn btn-secondary mt-3">Back</a>
       <a href="<?= site_url('doctor/downloadBill/' . $bill['id']) ?>" class="btn btn-sm btn-outline-info">

    Download Bill
</a>

    </div>
</div>

<?= $this->endSection() ?>