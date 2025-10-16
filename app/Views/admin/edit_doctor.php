<!DOCTYPE html>
<html>
<head>
    <title>Edit Doctor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Edit Doctor</h2>
    <form method="post" action="">
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="<?= $doctor['name'] ?>" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="<?= $doctor['email'] ?>" required>
        </div>
        <button class="btn btn-success">Update</button>
        <a href="<?= site_url('admin/dashboard') ?>" class="btn btn-secondary">Back</a>
    </form>
</div>
</body>
</html>
