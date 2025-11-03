<?php
//fungsi koneksi ke postgre 
function get_pg_connection(): PgSql\Connection { //hrs mengembalikan objek koneksi
    static $conn = null; //agar koneksi dibuat sekali saja
    if ($conn instanceof PgSql\Connection) { //memeriksa apakah koneksi sdh aktif
        return $conn;
    }
    //membuat string koneksi
    $connStr = "host=localhost port=5432 dbname=php_database user=postgres password=29082006 options='--client_encoding=UTF8'";
    $conn = @pg_connect($connStr);
    
    //validasi koneksi
    if (!$conn) { 
        throw new RuntimeException("Koneksi PostgreSQL gagal. Periksa host/port/db/user/pass & ekstensi pgsql.");
    }
    return $conn; //jika sukses fungsi kemblikan objek koneksi
}

function q(string $sql) { //eksekusi query biasa
    $conn = get_pg_connection(); 
    $res = @pg_query($conn, $sql); 

    if ($res === false) {
        throw new RuntimeException("Query gagal: " . pg_last_error($conn));
    }
    return $res; //jk berhasil hsilny akan dikembalikan ke pemanggil
}

function qparams(string $sql, array $params = []) { // query dg prmter d pkai di edit atau delete
    $conn = get_pg_connection();

    //menyiapkan query sql di postgre agr bisa d jalankan dgn aman sblm benar2 dieksekusi
    $stmtName = uniqid('stmt_');  
    $prep = @pg_prepare($conn, $stmtName, $sql); 
    if ($prep === false) { 
        throw new RuntimeException("Prepare gagal: " . pg_last_error($conn)); 
    }

    //menjalankan query yg sebelumnya udh di siapkan
    $exec = @pg_execute($conn, $stmtName, $params); 
    if ($exec === false) { 
        throw new RuntimeException("Execute gagal: " . pg_last_error($conn));
    }
    return $exec; //mengembalikan hasil dari eksekusi query ke bagian kode yg manggil fngsi 
}
?>
