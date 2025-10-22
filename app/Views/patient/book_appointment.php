<!DOCTYPE html>
<html>
<head>
    <title>Book Appointment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h3>Book Appointment</h3>
    <form action="<?= site_url('patient/saveAppointment') ?>" method="post">

        <div class="mb-3">
            <label for="hospital" class="form-label">Select Hospital</label>
            <select name="hospital_id" id="hospital" class="form-select" required onchange="filterDoctors(this.value)">
                <option value="">-- Choose Hospital --</option>
                <?php foreach($hospitals as $hospital): ?>
                    <option value="<?= $hospital['id'] ?>"><?= esc($hospital['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="doctor" class="form-label">Select Doctor</label>
            <select name="doctor_id" id="doctor" class="form-select" required>
                <option value="">-- Choose Doctor --</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="date" class="form-label">Date</label>
            <input type="date" name="appointment_date" id="date" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="time" class="form-label">Time</label>
            <input type="time" name="appointment_time" id="time" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">Book Appointment</button>
        <a href="<?= site_url('patient/dashboard') ?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<script>
    const allDoctors = <?= json_encode($doctors) ?>;

    function filterDoctors(hospitalId) {
        const doctorSelect = document.getElementById('doctor');
        doctorSelect.innerHTML = '<option value="">-- Choose Doctor --</option>';

        allDoctors.forEach(doc => {
            if(doc.hospital_id == hospitalId) {
                const option = document.createElement('option');
                option.value = doc.id;
                option.text = `${doc.doctor_name} (${doc.expertise}) - ${doc.hospital_name}`;
                doctorSelect.appendChild(option);
            }
        });
    }
</script>
</body>
</html>
