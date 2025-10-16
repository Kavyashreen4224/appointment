<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .register-card {
            max-width: 500px;
            margin: 50px auto;
            padding: 30px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .role-fields {
            display: none;
        }
    </style>
</head>
<body>

<div class="register-card">
    <h3 class="text-center mb-4">Register</h3>

    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <form action="<?= site_url('auth/registerPost') ?>" method="post">
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
            <select name="role" id="roleSelect" class="form-control" required>
                <option value="">Select Role</option>
                <option value="superadmin">Superadmin</option>
                <option value="admin">Admin</option>
                <option value="doctor">Doctor</option>
                <option value="patient">Patient</option>
            </select>
        </div>

        <!-- Hospital dropdown: only visible for admin, doctor, patient -->
        <div class="mb-3 role-fields" id="hospitalField">
            <label>Select Hospital</label>
            <select name="hospital_id" class="form-control">
                <?php foreach ($hospitals as $hospital): ?>
                    <option value="<?= $hospital['id'] ?>"><?= esc($hospital['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Doctor / Patient additional fields -->
        <div class="role-fields" id="extraFields">
            <div class="mb-3">
                <label>Age</label>
                <input type="number" name="age" class="form-control">
            </div>

            <div class="mb-3">
                <label>Gender</label>
                <select name="gender" class="form-control">
                    <option value="">Select Gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>
            </div>
        </div>

        <!-- Doctor expertise / availability -->
        <div class="role-fields" id="doctorFields">
            <div class="mb-3">
                <label>Expertise</label>
                <input type="text" name="expertise" class="form-control">
            </div>
            <div class="mb-3">
                <label>Availability</label>
                <input type="text" name="availability" class="form-control">
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100">Register</button>
    </form>
</div>

<script>
    const roleSelect = document.getElementById('roleSelect');
    const hospitalField = document.getElementById('hospitalField');
    const extraFields = document.getElementById('extraFields');
    const doctorFields = document.getElementById('doctorFields');

    roleSelect.addEventListener('change', function() {
        const role = this.value;

        if(role === 'superadmin') {
            hospitalField.style.display = 'none';
            extraFields.style.display = 'none';
            doctorFields.style.display = 'none';
        } else if(role === 'admin') {
            hospitalField.style.display = 'block';
            extraFields.style.display = 'none';
            doctorFields.style.display = 'none';
        } else if(role === 'doctor') {
            hospitalField.style.display = 'block';
            extraFields.style.display = 'block';
            doctorFields.style.display = 'block';
        } else if(role === 'patient') {
            hospitalField.style.display = 'block';
            extraFields.style.display = 'block';
            doctorFields.style.display = 'none';
        } else {
            hospitalField.style.display = 'none';
            extraFields.style.display = 'none';
            doctorFields.style.display = 'none';
        }
    });
</script>

</body>
</html>
