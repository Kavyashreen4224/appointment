<!DOCTYPE html>
<html>
<head>
    <title>doctor Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <a href="<?= site_url('doctor/dashboard') ?>" class="navbar-brand">doctor Dashboard</a>
        <a href="<?= site_url('auth/logout') ?>" class="btn btn-outline-light">Logout</a>
    </div>
</nav>

<div class="container-fluid mt-3">
    <?= $this->renderSection('content') ?>
</div>
</body>
</html>
