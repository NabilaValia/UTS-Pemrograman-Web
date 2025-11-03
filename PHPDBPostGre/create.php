<?php
require __DIR__ . '/koneksi.php';

$err = ''; // inisialisasi variabel error
$nama = $email = $no_telp = $jenis_kerjasama = ''; //di set kosong biar aman sblm diisi

if ($_SERVER['REQUEST_METHOD'] === 'POST') { //mengecek apkah halaman diakses dgn methode post
    $nama             = trim($_POST['nama'] ?? ''); //ambil data from (input pengguna)
    $email            = trim($_POST['email'] ?? ''); 
    $no_telp          = trim($_POST['no_telp'] ?? '');
    $jenis_kerjasama  = trim($_POST['jenis_kerjasama'] ?? '');

    if ($nama === '' || $email === '' || $no_telp === '' || $jenis_kerjasama === '') { //validasi input
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
            header('Location: index.php'); //kl sukses diarahkan ke index
            exit;
        } catch (Throwable $e) { //kl gagal tmpilkan pesan eror
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
    <style>
    body {
        font-family: system-ui, Segoe UI, Roboto, Arial, sans-serif;
        max-width: 720px;
        margin: 24px auto;
        padding: 0 12px;
    }

    label {
        display: block;
        margin-top: 10px;
    }

    input {
        width: 100%;
        padding: 8px;
        margin-top: 4px;
    }

    .btn {
        padding: 8px 12px;
        border-radius: 6px;
        text-decoration: none;
    }

    .alert {
        padding: 10px;
        border-radius: 6px;
        margin: 10px 0;
    }

    .alert.error {
        background: #ffe9e9;
        border: 1px solid #e99;
    }
    </style>
</head>

<body>
    <h1>Tambah Customer</h1>

    <?php if ($err): ?>
    <div class="alert error"><?= htmlspecialchars($err) ?></div>
    <?php endif; ?>

    <form method="post">
        <label>Nama
            <input name="nama" value="<?= htmlspecialchars($nama) ?>" required>
        </label>
        <label>Email
            <input name="email" value="<?= htmlspecialchars($email) ?>" required>
        </label>
        <label>No. Telp
            <input name="no_telp" value="<?= htmlspecialchars($no_telp) ?>" required>
        </label>
        <label>Jenis Kerjasama
            <input name="jenis_kerjasama" value="<?= htmlspecialchars($jenis_kerjasama) ?>" required>
        </label>

        <p style="margin-top:16px">
            <button class="btn btn-success" type="submit">Simpan</button>
            <a class="btn btn-info" href="index.php">Kembali</a>
        </p>
    </form>
</body>

</html>