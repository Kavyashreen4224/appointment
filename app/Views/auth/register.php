<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script>
    function toggleRoleFields() {
      const role = document.getElementById('role').value;
      document.getElementById('hospitalField').style.display = (role === 'superadmin') ? 'none' : 'block';
      document.getElementById('doctorFields').style.display = (role === 'doctor') ? 'block' : 'none';
      document.getElementById('patientFields').style.display = (role === 'patient') ? 'block' : 'none';
    }
  </script>
</head>
<body class="bg-light">
<div class="container mt-5">
  <div class="col-md-8 offset-md-2 card p-4 shadow">
    <h3 class="mb-3 text-center">Register</h3>

    <?php if(session()->getFlashdata('error')): ?>
      <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <form method="post" action="<?= site_url('registerPost') ?>">
      <?= csrf_field() ?>

      <div class="mb-3">
        <label>Name</label>
        <input type="text" name="name" class="form-control" required>
      </div>

      <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" required>
      </div>

      <div class="mb-3">
        <label>Password</label>
        <input type="password" name="password" class="form-control" required>
      </div>

      <div class="mb-3">
        <label>Role</label>
        <select id="role" name="role" class="form-select" onchange="toggleRoleFields()" required>
          <option value="">Select Role</option>
          <option value="superadmin">SuperAdmin</option>
          <option value="admin">Admin</option>
          <option value="doctor">Doctor</option>
          <option value="patient">Patient</option>
        </select>
      </div>

      <!-- Hospital Selection (Hidden for SuperAdmin) -->
      <div id="hospitalField" class="mb-3" style="display:none;">
        <label>Hospital</label>
        <select name="hospital_id" class="form-select">
          <?php foreach($hospitals as $hospital): ?>
            <option value="<?= $hospital['id'] ?>"><?= esc($hospital['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Doctor Extra Fields -->
      <div id="doctorFields" style="display:none;">
        <div class="mb-3">
          <label>Age</label>
          <input type="number" name="age" class="form-control">
        </div>
        <div class="mb-3">
          <label>Gender</label>
          <select name="gender" class="form-select">
            <option value="male">Male</option>
            <option value="female">Female</option>
            <option value="other">Other</option>
          </select>
        </div>
        <div class="mb-3">
          <label>Expertise</label>
          <input type="text" name="expertise" class="form-control">
        </div>
        <div class="mb-3">
          <label>Availability Type</label>
          <select name="availability_type" class="form-select">
            <option value="fixed">Fixed</option>
            <option value="dynamic">Dynamic</option>
          </select>
        </div>
      </div>

      <!-- Patient Extra Fields -->
      <div id="patientFields" style="display:none;">
        <div class="mb-3">
          <label>Age</label>
          <input type="number" name="age" class="form-control">
        </div>
        <div class="mb-3">
          <label>Gender</label>
          <select name="gender" class="form-select">
            <option value="male">Male</option>
            <option value="female">Female</option>
            <option value="other">Other</option>
          </select>
        </div>
      </div>

      <button type="submit" class="btn btn-success w-100">Register</button>
    </form>

    <p class="mt-3 text-center">Already have an account? <a href="<?= site_url('login') ?>">Login</a></p>
  </div>
</div>

<script>toggleRoleFields();</script>
</body>
</html>
