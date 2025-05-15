<?php
session_start();
include 'koneksi.php'; // Sesuaikan dengan file koneksi Anda

if ($conn === null) {
    die("Koneksi ke database gagal.");
}

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    
    // Update status menjadi 'offline'
    $sql = "UPDATE user SET status = 'offline' WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->close();
    } else {
        die("Gagal mempersiapkan statement: " . $conn->error);
    }
    
    // Hapus session
    session_destroy();
}

// Redirect ke halaman login
header("Location: login.php");
exit();
?>
