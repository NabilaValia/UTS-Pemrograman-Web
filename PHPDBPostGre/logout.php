<?php
session_start(); // mulai atau lanjutkan sesi agar bisa menghapus data session yang ada

// kosongkan semua data di $_SESSION
$_SESSION = array();

// kalau session menggunakan cookie, hapus juga cookie-nya agar benar-benar logout
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params(); // ambil pengaturan cookie dari session
    setcookie(
        session_name(),   // nama cookie (biasanya PHPSESSID)
        '',               // isi kosong
        time() - 42000,   // waktu kedaluwarsa di masa lalu agar cookie dihapus
        $params["path"], 
        $params["domain"],
        $params["secure"], 
        $params["httponly"]
    );
}

// hancurkan session di server
session_destroy();

// arahkan kembali ke halaman login setelah logout
header('Location: login.php');
exit;
