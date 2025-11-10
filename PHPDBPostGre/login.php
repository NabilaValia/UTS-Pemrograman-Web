<?php
session_start();
// jika sudah login, redirect ke dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}
?>
<!doctype html> 
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Login | PORTOFOLIO </title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light d-flex align-items-center justify-content-center vh-100">

  <div class="card shadow-lg border-0" style="max-width: 400px; width: 100%;">
    <div class="card-body p-4">
      <h5 class="text-center mb-3 fw-semibold">Masuk ke Akun</h5>

      <?php if (!empty($_GET['error'])): ?>
        <div class="alert alert-danger text-center py-2 mb-3">
          <?= htmlspecialchars($_GET['error']) ?>
        </div>
      <?php endif; ?>

      <form action="authenticate.php" method="post" autocomplete="off">
        <div class="mb-3">
          <label for="username" class="form-label fw-semibold">Username</label>
          <input id="username" name="username" type="text" class="form-control" placeholder="Masukkan username" required autofocus>
        </div>

        <div class="mb-4">
          <label for="password" class="form-label fw-semibold">Password</label>
          <input id="password" name="password" type="password" class="form-control" placeholder="Masukkan password" required>
        </div>

        <div class="d-grid mb-2">
          <button type="submit" class="btn btn-dark fw-semibold">Login</button>
        </div>

        <div class="d-grid">
          <a href="~/../../index.php" class="btn btn-outline-secondary">← Kembali ke Halaman Awal</a>
        </div>

        <div class="text-center mt-3">
          <span class="text-muted">Belum punya akun?</span>
          <a href="register.php" class="text-danger fw-semibold text-decoration-none">Daftar</a>
        </div>
      </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
