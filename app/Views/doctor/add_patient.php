<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Patient</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <h3>Add Patient</h3>
  <form action="<?= site_url('doctor/savePatient') ?>" method="post" class="mt-4 bg-white p-4 rounded shadow-sm">

    <div class="mb-3">
      <label>Name</label>
      <input type="text" name="name" class="form-control" required>
    </div>

    <div class="mb-3">
      <label>Email</label>
      <input type="email" name="email" class="form-control" required>
    </div>

    <div class="mb-3">
      <label>Age</label>
      <input type="number" name="age" class="form-control" required>
    </div>

    <div class="mb-3">
      <label>Gender</label>
      <select name="gender" class="form-control">
        <option value="Male">Male</option>
        <option value="Female">Female</option>
        <option value="Other">Other</option>
      </select>
    </div>

    <div class="mb-3">
      <label>Password</label>
      <input type="password" name="password" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-success">Add Patient</button>
    <a href="<?= site_url('doctor/patients') ?>" class="btn btn-secondary">Cancel</a>
  </form>
</div>

</body>
</html>
