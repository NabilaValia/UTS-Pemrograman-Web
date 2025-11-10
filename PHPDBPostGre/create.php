<?php
require __DIR__ . '/koneksi.php';

$err = '';
$nama = $email = $no_telp = $jenis_kerjasama = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama             = trim($_POST['nama'] ?? '');
    $email            = trim($_POST['email'] ?? '');
    $no_telp          = trim($_POST['no_telp'] ?? '');
    $jenis_kerjasama  = trim($_POST['jenis_kerjasama'] ?? '');

    if ($nama === '' || $email === '' || $no_telp === '' || $jenis_kerjasama === '') {
        $err = 'Semua field wajib diisi.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $err = 'Format email tidak valid.';
    } else {
        try {
            qparams(
                'INSERT INTO "TB_Customer" ("Nama", "Email", "No. Telp", "Jenis Kerjasama")
                 VALUES ($1, $2, $3, $4)',
                [$nama, $email, $no_telp, $jenis_kerjasama]
            );
            header('Location: dashboard.php');
            exit;
        } catch (Throwable $e) {
            $err = 'Execute gagal: ' . htmlspecialchars($e->getMessage());
        }
    }
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Tambah Customer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container py-4">
        <div class="card shadow-sm mx-auto" style="max-width: 600px;">
            <div class="card-body">
                <h1 class="h4 mb-4 text-center text-primary">Tambah Customer</h1>

                <?php if ($err): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($err) ?></div>
                <?php endif; ?>

                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" name="nama" class="form-control" 
                               value="<?= htmlspecialchars($nama) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" 
                               value="<?= htmlspecialchars($email) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">No. Telp</label>
                        <input type="text" name="no_telp" class="form-control" 
                               value="<?= htmlspecialchars($no_telp) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jenis Kerjasama</label>
                        <input type="text" name="jenis_kerjasama" class="form-control" 
                               value="<?= htmlspecialchars($jenis_kerjasama) ?>" required>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="index.php" class="btn btn-outline-secondary">Kembali</a>
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
