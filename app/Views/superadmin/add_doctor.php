<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Doctor</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <h3 class="text-center mb-4">➕ Add Doctor</h3>

  <?php if(session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger">
      <?php foreach(session()->getFlashdata('errors') as $error) { echo $error.'<br>'; } ?>
    </div>
  <?php endif; ?>

  <form action="<?= site_url('superadmin/saveDoctor/'.$hospital_id) ?>" method="post">
<label>Name</label>
<input type="text" name="name" class="form-control" value="<?= set_value('name') ?>" required>

<label>Email</label>
<input type="email" name="email" class="form-control" value="<?= set_value('email') ?>" required>

<label>Password</label>
<input type="password" name="password" class="form-control" required>

<label>Age</label>
<input type="number" name="age" class="form-control" value="<?= set_value('age') ?>" required>

<label>Gender</label>
<select name="gender" class="form-control" required>
<option value="">Select</option>
<option value="male">Male</option>
<option value="female">Female</option>
<option value="other">Other</option>
</select>

<label>Expertise</label>
<input type="text" name="expertise" class="form-control" value="<?= set_value('expertise') ?>" required>

<label>Availability</label>
<input type="text" name="availability" class="form-control" value="<?= set_value('availability') ?>" placeholder="e.g., Mon-Fri 10AM-5PM" required>

<button type="submit" class="btn btn-success mt-2">Add Doctor</button>
</form>

</div>
</body>
</html>
