<!DOCTYPE html>
<html>
<head>
  <title>Admins List</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
  <h2>All Hospital Admins</h2>
  <table class="table table-bordered">
    <thead class="table-dark">
      <tr>
        <th>ID</th>
        <th>Admin Name</th>
        <th>Hospital</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($admins as $a): ?>
        <tr>
          <td><?= $a['id'] ?></td>
          <td><?= esc($a['admin_name']) ?></td>
          <td><?= esc($a['hospital_name']) ?></td>
          <td><?= esc($a['status']) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
</body>
</html>
