<?php
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: user.php");
    exit();
}

include 'koneksi.php'; // Sambungkan ke database

// Cek apakah koneksi berhasil
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form yang dikirim via POST
    $id = $_POST['id'] ?? '';
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $level = trim($_POST['level'] ?? ''); // Tambahkan pengambilan data level

    // Validasi: Pastikan semua field diisi kecuali password (optional)
    if (empty($id) || empty($username) || empty($email) || empty($level)) {
        echo json_encode(["status" => "error", "message" => "Semua field (kecuali password) harus diisi!"]);
        exit();
    }

    // Validasi tambahan: cek apakah ID valid
    if (!filter_var($id, FILTER_VALIDATE_INT)) {
        echo json_encode(["status" => "error", "message" => "ID tidak valid"]);
        exit();
    }

    // Validasi tambahan: cek format email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["status" => "error", "message" => "Format email tidak valid"]);
        exit();
    }

    // Validasi level
    $allowed_levels = ['admin', 'staff', 'user']; // Sesuaikan dengan level yang valid
    if (!in_array($level, $allowed_levels)) {
        echo json_encode(["status" => "error", "message" => "Level tidak valid"]);
        exit();
    }

    // Jika password diisi, lakukan hashing
    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Query untuk update data user termasuk password dan level
        $sql = "UPDATE user SET username = ?, email = ?, password = ?, level = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        
        if ($stmt === false) {
            echo json_encode(["status" => "error", "message" => "Kesalahan dalam menyiapkan query: " . $conn->error]);
            exit();
        }
        
        // Bind parameter yang diupdate (username, email, hashed_password, level, id)
        $stmt->bind_param("ssssi", $username, $email, $hashed_password, $level, $id);
    } else {
        // Jika password tidak diisi, hanya update username, email, dan level
        $sql = "UPDATE user SET username = ?, email = ?, level = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        
        if ($stmt === false) {
            echo json_encode(["status" => "error", "message" => "Kesalahan dalam menyiapkan query: " . $conn->error]);
            exit();
        }

        // Bind parameter yang diupdate (username, email, level, id)
        $stmt->bind_param("sssi", $username, $email, $level, $id);
    }

    // Eksekusi statement
    if ($stmt->execute()) {
        // Setelah data berhasil diperbarui, arahkan kembali ke halaman user.php
        header("Location: user.php");
        exit();
    } else {
        echo json_encode(["status" => "error", "message" => "Gagal memperbarui data user: " . $stmt->error]);
    }

    // Tutup statement dan koneksi
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["status" => "error", "message" => "Metode request tidak valid"]);
}
