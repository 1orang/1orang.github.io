<?php
include 'koneksi.php';

$id = $_GET['id'];
// Ambil nama file
$query = $conn->query("SELECT filename FROM upload_foto WHERE id=$id");
$data = $query->fetch_assoc();
$filename = $data['filename'];

// Hapus dari folder
if (file_exists('uploads/' . $filename)) {
    unlink('uploads/' . $filename);
}

// Hapus dari database
$conn->query("DELETE FROM upload_foto WHERE id=$id");

header("Location: index.php");
?>
