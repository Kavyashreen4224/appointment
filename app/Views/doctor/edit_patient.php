<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Patient</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <h3>Edit Patient</h3>
  <form action="<?= site_url('doctor/updatePatient/' . $patient['id']) ?>" method="post" class="mt-4 bg-white p-4 rounded shadow-sm">

    <div class="mb-3">
      <label>Name</label>
      <input type="text" name="name" class="form-control" value="<?= esc($patient['name']) ?>" required>
    </div>

    <div class="mb-3">
      <label>Email</label>
      <input type="email" name="email" class="form-control" value="<?= esc($patient['email']) ?>" required>
    </div>

    <div class="mb-3">
      <label>Age</label>
      <input type="number" name="age" class="form-control" value="<?= esc($patient['age']) ?>" required>
    </div>

    <div class="mb-3">
      <label>Gender</label>
      <select name="gender" class="form-control">
        <option <?= $patient['gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
        <option <?= $patient['gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
        <option <?= $patient['gender'] == 'Other' ? 'selected' : '' ?>>Other</option>
      </select>
    </div>

    <button type="submit" class="btn btn-primary">Update</button>
    <a href="<?= site_url('doctor/patients') ?>" class="btn btn-secondary">Cancel</a>
  </form>
</div>

</body>
</html>
