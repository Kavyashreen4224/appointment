<?= $this->extend('layouts/patient_layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
  <h2>Book Appointment</h2>

  <form method="post" action="<?= site_url('patient/bookAppointmentPost') ?>">
    <div class="mb-3">
      <label>Hospital</label>
      <select id="hospital" name="hospital_id" class="form-select" required>
        <option value="">Select Hospital</option>
        <?php foreach($hospitals as $h): ?>
          <option value="<?= $h['id'] ?>"><?= esc($h['name']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="mb-3">
      <label>Doctor</label>
      <select id="doctor" name="doctor_id" class="form-select" required>
        <option value="">Select Doctor</option>
      </select>
    </div>

    <div class="mb-3">
      <label>Start Date/Time</label>
      <input type="datetime-local" name="start_datetime" class="form-control" required>
    </div>

    <div class="mb-3">
      <label>End Date/Time</label>
      <input type="datetime-local" name="end_datetime" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-success">Book Appointment</button>
  </form>
</div>

<script>
document.getElementById('hospital').addEventListener('change', function(){
    let hospitalId = this.value;
    fetch("<?= site_url('patient/getDoctorsByHospital/') ?>" + hospitalId)
    .then(res => res.json())
    .then(data => {
        let doctorSelect = document.getElementById('doctor');
        doctorSelect.innerHTML = '<option value="">Select Doctor</option>';
        data.forEach(d => {
            doctorSelect.innerHTML += `<option value="${d.id}">${d.name}</option>`;
        });
    });
});
</script>

<?= $this->endSection() ?>
