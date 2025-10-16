<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Doctor</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <h3 class="text-center mb-4">✏️ Edit Doctor</h3>

  <?php if(session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger">
      <?php foreach(session()->getFlashdata('errors') as $error) { echo $error.'<br>'; } ?>
    </div>
  <?php endif; ?>

<form action="<?= site_url('superadmin/updateDoctor/'.$doctor['id']) ?>" method="post">
<label>Name</label>
<input type="text" name="name" class="form-control" value="<?= set_value('name', $user['name']) ?>" required>

<label>Email</label>
<input type="email" name="email" class="form-control" value="<?= set_value('email', $user['email']) ?>" required>

<label>Age</label>
<input type="number" name="age" class="form-control" value="<?= set_value('age', $doctor['age']) ?>" required>

<label>Gender</label>
<select name="gender" class="form-control" required>
<option value="">Select</option>
<option value="male" <?= $doctor['gender']=='male'?'selected':'' ?>>Male</option>
<option value="female" <?= $doctor['gender']=='female'?'selected':'' ?>>Female</option>
<option value="other" <?= $doctor['gender']=='other'?'selected':'' ?>>Other</option>
</select>

<label>Expertise</label>
<input type="text" name="expertise" class="form-control" value="<?= set_value('expertise', $doctor['expertise']) ?>" required>

<label>Availability</label>
<input type="text" name="availability" class="form-control" value="<?= set_value('availability', $doctor['availability']) ?>" required>

<button type="submit" class="btn btn-warning mt-2">Update Doctor</button>
</form>

</div>
</body>
</html>
