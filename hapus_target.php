<?php
include 'koneksi.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Mengamankan input ID

    // Query untuk menghapus data berdasarkan ID
    $sql = "DELETE FROM target WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        // Redirect dengan status sukses setelah berhasil menghapus data
        header("Location: target.php?status=sukses");
        exit();
    } else {
        // Redirect dengan status error jika gagal menghapus data
        header("Location: target.php?status=error");
        exit();
    }
} else {
    // Jika ID tidak tersedia, redirect kembali ke target.php
    header("Location: target.php");
    exit();
}
?>
