<?php
$host = "localhost";
$user = "root";
$pass = ""; // kosongkan jika pakai XAMPP default
$db   = "sistem_akademik_xyz";

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
