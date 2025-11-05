<?= $this->extend('layouts/doctor_layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
  <h3>Add Prescription</h3>

  <form action="<?= site_url('doctor/savePrescription') ?>" method="post" id="prescriptionForm">
    <input type="hidden" name="visit_id" value="<?= esc($visit['id']) ?>">
    <input type="hidden" name="doctor_id" value="<?= esc($visit['doctor_id']) ?>">
    <input type="hidden" name="patient_id" value="<?= esc($visit['patient_id']) ?>">

    <div id="medicineList">
      <div class="medicine-item border p-3 mb-3 rounded">
        <div class="row g-2">
          <div class="col-md-3">
            <label class="form-label">Medicine Name</label>
            <input type="text" name="medicine_name[]" class="form-control" required>
          </div>
          <div class="col-md-2">
            <label class="form-label">Dosage</label>
            <input type="text" name="dosage[]" class="form-control" placeholder="e.g. 500mg">
          </div>
          <div class="col-md-2">
            <label class="form-label">Frequency</label>
            <input type="text" name="frequency[]" class="form-control" placeholder="Morning/Night">
          </div>
          <div class="col-md-2">
            <label class="form-label">Duration</label>
            <input type="text" name="duration[]" class="form-control" placeholder="e.g. 5 days">
          </div>
          <div class="col-md-3">
            <label class="form-label">Usage Instruction</label>
            <input type="text" name="usage_instruction[]" class="form-control" placeholder="After food">
          </div>
        </div>
        <div class="mt-2">
          <label class="form-label">Related Diagnosis</label>
          <input type="text" name="related_diagnosis[]" class="form-control" placeholder="e.g. Fever, Cough">
        </div>
      </div>
    </div>

    <button type="button" class="btn btn-outline-primary mb-3" id="addMedicine">+ Add Another Medicine</button>

    <div class="mb-3">
      <label class="form-label fw-bold">Prescription Notes</label>
      <textarea name="notes" class="form-control" rows="3" placeholder="General notes..."></textarea>
    </div>

    <button type="submit" class="btn btn-success">Save Prescription</button>
    <a href="<?= site_url('doctor/appointments') ?>" class="btn btn-secondary">Cancel</a>
  </form>
</div>

<script>
document.getElementById('addMedicine').addEventListener('click', function() {
  const list = document.getElementById('medicineList');
  const item = document.querySelector('.medicine-item').cloneNode(true);
  item.querySelectorAll('input').forEach(i => i.value = '');
  list.appendChild(item);
});
</script>

<?= $this->endSection() ?>
