<?= $this->extend('layouts/doctor_layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
  <h3>Add Bill</h3>

  <form action="<?= site_url('doctor/saveBill') ?>" method="post">
    <input type="hidden" name="appointment_id" value="<?= esc($appointment['id']) ?>">
    <input type="hidden" name="patient_id" value="<?= esc($appointment['patient_id']) ?>">
    <input type="hidden" name="doctor_id" value="<?= esc($appointment['doctor_id']) ?>">

    <div id="services-container">
      <div class="service-row mb-3 border p-3 rounded bg-light">
        <div class="row g-2">
          <div class="col-md-6">
            <label class="form-label">Service</label>
            <select name="service_id[]" class="form-select service-select" required>
              <option value="">Select Service</option>
              <?php foreach ($services as $s): ?>
                <option value="<?= $s['doctor_service_id'] ?>" data-price="<?= $s['price'] ?>">
                  <?= esc($s['service_name']) ?> (₹<?= esc($s['price']) ?>)
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label">Quantity</label>
            <input type="number" name="quantity[]" class="form-control quantity" value="1" min="1">
          </div>
          <div class="col-md-3">
            <label class="form-label">Amount (₹)</label>
            <input type="text" class="form-control amount" readonly>
          </div>
        </div>
      </div>
    </div>

    <button type="button" class="btn btn-secondary mb-3" id="addServiceRow">+ Add More</button>

    <div class="d-flex justify-content-between">
      <h4>Total: ₹<span id="total">0</span></h4>
      <button type="submit" class="btn btn-success">Save Bill</button>
    </div>
  </form>
</div>

<script>
  function calculateTotal() {
    let total = 0;
    document.querySelectorAll('.service-row').forEach(row => {
      const select = row.querySelector('.service-select');
      const qty = parseInt(row.querySelector('.quantity').value) || 1;
      const price = parseFloat(select.selectedOptions[0]?.getAttribute('data-price')) || 0;
      const amount = price * qty;
      row.querySelector('.amount').value = amount.toFixed(2);
      total += amount;
    });
    document.getElementById('total').innerText = total.toFixed(2);
  }

  document.addEventListener('change', e => {
    if (e.target.classList.contains('service-select') || e.target.classList.contains('quantity')) {
      calculateTotal();
    }
  });

  document.getElementById('addServiceRow').addEventListener('click', () => {
    const container = document.getElementById('services-container');
    const firstRow = container.firstElementChild.cloneNode(true);
    firstRow.querySelectorAll('input').forEach(i => i.value = i.classList.contains('quantity') ? 1 : '');
    firstRow.querySelector('.service-select').selectedIndex = 0;
    container.appendChild(firstRow);
  });
</script>

<?= $this->endSection() ?>
