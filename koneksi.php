<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "coba";

$conn = new mysqli($host, $user, $pass, $dbname);

// Cek koneksi dengan debugging
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
//} else {
    //echo "Koneksi berhasil!";
}//
?>
