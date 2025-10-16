<!DOCTYPE html>
<html>
<head>
    <title>Edit Appointment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Edit Appointment</h2>
    <form method="post" action="">
        <div class="mb-3">
            <label>Doctor</label>
            <select name="doctor_id" class="form-control" required>
                <option value="">Select Doctor</option>
                <?php foreach($doctors as $doc): ?>
                    <option value="<?= $doc['id'] ?>" <?= ($appointment['doctor_id'] == $doc['id']) ? 'selected' : '' ?>><?= $doc['name'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label>Patient</label>
            <select name="patient_id" class="form-control" required>
                <option value="">Select Patient</option>
                <?php foreach($patients as $pat): ?>
                    <option value="<?= $pat['id'] ?>" <?= ($appointment['patient_id'] == $pat['id']) ? 'selected' : '' ?>><?= $pat['name'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label>Date</label>
            <input type="date" name="date" class="form-control" value="<?= $appointment['date'] ?>" required>
        </div>
        <button class="btn btn-success">Update</button>
        <a href="<?= site_url('admin/dashboard') ?>" class="btn btn-secondary">Back</a>
    </form>
</div>
</body>
</html>
