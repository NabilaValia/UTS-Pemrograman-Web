<?php
session_start();

// jika sudah login, redirect ke dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

// buat CSRF token sederhana jika belum ada
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// ambil pesan error/sukses dari query string
$error = $_GET['error'] ?? '';
$success = $_GET['success'] ?? '';
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Daftar Akun | PORTOFOLIO</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light d-flex align-items-center justify-content-center vh-100">

  <div class="card shadow-lg border-0" style="max-width: 450px; width: 100%;">
    <div class="card-body p-4">
      <h5 class="text-center mb-4 fw-semibold">Buat Akun Baru</h5>

      <?php if ($error): ?>
        <div class="alert alert-danger text-center py-2">
          <?= htmlspecialchars($error) ?>
        </div>
      <?php endif; ?>

      <?php if ($success): ?>
        <div class="alert alert-success text-center py-2">
          <?= htmlspecialchars($success) ?>
        </div>
      <?php endif; ?>

      <form action="register_process.php" method="post" autocomplete="off" novalidate>
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

        <div class="mb-3">
          <label for="username" class="form-label fw-semibold">Username</label>
          <input id="username" name="username" type="text" class="form-control" placeholder="Masukkan username" required minlength="3" maxlength="100">
        </div>

        <div class="mb-3">
          <label for="full_name" class="form-label fw-semibold">Nama Lengkap</label>
          <input id="full_name" name="full_name" type="text" class="form-control" placeholder="Masukkan nama lengkap" maxlength="200">
        </div>

        <div class="mb-3">
          <label for="password" class="form-label fw-semibold">Password</label>
          <input id="password" name="password" type="password" class="form-control" placeholder="Minimal 6 karakter" required minlength="6">
        </div>

        <div class="mb-4">
          <label for="password_confirm" class="form-label fw-semibold">Konfirmasi Password</label>
          <input id="password_confirm" name="password_confirm" type="password" class="form-control" placeholder="Ulangi password" required minlength="6">
        </div>

        <div class="d-grid mb-2">
          <button type="submit" class="btn btn-dark fw-semibold">Daftar</button>
        </div>

        <div class="d-grid">
          <a href="login.php" class="btn btn-outline-dark fw-semibold">← Kembali ke Login</a>
        </div>

      </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
