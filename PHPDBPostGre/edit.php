<?php
require __DIR__ . '/koneksi.php';

$err = ''; //menyiapkan variabel untk mnyimpan psn eror apbl ada
$id = (int)($_GET['id'] ?? 0); //mngambil id cust

if ($id <= 0) { //kl id gk valid nmpilkan pesan dan hntikn program
    http_response_code(400);
    exit('ID tidak valid.');
}

try { //mgmbil dt lama dr db
    $res = qparams('SELECT "Nama", "Email", "No. Telp", "Jenis Kerjasama" 
                    FROM "TB_Customer" 
                    WHERE "Id_cust" = $1', [$id]);
    $row = pg_fetch_assoc($res); //mengubah hsl query mjd array asosiatif
    if (!$row) {
        http_response_code(404);
        exit('Data tidak ditemukan.');
    }
} catch (Throwable $e) { // kalau ada error, hentikan program dan tampilkan pesannya
    exit('Error: ' . htmlspecialchars($e->getMessage()));
}
//menyimpan data ke variabel
$nama = $row['Nama'];
$email = $row['Email'];
$no_telp = $row['No. Telp'];
$jenis_kerjasama = $row['Jenis Kerjasama'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') { //ngecek apkh form dikirim lwt post
    $nama = trim($_POST['nama'] ?? '');//ambil nilai input dr form
    $email = trim($_POST['email'] ?? '');
    $no_telp = trim($_POST['no_telp'] ?? '');
    $jenis_kerjasama = trim($_POST['jenis_kerjasama'] ?? '');

    if ($nama === '') { //validasi input
        $err = 'Nama wajib diisi.';
    } elseif ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $err = 'Format email tidak valid.';
    } else { //update ke db
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
            header('Location: index.php'); //kl gk eror balik ke index.php
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
    <h1>Ubah Data Customer</h1>
    
    <?php if ($err): ?>
    <div class="alert error"><?= htmlspecialchars($err) ?></div>
    <?php endif; ?>

    <form method="post">
        <label>Nama
            <input name="nama" value="<?= htmlspecialchars($nama) ?>" required>
        </label>
        <label>Email 
            <input name="email" value="<?= htmlspecialchars($email) ?>" placeholder="nama@email.com">
        </label>
        <label>No. Telp
            <input name="no_telp" value="<?= htmlspecialchars($no_telp) ?>">
        </label>
        <label>Jenis Kerjasama
            <input name="jenis_kerjasama" value="<?= htmlspecialchars($jenis_kerjasama) ?>">
        </label>

        <p style="margin-top:16px">
            <button class="btn btn-success" type="submit">Simpan Perubahan</button>
            <a class="btn btn-info" href="index.php">Batal</a>
        </p>
    </form>
</body>
</html>
