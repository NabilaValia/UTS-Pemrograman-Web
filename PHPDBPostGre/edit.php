<?php
require __DIR__ . '/koneksi.php';

$err = '';
$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    http_response_code(400);
    exit('ID tidak valid.');
}

try {
    $res = qparams('SELECT "Nama", "Email", "No. Telp", "Jenis Kerjasama" 
                    FROM "TB_Customer" 
                    WHERE "Id_cust" = $1', [$id]);
    $row = pg_fetch_assoc($res);
    if (!$row) {
        http_response_code(404);
        exit('Data tidak ditemukan.');
    }
} catch (Throwable $e) {
    exit('Error: ' . htmlspecialchars($e->getMessage()));
}

$nama = $row['Nama']; 
$email = $row['Email'];
$no_telp = $row['No. Telp'];
$jenis_kerjasama = $row['Jenis Kerjasama'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $no_telp = trim($_POST['no_telp'] ?? '');
    $jenis_kerjasama = trim($_POST['jenis_kerjasama'] ?? '');

    if ($nama === '') {
        $err = 'Nama wajib diisi.';
    } elseif ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $err = 'Format email tidak valid.';
    } else {
        try {
            qparams(
                'UPDATE "TB_Customer"
                   SET "Nama" = $1,
                       "Email" = NULLIF($2, \'\'),
                       "No. Telp" = NULLIF($3, \'\'),
                       "Jenis Kerjasama" = NULLIF($4, \'\')
                 WHERE "Id_cust" = $5',
                [$nama, $email, $no_telp, $jenis_kerjasama, $id]
            );
            header('Location: dashboard.php');
            exit;
        } catch (Throwable $e) {
            $err = $e->getMessage();
        }
    }
}
?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Ubah Data Customer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container py-4">
        <div class="card shadow-sm mx-auto" style="max-width: 600px;">
            <div class="card-body">
                <h1 class="h4 mb-4 text-center text-primary">Ubah Data Customer</h1>

                <?php if ($err): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($err) ?></div>
                <?php endif; ?>

                <form method="post">
                    <div class="mb-3"> 
                        <label class="form-label">Nama</label>
                        <input name="nama" class="form-control" value="<?= htmlspecialchars($nama) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input name="email" type="email" class="form-control" 
                               value="<?= htmlspecialchars($email) ?>" placeholder="nama@email.com">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">No. Telp</label>
                        <input name="no_telp" class="form-control" value="<?= htmlspecialchars($no_telp) ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jenis Kerjasama</label>
                        <input name="jenis_kerjasama" class="form-control" value="<?= htmlspecialchars($jenis_kerjasama) ?>">
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="index.php" class="btn btn-outline-secondary">Batal</a>
                        <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
