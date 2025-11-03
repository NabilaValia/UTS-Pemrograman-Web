<?php
require __DIR__ . '/koneksi.php';

// Cegah akses langsung
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { //hny bs diakses melalui metode post
    http_response_code(405); 
    exit('Method not allowed');
}

$id = (int)($_POST['id'] ?? 0); //mgmbil nilai id dr data form post, int mstikan nilai diubah ke int
if ($id <= 0) { //kl id tdk ada atau krg dr 0 dianggap gk valid
    http_response_code(400); 
    exit('ID tidak valid.');
}

try { //menjalankan proses hapus data
    qparams('DELETE FROM public."TB_Customer" WHERE "Id_cust" = $1', [$id]);
    header('Location: index.php');
    exit;
} catch (Throwable $e) {
    http_response_code(500);
    echo 'Gagal menghapus: ' . htmlspecialchars($e->getMessage());
}
?>
