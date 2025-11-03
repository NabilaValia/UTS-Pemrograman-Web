<?php
require __DIR__ . '/koneksi.php';
try { //jlnkan kode d dlmnya
  $conn = get_pg_connection(); 
  echo "Koneksi OK. Versi server: " . pg_parameter_status($conn, 'server_version'); //menampilkan versi postgre server yg sdg trhubung
} catch (Throwable $e) { //jk eror akan mnampilkan psn eror
  echo "Koneksi gagal: " . $e->getMessage();
}
