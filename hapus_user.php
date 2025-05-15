<?php
include 'koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $query = "DELETE FROM user WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>alert('User berhasil dihapus'); window.location.href='user.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus user!'); window.history.back();</script>";
    }
}
?>