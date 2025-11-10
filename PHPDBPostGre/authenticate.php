<?php
session_start(); // memulai sesi PHP
require_once 'koneksi.php'; // memanggil file koneksi ke database

// ambil input dari form login
$username = isset($_POST['username']) ? trim($_POST['username']) : ''; // hapus spasi di depan/belakang
$password = isset($_POST['password']) ? $_POST['password'] : ''; // ambil password apa adanya (tidak di-trim)

// koneksi ke database PostgreSQL
try {
    $conn = get_pg_connection(); // fungsi ini ada di koneksi.php
} catch (Throwable $e) {
    // kalau koneksi gagal, catat error dan arahkan kembali ke halaman login
    error_log('DB connection error in authenticate.php: ' . $e->getMessage());
    header('Location: login.php?error=' . urlencode('Gagal koneksi ke database.'));
    exit;
}

// validasi awal: cek apakah form diisi semua
if ($username === '' || $password === '') {
    header('Location: login.php?error=' . urlencode('Username dan password harus diisi.'));
    exit;
}

// query database untuk mencari user berdasarkan username
$sql = 'SELECT id, username, password_hash, full_name FROM users WHERE username = $1 LIMIT 1';
$result = pg_query_params($conn, $sql, array($username)); // kirim username sebagai parameter

// jika query gagal dijalankan
if (!$result) {
    header('Location: login.php?error=' . urlencode('Terjadi kesalahan pada server.'));
    exit;
}

// jika tidak ditemukan user dengan username itu
if (pg_num_rows($result) === 0) {
    header('Location: login.php?error=' . urlencode('Username atau password salah.'));
    exit;
}

// ambil data user dalam bentuk array asosiatif
$user = pg_fetch_assoc($result);
$hash = $user['password_hash']; // ambil hash password dari database

// verifikasi apakah password yang diketik cocok dengan hash di database
if (password_verify($password, $hash)) {
    // jika benar, buat sesi login
    session_regenerate_id(true); 
    $_SESSION['user_id'] = $user['id'];         
    $_SESSION['username'] = $user['username'];  
    $_SESSION['full_name'] = $user['full_name']; 

    // arahkan user ke dashboard setelah login berhasil
    header('Location: dashboard.php');
    exit;
} else {
    // jika password salah
    header('Location: login.php?error=' . urlencode('Username atau password salah.'));
    exit;
}
