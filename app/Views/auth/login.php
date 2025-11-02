<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Multi Hospital System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-lg border-0">
                <div class="card-header text-center bg-primary text-white">
                    <h4>Login</h4>
                </div>
                <div class="card-body">
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                    <?php endif; ?>

                    <form action="<?= base_url('auth/loginPost') ?>" method="post">
  <div class="mb-3">
    <label>Email</label>
    <input type="email" name="email" class="form-control" placeholder="Enter email" required>
  </div>

  <div class="mb-3">
    <label>Password</label>
    <input type="password" name="password" class="form-control" placeholder="Enter password" required>
  </div>

  <div class="mb-3">
    <label>Role</label>
    <select name="role" id="role" class="form-control" required>
      <option value="">-- Select Role --</option>
      <option value="superadmin">Super Admin</option>
      <option value="admin">Admin</option>
      <option value="doctor">Doctor</option>
      <option value="patient">Patient</option>
    </select>
  </div>

  <div class="mb-3" id="hospitalDiv" style="display:none;">
    <label>Hospital</label>
    <select name="hospital_id" class="form-control" id="hospitalSelect">
      <option value="">-- Select Hospital --</option>
      <?php if (isset($hospitals)): ?>
        <?php foreach ($hospitals as $h): ?>
          <option value="<?= $h['id'] ?>"><?= esc($h['name']) ?></option>
        <?php endforeach; ?>
      <?php endif; ?>
    </select>
  </div>

  <button type="submit" class="btn btn-primary w-100">Login</button>
  <p class="text-center mt-3 mb-0">
    Don’t have an account? <a href="<?= base_url('register') ?>">Register here</a>
  </p>
</form>

<script>
document.getElementById('role').addEventListener('change', function() {
  const selectedRole = this.value;
  const hospitalDiv = document.getElementById('hospitalDiv');
  hospitalDiv.style.display = (selectedRole && selectedRole !== 'superadmin') ? 'block' : 'none';
});
</script>

                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
