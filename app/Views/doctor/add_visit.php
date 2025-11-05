<?= $this->extend('layouts/doctor_layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
  <h3>Add Visit Details</h3>

  <form action="<?= site_url('doctor/saveVisit') ?>" method="post" class="mt-3">
    <input type="hidden" name="appointment_id" value="<?= esc($appointment['id']) ?>">
    <input type="hidden" name="patient_id" value="<?= esc($appointment['patient_id']) ?>">
    <input type="hidden" name="doctor_id" value="<?= esc($appointment['doctor_id']) ?>">

    <div id="complaint-section">
      <div class="complaint-group border p-3 mb-3 rounded">
        <div class="row">
          <div class="col-md-5">
            <label class="form-label fw-bold">Complaint</label>
            <select name="complaints[]" class="form-select complaint-select" required>
              <option value="">-- Select Complaint --</option>
              <option value="Fever">Fever</option>
              <option value="Cough">Cough</option>
              <option value="Cold">Cold</option>
              <option value="Headache">Headache</option>
              <option value="Stomach Pain">Stomach Pain</option>
              <option value="Body Ache">Body Ache</option>
              <option value="Nausea">Nausea</option>
              <option value="Fatigue">Fatigue</option>
              <option value="Other">Other</option>
            </select>
            <input type="text" name="other_complaints[]" class="form-control mt-2 other-complaint d-none" placeholder="Specify other complaint">
          </div>

          <div class="col-md-5">
            <label class="form-label fw-bold">Diagnosis</label>
            <select name="diagnosis[]" class="form-select diagnosis-select" required>
              <option value="">-- Select Diagnosis --</option>
              <option value="Viral Fever">Viral Fever</option>
              <option value="Common Cold">Common Cold</option>
              <option value="Bronchitis">Bronchitis</option>
              <option value="Hypertension">Hypertension</option>
              <option value="Diabetes">Diabetes</option>
              <option value="Migraine">Migraine</option>
              <option value="Gastritis">Gastritis</option>
              <option value="Anemia">Anemia</option>
              <option value="Other">Other</option>
            </select>
            <input type="text" name="other_diagnosis[]" class="form-control mt-2 other-diagnosis d-none" placeholder="Specify other diagnosis">
          </div>

          <div class="col-md-2 d-flex align-items-end">
            <button type="button" class="btn btn-danger remove-complaint w-100">Remove</button>
          </div>
        </div>
      </div>
    </div>

    <button type="button" id="add-complaint" class="btn btn-outline-primary mb-3">+ Add Another Complaint</button>

    <div class="row">
      <div class="col-md-4 mb-3">
        <label class="form-label">Weight (kg)</label>
        <input type="number" step="0.1" name="weight" class="form-control">
      </div>
      <div class="col-md-4 mb-3">
        <label class="form-label">Blood Pressure</label>
        <input type="text" name="blood_pressure" class="form-control" placeholder="120/80">
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label fw-bold">Doctor Comments</label>
      <textarea name="doctor_comments" class="form-control" rows="3" placeholder="Add any important notes..."></textarea>
    </div>

    <button type="submit" class="btn btn-success">Save Visit</button>
    <a href="<?= site_url('doctor/appointments') ?>" class="btn btn-secondary">Cancel</a>
  </form>
</div>

<!-- JavaScript for dynamic fields -->
<script>
document.addEventListener("DOMContentLoaded", function() {
  const complaintSection = document.getElementById("complaint-section");
  const addComplaintBtn = document.getElementById("add-complaint");

  // Add new complaint-diagnosis pair
  addComplaintBtn.addEventListener("click", function() {
    const newGroup = complaintSection.querySelector(".complaint-group").cloneNode(true);
    newGroup.querySelectorAll("input").forEach(input => input.value = "");
    newGroup.querySelectorAll("select").forEach(select => select.selectedIndex = 0);
    complaintSection.appendChild(newGroup);
  });

  // Show/hide "Other" input fields dynamically
  document.addEventListener("change", function(e) {
    if (e.target.classList.contains("complaint-select")) {
      const otherInput = e.target.closest(".complaint-group").querySelector(".other-complaint");
      otherInput.classList.toggle("d-none", e.target.value !== "Other");
    }
    if (e.target.classList.contains("diagnosis-select")) {
      const otherInput = e.target.closest(".complaint-group").querySelector(".other-diagnosis");
      otherInput.classList.toggle("d-none", e.target.value !== "Other");
    }
  });

  // Remove complaint group
  document.addEventListener("click", function(e) {
    if (e.target.classList.contains("remove-complaint")) {
      const groups = complaintSection.querySelectorAll(".complaint-group");
      if (groups.length > 1) e.target.closest(".complaint-group").remove();
    }
  });
});
</script>

<?= $this->endSection() ?>
