<?php
session_start(); //memulai sesi PHP
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require __DIR__ . '/koneksi.php'; // koneksi ke PostgreSQL

// ambil data customer
$res = q('SELECT  
            "Id_cust" AS id_customer,
            "Nama" AS nama,
            "Email" AS email,
            "No. Telp" AS no_telp,
            "Jenis Kerjasama" AS jenis_kerjasama
          FROM "TB_Customer"
          ORDER BY "Id_cust" ASC');

$rows = pg_fetch_all($res) ?: [];
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Dashboard - Data Customer</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-5">
    <div class="container">
      <a class="navbar-brand fw-semibold" href="#">Dashboard</a>
      <div class="d-flex align-items-center">
        <span class="text-white me-3">
          <?= htmlspecialchars($_SESSION['full_name'] ?? $_SESSION['username']) ?>
        </span>
        <a class="btn btn-outline-light btn-sm" href="logout.php">Logout</a>
      </div>
    </div>
  </nav>

  <!-- Konten utama -->
  <div class="container">
    <div class="card shadow-sm mb-4">
      <div class="card-body text-center py-4">
        <h2 class="card-title mb-3 text-primary">
          Selamat datang, <?= htmlspecialchars($_SESSION['full_name'] ?? $_SESSION['username']) ?>!
        </h2>
        <p class="text-muted mb-0">
          Kamu berhasil login<br>
          Berikut adalah data customer.
        </p>
      </div>
    </div> 
  
    <!-- Tabel Data Customer -->
    <div class="card shadow-sm">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h4 class="text-primary mb-0">Data Customer</h4>
          <a class="btn btn-primary" href="create.php">+ Tambah Customer</a>
        </div>

        <div class="table-responsive">
          <table class="table table-bordered align-middle">
            <thead class="table-light">
              <tr>
                <th scope="col">ID Customer</th>
                <th scope="col">Nama</th>
                <th scope="col">Email</th>
                <th scope="col">No. Telp</th>
                <th scope="col">Jenis Kerjasama</th>
                <th scope="col" style="width: 140px;">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!$rows): ?>
              <tr>
                <td colspan="6" class="text-center text-muted">Belum ada data.</td>
              </tr>
              <?php else: foreach ($rows as $r): ?>
              <tr>
                <td><?= htmlspecialchars($r['id_customer']) ?></td>
                <td><?= htmlspecialchars($r['nama']) ?></td>
                <td><?= htmlspecialchars($r['email']) ?></td>
                <td><?= htmlspecialchars($r['no_telp']) ?></td>
                <td><?= htmlspecialchars($r['jenis_kerjasama']) ?></td>
                <td class="text-center">
                  <div class="btn-group btn-group-sm" role="group">
                    <a class="btn btn-warning" href="edit.php?id=<?= urlencode($r['id_customer']) ?>">Ubah</a>
                    <button class="btn btn-danger" 
                      onclick="if(confirm('Hapus data ini?')) { 
                        document.getElementById('deleteForm<?= $r['id_customer'] ?>').submit(); 
                      }">
                      Hapus
                    </button>
                  </div>

                  <form id="deleteForm<?= $r['id_customer'] ?>" action="delete.php" method="post" class="d-none">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($r['id_customer']) ?>">
                  </form>
                </td>
              </tr>
              <?php endforeach; endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
