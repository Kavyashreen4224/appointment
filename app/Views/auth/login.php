<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f4f6f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-card {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }
        .login-card h2 {
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="login-card">
    <h2>Login</h2>

    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>
    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <form action="<?= site_url('auth/loginPost') ?>" method="post">
        <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select name="role" id="role" class="form-control" required>
                <option value="">-- Select Role --</option>
                <option value="superadmin">Superadmin</option>
                <option value="admin">Admin</option>
                <option value="doctor">Doctor</option>
                <option value="patient">Patient</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>

        <div class="mb-3" id="hospital-div">
            <label for="hospital_id" class="form-label">Select Hospital</label>
            <select name="hospital_id" id="hospital_id" class="form-control">
                <option value="">-- Select Hospital --</option>
                <?php foreach ($hospitals as $hospital): ?>
                    <option value="<?= $hospital['id'] ?>"><?= esc($hospital['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>

    <p class="mt-3 text-center">
        Don't have an account? <a href="<?= site_url('auth/register') ?>">Register</a>
    </p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const roleSelect = document.getElementById('role');
    const hospitalDiv = document.getElementById('hospital-div');

    function toggleHospitalDropdown() {
        if(roleSelect.value === 'superadmin') {
            hospitalDiv.style.display = 'none';
            document.getElementById('hospital_id').removeAttribute('required');
        } else {
            hospitalDiv.style.display = 'block';
            document.getElementById('hospital_id').setAttribute('required', true);
        }
    }

    roleSelect.addEventListener('change', toggleHospitalDropdown);
    toggleHospitalDropdown(); // initial call
</script>
</body>
</html>
