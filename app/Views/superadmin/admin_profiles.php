<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Profile</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .card {
      border-radius: 12px;
      box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
    }
    .card-title {
      font-weight: 600;
      color: #0d6efd;
    }
  </style>
</head>
<body>

<div class="container mt-5">

  <!-- Back button -->
  <a href="<?= site_url('superadmin/hospitalProfile/'.$hospital['id']) ?>" class="btn btn-secondary mb-3">
    ← Back to Hospital Profile
  </a>

  <!-- Page Heading -->
  <h3 class="mb-4 text-center text-primary">Admin Profile</h3>

  <!-- Admin Info Card -->
  <div class="card mb-4">
    <div class="card-body">
      <h4 class="card-title mb-3"><?= esc($admin['name'] ?? $admin['username']) ?></h4>
      <p><strong>Email:</strong> <?= esc($admin['email'] ?? 'N/A') ?></p>
      <p><strong>Role:</strong> <?= esc($admin['role']) ?></p>
      <p><strong>Hospital:</strong> <?= esc($hospital['name'] ?? 'N/A') ?></p>
    </div>
  </div>

  <!-- Optional: Hospital Summary -->
  <div class="card">
    <div class="card-body">
      <h5 class="text-secondary mb-3">Hospital Information</h5>
      <p><strong>Hospital Name:</strong> <?= esc($hospital['name']) ?></p>
      <p><strong>Address:</strong> <?= esc($hospital['address'] ?? 'N/A') ?></p>
      <p><strong>Contact:</strong> <?= esc($hospital['contact'] ?? 'N/A') ?></p>
      <p><strong>Email:</strong> <?= esc($hospital['email'] ?? 'N/A') ?></p>
    </div>
  </div>

</div>

</body>
</html>
