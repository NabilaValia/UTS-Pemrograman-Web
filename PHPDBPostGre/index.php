<?php
$host = 'localhost';
$port = '5432';
$dbname = 'php_database';
$user = 'postgres';
$pass = '29082006';

$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$pass");
if (!$conn) {
    die('Koneksi gagal: ' . pg_last_error());
}

pg_set_client_encoding($conn, 'UTF8');

$sql = 'SELECT
            "Id_cust" AS id_customer,
            "Nama" AS nama,
            "Email" AS email,
            "No. Telp" AS no_telp,
            "Jenis Kerjasama" AS jenis_kerjasama
        FROM "TB_Customer"
        ORDER BY "Id_cust"';

$result = pg_query($conn, $sql);
if (!$result) {
    die('Query gagal: ' . pg_last_error($conn));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Customer</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>Daftar Customer</h1>
<table>
<tr>
    <th>ID Customer</th>
    <th>Nama</th>
    <th>Email</th>
    <th>No. Telp</th>
    <th>Jenis Kerjasama</th>
</tr>

<?php while($row = pg_fetch_assoc($result)): ?>
<tr>
    <td><?= htmlspecialchars($row["id_customer"]); ?></td>
    <td><?= htmlspecialchars($row["nama"]); ?></td>
    <td><?= htmlspecialchars($row["email"]); ?></td>
    <td><?= htmlspecialchars($row["no_telp"]); ?></td>
    <td><?= htmlspecialchars($row["jenis_kerjasama"]); ?></td>
</tr>
<?php endwhile; ?>

</table>

</body>
</html>

<?php
pg_free_result($result);
pg_close($conn);
?>
