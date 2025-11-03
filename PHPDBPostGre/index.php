<?php
require __DIR__ . '/koneksi.php'; //menghubungkan ke db

$res = q('SELECT  
            "Id_cust" AS id_customer,
            "Nama" AS nama,
            "Email" AS email,
            "No. Telp" AS no_telp,
            "Jenis Kerjasama" AS jenis_kerjasama
          FROM "TB_Customer"
          ORDER BY "Id_cust" ASC'); //ngambil data dr tb

$rows = pg_fetch_all($res) ?: []; //ambil smua hsil query kl gk ada jdikan $rows array kosong spy aman
?>

<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Data Customer (PHP + PostgreSQL)</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: system-ui, Segoe UI, Roboto, Arial, sans-serif;
      max-width: 1000px;
      margin: 24px auto;
      padding: 0 2px;
    }
    table {
      border-collapse: collapse;
      width: 100%;
    }
    th, td {
      border: 1px solid #ddd;
      padding: 8px;
    }
    th {
      background: #f6f6f6;
      text-align: left;
    }
    a.btn {
      padding: 6px 10px;
      border-radius: 6px;
      text-decoration: none;
    }
    .row-actions a {
      margin-right: 8px;
    }
  </style>
</head>

<body>
  <h1>Data Customer</h1>

  <p><a class="btn btn-primary" href="create.php">+ Tambah Customer</a></p>

  <table class="table">
    <thead>
      <tr>
        <th>ID Customer</th>
        <th>Nama</th>
        <th>Email</th>
        <th>No. Telp</th>
        <th>Jenis Kerjasama</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!$rows): ?>  
      <tr> 
        <td colspan="6">Belum ada data.</td>
      </tr>
      <?php else: foreach ($rows as $r): ?> 
      <tr>
        <td><?= htmlspecialchars($r['id_customer']) ?></td>
        <td><?= htmlspecialchars($r['nama']) ?></td>
        <td><?= htmlspecialchars($r['email']) ?></td>
        <td><?= htmlspecialchars($r['no_telp']) ?></td>
        <td><?= htmlspecialchars($r['jenis_kerjasama']) ?></td>
        <td class="row-actions">
          <a class="btn btn-warning" href="edit.php?id=<?= urlencode($r['id_customer']) ?>">Ubah</a>
          <a href="#" class="btn btn-danger" onclick="if(confirm('Hapus data ini?')) { 
            document.getElementById('deleteForm<?= $r['id_customer'] ?>').submit(); 
          }">Hapus</a>

          <form id="deleteForm<?= $r['id_customer'] ?>" action="delete.php" method="post" style="display:none;">
            <input type="hidden" name="id" value="<?= htmlspecialchars($r['id_customer']) ?>">
          </form>
        </td>
      </tr>
      <?php endforeach; endif; ?>
    </tbody>
  </table>
</body>
</html>
