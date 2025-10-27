<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Bill #<?= esc($bill['id']) ?></title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; margin: 20px; }
        h2, h3 { text-align: center; margin: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #ccc; }
        th, td { padding: 8px; text-align: left; }
        .header, .footer { text-align: center; margin-bottom: 10px; }
        .total { text-align: right; font-weight: bold; }
        .section-title { margin-top: 25px; font-size: 16px; border-bottom: 2px solid #000; }
    </style>
</head>
<body>

    <!-- Hospital Details -->
    <div class="header">
        <h2><?= esc($hospital['name']) ?></h2>
        <p><?= esc($hospital['address']) ?><br>
        Phone: <?= esc($hospital['contact_number']) ?></p>
    </div>

    <hr>

    <!-- Patient & Visit Info -->
    <h3>Patient Visit Details</h3>
    <table>
        <tr>
            <th>Patient Name</th>
            <td><?= esc($appointment['patient_name']) ?></td>
            <th>Visit Date</th>
            <td><?= esc($appointment['appointment_date']) ?></td>
        </tr>
        <tr>
            <th>Doctor</th>
            <td><?= esc($appointment['doctor_name']) ?></td>
            <th>Department</th>
            <td><?= esc($appointment['department']) ?></td>
        </tr>
    </table>

    <!-- Billing Details -->
    <h3 class="section-title">Billing Details</h3>
    <table>
        <thead>
            <tr>
                <th>Service</th>
                <th>Description</th>
                <th>Amount (₹)</th>
            </tr>
        </thead>
        <tbody>
            <?php $total = 0; ?>
            <?php foreach ($services as $s): ?>
                <tr>
                    <td><?= esc($s['service_name']) ?></td>
                    <td><?= esc($s['description']) ?></td>
                    <td><?= number_format($s['price'], 2) ?></td>
                </tr>
                <?php $total += $s['price']; ?>
            <?php endforeach; ?>

            <tr>
                <td colspan="2">Consultation Fee</td>
                <td><?= number_format($bill['consultation_fee'], 2) ?></td>
                <?php $total += $bill['consultation_fee']; ?>
            </tr>
            <tr>
                <td colspan="2" class="total">Total Amount</td>
                <td class="total"><?= number_format($total, 2) ?></td>
            </tr>
        </tbody>
    </table>

    <!-- Payment Info -->
    <p><strong>Payment Status:</strong> <?= esc(ucfirst($bill['payment_status'])) ?></p>
    <?php if (!empty($bill['payment_date'])): ?>
        <p><strong>Payment Date:</strong> <?= esc($bill['payment_date']) ?></p>
    <?php endif; ?>

    <hr>

    <div class="footer">
        <p>Thank you for visiting <?= esc($hospital['name']) ?>.<br>
        Get well soon!</p>
    </div>

</body>
</html>
